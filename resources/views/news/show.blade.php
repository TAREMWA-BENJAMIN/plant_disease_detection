@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('news.index') }}">News</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Article</li>
                </ol>
            </nav>

            <article class="card">
                @if($news->image_url)
                    <img src="{{ $news->image_url }}" class="card-img-top" alt="{{ $news->title }}" style="max-height: 400px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary">{{ $news->country }}</span>
                        <small class="text-muted">{{ $news->published_date->format('F d, Y \a\t g:i A') }}</small>
                    </div>
                    
                    <h1 class="card-title">{{ $news->title }}</h1>
                    
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-user"></i> Source: {{ $news->source }}
                            @if($news->language)
                                | <i class="fas fa-language"></i> {{ strtoupper($news->language) }}
                            @endif
                        </small>
                    </div>
                    
                    @if($news->description)
                        <div class="mb-4">
                            <h5>Summary</h5>
                            <p class="lead">{{ $news->description }}</p>
                        </div>
                    @endif
                    
                    @if($news->content)
                        <div class="mb-4">
                            <h5>Content</h5>
                            <div class="news-content">
                                {!! nl2br(e($news->content)) !!}
                            </div>
                        </div>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ $news->url }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> Read Original Article
                        </a>
                        <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to News
                        </a>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>

<style>
.news-content {
    line-height: 1.6;
    font-size: 1.1rem;
}
</style>
@endsection 