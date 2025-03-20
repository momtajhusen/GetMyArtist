@extends('ArtistPanel.artist-layout')

@section('content')
<div class="col-12">
    <div class="card">
      <h5 class="card-header">Upload Album</h5>
      <div class="card-body">
    

        <form action="{{ route('artist.albums.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
          
          <div class="mb-3">
            <label for="title" class="form-label">Album Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Enter album title" required>
          </div>

    
        <!-- Upload Type -->
        <div class="mb-3">
            <label for="upload_type" class="form-label">Upload Type</label>
            <select name="upload_type" id="upload_type" class="form-select" required>
            <option value="local" selected>Local Upload</option>
            <option value="url">URL Upload</option>
            </select>
        </div>
    
        <!-- Local Upload Input (Visible by Default) -->
        <div class="mb-3" id="local_upload_div" style="display: block;">
            <label for="media_file" class="form-label">Upload Media File</label>
            <input type="file" name="media_file" id="media_file" class="form-control">
        </div>

        <!-- Media URL Input (Hidden by Default) -->
        <div class="mb-3" id="url_upload_div" style="display: none;">
            <label for="media_url" class="form-label">Media URL</label>
            <input type="url" name="media_url" id="media_url" class="form-control" placeholder="Enter media URL" pattern="https?://.+" >
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control" placeholder="Enter album description"></textarea>
        </div>
    
        <button type="submit" class="btn btn-primary">Upload Album</button>
    
            </form>
        </div>
        </div>
  </div>

<!-- JavaScript to toggle input fields based on Upload Type selection -->
<script>
  document.getElementById('upload_type').addEventListener('change', function() {
    var uploadType = this.value;
    if (uploadType === 'local') {
      document.getElementById('local_upload_div').style.display = 'block';
      document.getElementById('url_upload_div').style.display = 'none';
      document.getElementById('media_url').required = false;
    } else if (uploadType === 'url') {
      document.getElementById('local_upload_div').style.display = 'none';
      document.getElementById('url_upload_div').style.display = 'block';
      document.getElementById('media_url').required = true;
    } else {
      document.getElementById('local_upload_div').style.display = 'none';
      document.getElementById('url_upload_div').style.display = 'none';
      document.getElementById('media_url').required = false;
    }
  });
</script>
@endsection
