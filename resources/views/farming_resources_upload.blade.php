@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Upload New Farming Resource</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ url('/farming-resources/upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="video">Video</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Resource Source</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="resource_source" id="source_file" value="file" checked>
                            <label class="form-check-label" for="source_file">Upload File</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="resource_source" id="source_youtube" value="youtube">
                            <label class="form-check-label" for="source_youtube">YouTube Link</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3" id="file_input_group">
                    <label for="file" class="form-label">File</label>
                    <input type="file" class="form-control" id="file" name="file" accept="video/*,application/pdf">
                </div>
                <div class="mb-3 d-none" id="youtube_link_group">
                    <label for="youtube_link" class="form-label">YouTube Link</label>
                    <input type="url" class="form-control" id="youtube_link" name="youtube_link" placeholder="https://www.youtube.com/watch?v=...">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="is_offline_available" name="is_offline_available" checked>
                    <label class="form-check-label" for="is_offline_available">
                        Available for offline download
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
                <a href="{{ url('/farming-resources') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputGroup = document.getElementById('file_input_group');
        const youtubeLinkGroup = document.getElementById('youtube_link_group');
        const sourceFile = document.getElementById('source_file');
        const sourceYoutube = document.getElementById('source_youtube');
        
        function toggleResourceSource() {
            if (sourceFile.checked) {
                fileInputGroup.classList.remove('d-none');
                youtubeLinkGroup.classList.add('d-none');
                document.getElementById('file').required = true;
                document.getElementById('youtube_link').required = false;
            } else {
                fileInputGroup.classList.add('d-none');
                youtubeLinkGroup.classList.remove('d-none');
                document.getElementById('file').required = false;
                document.getElementById('youtube_link').required = true;
            }
        }
        sourceFile.addEventListener('change', toggleResourceSource);
        sourceYoutube.addEventListener('change', toggleResourceSource);
        toggleResourceSource();
    });
</script>
@endpush 