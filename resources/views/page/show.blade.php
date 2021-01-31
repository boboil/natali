@extends('app')
@section('content')

    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
        </div><!-- End .container -->
    </nav>

    <div class="page-header">
        <div class="container">
            <h1>{{ $page->title }}</h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->

    <div class="container">
            {!! $page->description !!}
    </div><!-- End .container -->

@endsection


