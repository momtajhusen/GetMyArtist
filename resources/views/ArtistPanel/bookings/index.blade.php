@extends('AdminPanel.admin-layout')

@section('content')
<div class="container mt-4">
    <!-- Flash Messages -->
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
        <h5 class="card-header">Your Bookings</h5>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Details</th>
                        <th>Event Date</th>
                        <th>Event Type</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>
                            {{ $booking->customer->name ?? 'N/A' }}<br>
                            {{ $booking->customer->email ?? '' }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('d-m-Y H:i') }}</td>
                        <td>{{ $booking->event_type }}</td>
                        <td>{{ $booking->details }}</td>
                        <td>{{ ucfirst($booking->booking_status) }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('bookings.show', $booking->id) }}">
                                            View
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('bookings.edit', $booking->id) }}">
                                            Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger" type="submit" onclick="return confirm('Are you sure you want to delete this booking?');">
                                                Delete
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('bookings.updateStatus', $booking->id) }}">
                                            Update Status
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
