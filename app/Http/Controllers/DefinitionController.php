<?php

namespace App\Http\Controllers;

use App\Resources\Definition;
use Illuminate\Validation\Rule;

class DefinitionController extends Controller
{
    /**
     * Supported definition types.
     *
     * @const array
     */
    const SUPPORTED_TYPES = [
        'word',
        'expression',
    ];

    protected function boot()
    {
        $this->middleware('auth')
            ->only('create', 'edit', 'store', 'update', 'destroy');
    }

    /**
     * Displays the form to add a new definition.
     *
     * @param  string  $type
     * @param  string  $lang
     * @return \Illuminate\View\View
     */
    public function create($type = 'word', $lang = '')
    {
        $subType    = '';
        $languages  = [];
        $tags       = [];
        $type       = in_array($type, ['word', 'expression']) ? $type : 'word';

        foreach (explode(',', $lang) as $code) {
            $code = preg_replace('/[^a-z-]/', '', strtolower($code));

            try {
                if ($language = $this->getLanguage($code)) {
                    $languages[$language->code] = $language->name;
                }
            } catch (\Exception $e) {}
        }

        if ($this->request->filled('tags')) {
            // TODO: add tags
        }

        return $this->form([
            'id'            => null,
            'type'          => $type,
            'subType'       => $subType,
            'titleString'   => $this->request->get('title', ''),
            'practical'     => $this->request->get('translation', ''),
            'literal'       => $this->request->get('literally', ''),
            'meaning'       => $this->request->get('meaning'),
            'languages'     => $languages,
            'tags'          => $tags,
        ]);
    }

    /**
     * Stores a new definition on the API.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        return $this->save();
    }

    /**
     * Renders the definition view for the given ID.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! $definition = $this->getDefinition($id)) {
            abort(404);
        }

        // List of languages
        $namedLanguages  = [];
        $linkedLanguages = [];

        foreach ($definition->languages as $lang) {
            $fullName = $lang->name.($lang->altNames ? ' ('.trim($lang->altNames).')' : '');

            $namedLanguages[]   = '<a href="'.route('language', $lang->code).'">'.$fullName.'</a>';
            $linkedLanguages[]  = '<em><a href="'.route('language', $lang->code).'">'.$lang->name.'</a></em>';
        }

        return view('definition.index')->with([
            'definition'        => $definition,
            'namedLanguages'    => $definition->listToString($namedLanguages, 'and'),
            'linkedLanguages'   => $definition->listToString($linkedLanguages, 'and'),
        ]);
    }

    /**
     * Retrieves a random definition in the specified language, and redirects to that definition's
     * page.
     *
     * @param  string $lang
     * @return \Illuminate\Http\RedirectResponse
     */
    public function random($lang = null)
    {
        if (! $definition = $this->api->getRandomDefinition($lang)) {
            return redirect(route('home'))->withErrors($this->api->getErrors());
        }

        return redirect(route('definition.show', $definition->uniqueId));
    }

    /**
     * Dispays the form to edit a defintion.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if (! $definition = $this->getdefinition($id)) {
            abort(404);
        }

        // Languages
        $languages = [];

        foreach ($definition->languages as $lang) {
            $languages[$lang->code] = $lang->name;
        }

        return $this->form([
            'id'            => $definition->uniqueId,
            'type'          => $definition->type,
            'subType'       => $definition->subType,
            'titleString'   => implode(', ', array_pluck($definition->titles, 'title')),
            'practical'     => $definition->getTranslation()->practical,
            'literal'       => $definition->getTranslation()->literal,
            'meaning'       => $definition->getTranslation()->meaning,
            'languages'     => $languages,
            'tags'          => [],
        ]);
    }

    /**
     * Updates a definition on the API.
     *
     * @param  string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        return $this->save($id);
    }

    /**
     * @param  array $details
     * @return \Illuminate\View\View
     */
    protected function form(array $details)
    {
        if (! in_array($details['type'], self::SUPPORTED_TYPES)) {
            abort(501, 'Unsupported Definition Type.');
        }

        // NOTE: this will be handled by a JS framework on the frontend some day.
        $details['subTypes'] = [];
        switch ($details['type']) {
            case 'word':
                $details['subTypes'] = [
                    '[ part of speech ]',
                    'adj'   => 'adjective',
                    'adv'   => 'adverb',
                    'conn'  => 'connective',
                    'ex'    => 'exclamation',
                    'prep'  => 'preposition',
                    'pro'   => 'pronoun',
                    'n'     => 'noun',
                    'v'     => 'verb',
                    'intv'  => 'intransitive verb',
                ];
                break;

            case 'expression':
                $details['subTypes'] = [
                    '[ expression types ]',
                    'expression'    => 'common expression',
                    'phrase'        => 'simple phrase',
                    'proverb'       => 'proverb or saying',
                ];
                break;
        }

        // Temp hack: fix definition subtype.
        $subTypesAbbreviations = [
            'adjective'     => 'adj',
            'adverb'        => 'adv',
            'connective'    => 'conn',
            'exclamation'   => 'ex',
            'preposition'   => 'pre',
            'pronoun'       => 'pro',
            'noun'          => 'n',
            'verb'          => 'v',
            'intransitive verb' => 'intv',
        ];

        if (array_key_exists($details['subType'], $subTypesAbbreviations)) {
            $details['subType'] = $subTypesAbbreviations[$details['subType']];
        }

        return view('definition.'.$details['type'].'.form', $details);
    }

    /**
     * Saves a definition resource on the API.
     *
     * @param  string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function save($id = null)
    {
        // TODO: improve validation to avoid unecessary API calls.
        $this->validate($this->request, [
            'title'     => 'required',
            'type'      => ['required', Rule::in(self::SUPPORTED_TYPES)],
            'subType'   => 'required',
            'languages' => 'required',
            'practical' => 'required',
            'literal'   => '',
            'meaning'   => '',
            'tags'      => '',
        ]);

        // Check titles
        $titles = [];
        foreach (explode(',', $this->request->get('title')) as $str) {
            $titles[] = ['title' => trim($str)];
        }

        $data = [
            'type'          => $this->request->get('type'),
            'subType'       => $this->request->get('subType'),
            'titles'        => $titles,
            'translations'  => [
                [
                    'language'  => 'eng',
                    'practical' => $this->request->get('practical'),
                    'literal'   => $this->request->get('literal'),
                    'meaning'   => $this->request->get('meaning'),
                ]
            ],
            'languages'     => explode(',', $this->request->get('languages')),
        ];

        $failRoute = $id
            ? route('definition.show', $id)
            : route('definition.create');

        try {
            if ($id) {
                $saved = $this->api->patch(
                    $this->request->user()->getAccessToken(),
                    'definitions/'.$id,
                    $data
                );
            } else {
                $saved = $this->api->post(
                    $this->request->user()->getAccessToken(),
                    'definitions',
                    $data
                );
            }
        } catch (\Exception $e) {
            return $this->redirectWithErrors(
                $failRoute,
                'Could not save definition',
                $e
            );
        }

        if (! $saved) {
            return redirect($failRoute)->withErrors('Could not save definition');
        }

        // Clear local cache
        if ($id) {
            $this->cache->forget($this->getCacheKey($id));
        }

        return redirect(route('definition.show', ['id' => $saved->uniqueId, 'saved' => 1]));
    }
}
