@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Agriculture & Farming News</h2>
        <button class="btn btn-primary" onclick="fetchLatestNews()">
            <i class="fas fa-sync-alt"></i> Refresh News
        </button>
    </div>

    @if($news->count() > 0)
        <div class="row">
            @foreach($news as $article)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        @if($article->image_url)
                            <img src="{{ $article->image_url }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary">{{ $article->country }}</span>
                                <small class="text-muted">{{ $article->published_date->format('M d, Y') }}</small>
                            </div>
                            <h5 class="card-title">{{ Str::limit($article->title, 80) }}</h5>
                            <p class="card-text">{{ Str::limit($article->description, 120) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Source: {{ $article->source }}</small>
                                <a href="{{ route('news.show', $article) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $news->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
            <h4>No news articles found</h4>
            <p class="text-muted">Try refreshing the news or check back later.</p>
            <button class="btn btn-primary" onclick="fetchLatestNews()">
                <i class="fas fa-sync-alt"></i> Fetch Latest News
            </button>
        </div>
    @endif
</div>

@push('scripts')
<script>
function fetchLatestNews() {
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching...';
    button.disabled = true;

    // Make AJAX request to fetch news
    fetch('/api/fetch-news', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to fetch news. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching news.');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}
</script>
@endpush
@endsection 