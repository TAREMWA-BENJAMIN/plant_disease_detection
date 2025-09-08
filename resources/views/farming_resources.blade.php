@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Farming Resources (Videos & PDFs)</h2>
    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ url('/farming-resources/upload') }}" class="btn btn-success">Upload Resource</a>
    </div>
    <div class="row">
        @foreach($resources as $resource)
            <div class="col-md-8 offset-md-2 mb-5">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title mb-3">{{ $resource->title }}</h3>
                        @if($resource->type === 'video')
                            <div class="mb-3 text-center">
                                @if($resource->youtube_link)
                                    @php
                                        function getYoutubeId($url) {
                                            $host = parse_url($url, PHP_URL_HOST);
                                            if (str_contains($host, 'youtu.be')) {
                                                return ltrim(parse_url($url, PHP_URL_PATH), '/');
                                            }
                                            parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);
                                            if (isset($query['v'])) {
                                                return $query['v'];
                                            }
                                            if (str_contains($url, '/embed/')) {
                                                return last(explode('/embed/', $url));
                                            }
                                            return null;
                                        }
                                        $videoId = getYoutubeId($resource->youtube_link);
                                    @endphp
                                    @if($videoId)
                                        <div class="ratio ratio-16x9">
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" title="YouTube video" allowfullscreen></iframe>
                                        </div>
                                    @else
                                        <div class="alert alert-danger">Invalid YouTube link.</div>
                                    @endif
                                @else
                                    <video width="100%" height="360" controls poster="{{ $resource->thumbnail_url ?? '' }}">
                                        <source src="{{ $resource->file_url }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        @elseif($resource->type === 'pdf')
                            <div class="mb-3 text-center">
                                <a href="{{ $resource->file_url }}" target="_blank" class="d-inline-flex align-items-center text-decoration-none">
                                    <img src="https://cdn.jsdelivr.net/gh/edent/SuperTinyIcons/images/svg/pdf.svg" alt="PDF" width="48" height="48" class="me-2">
                                    <span class="fs-5">Download PDF</span>
                                </a>
                            </div>
                        @endif
                        @if($resource->description)
                            <p class="card-text mt-3">{{ $resource->description }}</p>
                        @endif
                        <form action="{{ url('/farming-resources/' . $resource->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this resource?');" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 