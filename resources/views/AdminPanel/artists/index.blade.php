@extends('AdminPanel.admin-layout')

@section('content')
<div class="card mt-4">
    <h5 class="card-header">Artists List</h5>
    <table class="table table-bordered table-striped mb-0">
        <thead>
            <tr>
                <th>id</th>
                <th>stage_name</th>
                <th>State Image</th>
                <th>is_premium</th>
                <th>Experience</th>
                <th>Location</th>
                <th>Booking rate</th>
                <th>is_verified</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody style="white-space: nowrap;">
            @foreach($artists as $artist)
            <tr>
                <td>{{ $artist->id }}</td>
                <td>{{ $artist->stage_name }}</td>
                <td>
                    @if($artist->profile_photo)
                    <img src="{{ asset('storage/' . $artist->profile_photo) }}" alt="Profile Image" style="max-width: 35px; border-radius: 5px;">
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($artist->is_premium  == 1)
                    yes 
                    @else
                    no
                    @endif
                <td>{{ $artist->experience_years }}</td>
                <td>{{ $artist->location }}</td>
                <td>{{ $artist->booking_rate }}</td>
                <td>
                    @if($artist->is_verified  == 1)
                    yes 
                    @else
                    no
                    @endif
                </td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti tabler-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('artist.profile', $artist->id) }}" class="dropdown-item">
                                <i class="ti tabler-user me-1"></i> Profile
                            </a>
                        </div>                        
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
 

@endsection


