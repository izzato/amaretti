@extends('layouts.flipbook')

@section('title')
{{ $proposal->title }} 
@endsection

@section('content')
<script src="https://unpkg.com/pdfobject@2.2.8/pdfobject.min.js"></script>

<div id="the-canvas" style="height: 100vh"></div>

<script>PDFObject.embed("{{ url('storage/uploads/'. $proposal->pathHash() . '.pdf') }}", "#the-canvas");</script>
@endsection