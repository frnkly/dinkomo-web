
{{-- Author --}}
<div class="row">
    <input
        type="text"
        name="data[author]"
        id="data[author]"
        class="en-text-input"
        placeholder="authors of the reference"
        value="{{ $model->getDataParam('author') }}"
        autocomplete="on"
        required="required">
    <label for="data[author]">Reference authors</label>
</div>

{{-- Title --}}
<div class="row">
    <input
        type="text"
        name="data[title]"
        id="data[title]"
        class="en-text-input"
        placeholder="title of the reference"
        value="{{ $model->getDataParam('title') }}"
        autocomplete="off"
        required="required">
    <label for="data[title]">Reference title</label>
</div>

{{-- Date --}}
<div class="row">
    <input
        type="date"
        name="data[date]"
        id="data[date]"
        class="en-text-input"
        placeholder="publication date"
        value="{{ $model->getDataParam('date') }}"
        autocomplete="off">
    <label for="data[date]">Publication date</label>
</div>

{{-- Publisher --}}
<div class="row">
    <input
        type="text"
        name="data[publisher]"
        id="data[publisher]"
        class="en-text-input"
        placeholder="publisher of the reference"
        value="{{ $model->getDataParam('publisher') }}"
        autocomplete="on">
    <label for="data[publisher]">Publisher</label>
</div>

{{-- City --}}
<div class="row">
    <input
        type="text"
        name="data[city]"
        id="data[city]"
        class="en-text-input"
        placeholder="city where reference was published"
        value="{{ $model->getDataParam('city') }}"
        autocomplete="on">
    <label for="data[city]">City</label>
</div>

{{-- Country --}}
<div class="row">
    <input
        type="text"
        name="data[country]"
        id="data[country]"
        class="en-text-input"
        placeholder="country where reference was published"
        value="{{ $model->getDataParam('country') }}"
        autocomplete="on">
    <label for="data[country]">Country</label>
</div>
