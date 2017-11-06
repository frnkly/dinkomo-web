@extends('layouts.half-hero')

@section('hero')

    <h1 class="hero-title">
        <small>Learn</small>
        {{ $lang->name }}
    </h1>

@stop

@section('body')

    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
            We're building a <strong>free</strong> language learning tool for
            <strong><a href="{{ route('language.show', $lang->code) }}">{{ $lang->name }}</a></strong>.

            @include('language.learn.signup', ['name' => $lang->name])
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
            @lang('branding.pitch')
            <hr>
        </div>
    </div>

@stop
