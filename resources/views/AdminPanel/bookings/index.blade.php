@extends('AdminPanel.admin-layout')

@section('content')
<div class="container mt-4">
    <h3>All Bookings</h3>
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
      </div>
    @endif
    <div class="card mt-3">
        <div class="card-header">
            Booking List
        </div>
        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Details</th>
                        <th>Artist Details</th>
                        <th>Event Date</th>
                        <th>Event Type</th>
                        <th>Venue</th>
                        <th>Budget</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody style="white-space: nowrap;">
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>
                            @if($booking->user)
                                {{ $booking->user->name }}<br>
                                {{ $booking->user->email }}
                            @else
                                Guest
                            @endif
                        </td>
                        <td>
                            @if($booking->artist)
                                {{ $booking->artist->name }}<br>
                                {{ $booking->artist->contact ?? '' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('d-m-Y H:i') }}</td>
                        <td>{{ $booking->event_type }}</td>
                        <td>{{ $booking->venue }}</td>
                        <td>{{ $booking->budget }}</td>
                        <td>{{ $booking->full_name }}</td>
                        <td>{{ $booking->email }}</td>
                        <td>{{ $booking->phone }}</td>
                        <td>{{ ucfirst($booking->booking_status) }}</td>
                        <td>{{ $booking->created_at ? $booking->created_at->format('d-m-Y') : '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
