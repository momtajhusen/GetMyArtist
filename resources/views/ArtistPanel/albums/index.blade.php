@extends('ArtistPanel.artist-layout')

@section('content')
<div class="container mt-4">

    <!-- Filter Options -->
    <div class="col-12 mb-4">
        <div class="card p-3">
            <h5>Events Album</h5>
            <div class="col-12 d-flex">
                <button class="btn btn-primary filter-btn me-2" data-filter="all">All</button>
                <button class="btn btn-outline-secondary filter-btn me-2" data-filter="image">Photos</button>
                <button class="btn btn-outline-secondary filter-btn" data-filter="video">Videos</button>
            </div>
        </div>
    </div>

    <!-- Albums Grid -->
    <div class="row mb-12 g-3" id="album-container">
        @foreach($albums as $album)
            @php
                $mediaUrl = filter_var($album->storage_path, FILTER_VALIDATE_URL) 
                              ? $album->storage_path 
                              : asset('storage/' . $album->storage_path);
            @endphp
            <div class="col-12 col-sm-6 col-md-3 album-item" data-type="{{ $album->media_type }}">
                <div class="card h-100">
                    @if($album->media_type === 'image')
                        <img class="card-img-top" src="{{ $mediaUrl }}" alt="{{ $album->title }}" style="height: 150px; object-fit: cover;">
                    @elseif($album->media_type === 'video')
                        @php
                            $isYoutube = false;
                            $youtubeId = null;
                            if (stripos($album->storage_path, 'youtube') !== false) {
                                parse_str(parse_url($album->storage_path, PHP_URL_QUERY), $queryVars);
                                $youtubeId = $queryVars['v'] ?? null;
                                $isYoutube = true;
                            } elseif (stripos($album->storage_path, 'youtu.be') !== false) {
                                $pathParts = explode('/', parse_url($album->storage_path, PHP_URL_PATH));
                                $youtubeId = end($pathParts);
                                $isYoutube = true;
                            }
                        @endphp
                        @if($isYoutube && $youtubeId)
                        <div id="youtube-container-{{ $youtubeId }}" style="position: relative; width: 100%; height: 150px;">
                            <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg"
                                alt="YouTube Thumbnail"
                                style="width: 100%; height: 150px; object-fit: cover; cursor: pointer;"
                                onclick="loadYouTubeVideo('{{ $youtubeId }}')">
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                                        background: rgba(0, 0, 0, 0.6); padding: 10px; border-radius: 50%;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/75/YouTube_social_white_squircle_%282017%29.svg" 
                                    width="50" height="50" alt="Play">
                            </div>
                        </div>
                        @else
                        <video class="card-img-top" controls style="height: 150px; object-fit: cover;">
                            <source src="{{ $mediaUrl }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        @endif
                    @endif
                    <div class="card-body p-2">
                        <h6 class="card-title mb-1">{{ $album->title }}</h6>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($album->description, 50) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- JavaScript for Filtering -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const albumItems = document.querySelectorAll('.album-item');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('btn-primary'));
                filterButtons.forEach(btn => btn.classList.add('btn-outline-secondary'));
                this.classList.add('btn-primary');
                this.classList.remove('btn-outline-secondary');

                albumItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-type') === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });

 
    function loadYouTubeVideo(videoId) {
        let container = document.getElementById("youtube-container-" + videoId);
        container.innerHTML = `<iframe width="100%" height="150" 
                                src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1&showinfo=0&controls=1"
                                frameborder="0" allowfullscreen></iframe>`;
    }
 
</script>
@endsection
