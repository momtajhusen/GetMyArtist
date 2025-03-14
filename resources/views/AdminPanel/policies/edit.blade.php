@extends('AdminPanel.admin-layout')

@section('styles')
<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('scripts')
<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
<!-- Page JS -->
<script src="{{ asset('assets/js/forms-editors.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Quill editor initialization using Snow theme
        var quill = new Quill('#snow-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': [] }, { 'size': [] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'header': [1, 2, false] }],
                    ['blockquote', 'code-block'],
                    ['clean']
                ]
            }
        });

        // Set initial content from hidden input if available
        var initialContent = document.getElementById('hidden-content').value;
        quill.root.innerHTML = initialContent;

        // Sync editor content into hidden input before form submission
        document.getElementById('policy-form').addEventListener('submit', function() {
            document.getElementById('hidden-content').value = quill.root.innerHTML;
        });
    });
</script>
@endsection

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <h5 class="card-header">{{ $policy->title }}</h5>
        <div class="card-body">
            <form id="policy-form" action="{{ route('policies.update', $policy->type) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Hidden input to hold editor content -->
                <input type="hidden" name="content" id="hidden-content" value="{{ old('content', $policy->content) }}">
                <!-- Snow Theme Quill Editor -->
                <div id="snow-editor" style="height: 300px;">
                    <!-- Quill editor will render here -->
                </div>
                <button type="submit" class="btn btn-success mt-3">Update Policy</button>
            </form>
        </div>
    </div>
</div>
@endsection
