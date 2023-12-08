@extends('layout/app')
@section('title', 'Home')
@section('content')
<div class="container mt-4">
    <div class="jumbotron">
        <h1 class="display-4">Welcome to the Worktogether app</h1>
        <p class="lead my-4">Sync, Collaborate, Succeed: WorkTogether Paves the Way!</p>
        @guest
        <a class="btn btn-primary btn-lg mr-3" href="{{ route('register') }}">Register</a>
            <a class="btn btn-secondary btn-lg" href="{{ route('login') }}">Login</a>
        <hr class="my-4">
        <div class="video-container">
            <iframe width="900" height="515" src="https://www.youtube.com/embed/UpOXFPQPNh4?si=0IDWwOHLbjwyaxKw"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
        </div>
        @endguest

        @auth
        <p>Use the navigation menu to discover the features.</p>
        @endauth
        
    </div>
</div>
@endsection