@extends('layouts.main')

@section('content')
<div id="about">
    {{-- Section Content --}}
    <div class="section-content" id="resource-content">
                
        <div class="row">
        {{-- Column --}}
        <div class="col-md-6">
            <div class="brief no-margin text-left">
                <div class="brief brief-inner">
                    <h3 class="t-left f-normal ultrabold uppercase">{{ $project->title }}</h3>
                    <div class="t-left project-content">{!! $project->content !!}</div>
                </div>
            </div>

            @foreach($project->getMedia('default') as $media)
            <a href="{{ $media->getUrl('large') }}" data-lightbox="works" title="{{ $media->name }}"><img src="{{ $media->getUrl('large') }}" alt="{{ $media->name }}" data-uk-scrollspy="{cls:'uk-animation-fade'}"></a>
            @endforeach
        </div>
        {{-- End Column --}}
        </div>
    </div>
</div>
@endsection