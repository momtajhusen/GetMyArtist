@extends('AdminPanel.admin-layout')

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
            <form action="{{ route('policies.update', $policy->type) }}" method="POST">
                @csrf
                @method('PUT')
                <textarea name="content" id="tinymce-editorssss" rows="10" class="form-control">{{ old('content', $policy->content) }}</textarea>
                <button type="submit" class="btn btn-success mt-3">Update Policy</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/hv66gue8zty2p0gpg9e8ngk639jk0s8ml1zl2lxgdo91xedv/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#tinymce-editor', // matches the id on the textarea
    plugins: 'code table lists',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
  });
</script>
@endsection
