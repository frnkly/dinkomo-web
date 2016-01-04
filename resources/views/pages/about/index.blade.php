@extends('layouts.narrow')

@section('body')

	<section>
        <h1>
            <span ng-hide="language">Di Nkɔmɔ</span>

            <br>
            <small>A Collection Of Cultures</small>
        </h1>

        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 text-center">
                Di Nkɔmɔ is a project I started in 2014 to help myself learn
                <a href="/twi">Twi</a>,
                my native language. It quickly became a cultural repository which I hope will
                be useful to others. Since the first version of the app, Di Nkɔmɔ has grown from just
                under 100 Twi words to
                <a href="{{ route('stats') }}">{{ $defs }} definitions in {{ $langs }} languages</a>
                and counting.
            </div>
        </div>

        <div class="timeline">

            <!-- 100 words -->
            <div class="event">
                <div class="event-marker"></div>

                <div class="event-details">
                    <h1>First Milestone</h1>
                    <p>Reached 100 words.</p>
                    <span class="date">Sep 2015</span>
                </div>
            </div>

            <!-- Trip to Ghana -->
            <div class="event">
                <div class="event-marker"></div>

                <div class="event-details">
                    <h1>Trip to Ghana</h1>
                    <p>Learning Dagbani, idea expands to include words and concepts from other languages.</p>
                    <span class="date">May 2014</span>
                </div>
            </div>

            <!-- Birth of idea -->
            <div class="event">
                <div class="event-marker"></div>

                <div class="event-details">
                    <h1>Birth of Idea</h1>
                    <p>An online Twi dictionary to practice and learn new words.</p>
                    <span class="date">Early 2014</span>
                </div>
            </div>
        </div>
        <br>

        <a href="/twi/di_nkɔmɔ">Di Nkɔmɔ</a> is Twi for chat, or converse.
        This app was built with <a href="http://laravel.com" target="_blank">Laravel</a>,
        <a href="https://angularjs.org" target="_blank">Angular</a> and other great resources such as:
        <br><br>

        <ul>
            <!-- <li>
                Asante Twi for Beginners
            </li> -->
            <li>
                <a href="http://www.iso.org/iso/catalogue_detail?csnumber=39534" target="_blank">ISO 639-3</a>
                codes for the representation of names of languages.
            </li>
            <li>
                The <a href="http://www.ethnologue.com/" target="_blank">Ethnologue's</a>
                extensive catalog.
            </li>
            <li>
                The <a href="http://glottolog.org/" target="_blank">Glottolog's</a>
                extensive catalog.
            </li>
            <li>
                <a href="http://cldr.unicode.org" target="_blank">CLDR</a>, or Unicode's Common
                Locale Data Repository.
            </li>
        </ul>
        <br />

        I've spent a lot of time on this app. If you have any thoughts you'd like to share,
        <a href="mailto:&#102;&#114;&#97;&#110;&#107;&#64;&#102;&#114;&#110;&#107;&#46;&#99;&#97;">please do</a> !
	</section>

@stop