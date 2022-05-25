@extends('layouts.main')

@section('title')
Projects
@endsection

@section('content')
{{-- Portfolio --}}
<div id="portfolio">
    {{-- Section Content --}}
    <div class="section-content">

        {{-- Portfolio Filters --}}
        <ul id="work" class="works-filter gutter uk-subnav uppercase ultrabold">

            <li data-uk-filter="" data-uk-scrollspy="{cls:'uk-animation-fade', delay:20}"><a href="#">All</a></li>
            @foreach($categories as $index => $category)
            <li data-uk-filter="filter-{{ $category->slug }}" data-uk-scrollspy="{cls:'uk-animation-fade', delay:{{ 20 + (40/2*($index+1)) }}}"><a href="#">{{ $category->name }}</a></li>
            @endforeach
        </ul>
        {{-- End Portfolio Filters --}}

        {{-- Work Previews --}}
        <div data-uk-grid="{controls: '#work'}">

            @foreach($projects as $item)
            {{-- Work --}}
            <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-{{ implode(',filter-', $item->categories->map(function($i, $j){
                return $i->slug;
            })->all()) }}">
            <div class="work-image" style="background: url({{ $item->featuredImage }}) no-repeat center center;"></div>

            <a href="{{ route('projects.show', $item) }}">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">{{ $item->title }}</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}
        @endforeach

        @if(env('SHOW_SAMPLE_WORK'))
        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-web">

            {{-- Work Image --}}
            <img src="img/works/02.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="project-01.html">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Cairo</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}

        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-graphic">

            {{-- Work Image --}}
            <img src="img/works/03.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="https://www.youtube.com/watch?v=YE7VzlLtp-4" data-uk-lightbox="{group:'works'}">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Berlin</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}

        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-web">

            {{-- Work Image --}}
            <img src="img/works/04.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="project-01.html">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Rafi</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}

        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-graphic">

            {{-- Work Image --}}
            <img src="img/works/05.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="img/works/05.jpg" data-lightbox="works">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Adios</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}

        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-web">

            {{-- Work Image --}}
            <img src="img/works/06.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="project-01.html">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Goblin</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}

        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-graphic">

            {{-- Work Image --}}
            <img src="img/works/07.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="img/works/07.jpg" data-lightbox="works">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Mozart</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}

        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-graphic">

            {{-- Work Image --}}
            <img src="img/works/08.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="project-01.html">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Kuddus</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}

        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-graphic">

            {{-- Work Image --}}
            <img src="img/works/09.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="img/works/09.jpg" data-lightbox="works">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Royale</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}

        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-graphic">

            {{-- Work Image --}}
            <img src="img/works/05.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="img/works/05.jpg" data-lightbox="works">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Lulaby</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}

        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-graphic">

            {{-- Work Image --}}
            <img src="img/works/08.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="img/works/08.jpg" data-lightbox="works">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Gango</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}

        {{-- Work --}}
        <div class="work gutter uk-width-small-1-2 uk-width-medium-1-4" data-uk-filter="filter-graphic">

            {{-- Work Image --}}
            <img src="img/works/01.jpg" alt="">
            {{-- End Work Image --}}

            {{-- Hover Lightbox --}}
            <a href="img/works/01.jpg" data-lightbox="works">

                {{-- Hover --}}
                <div class="hover">

                    {{-- Title --}}
                    <p class="dark f-normal normal uppercase">Chipset</p>

                </div>
                {{-- End Hover --}}

            </a>
            {{-- End Hover Lightbox --}}

        </div>
        {{-- End Work --}}
        @endif

    </div>
    {{-- End Work Previews --}}

</div>
{{-- End Section Content --}}

</div>
{{-- End Portfolio --}}
@endsection
