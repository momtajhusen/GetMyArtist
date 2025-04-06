@extends('AdminPanel.admin-layout')

@section('styles')
  <!-- Form Validation CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}" />
@endsection

@section('scripts')
  <script src="{{ asset('assets/js/form-validation.js') }}"></script>
@endsection

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

    <!-- Form for Adding New Event -->
    <div class="card">
        <h5 class="card-header">Add New Event</h5>
        <div class="card-body">
            <form class="needs-validation" novalidate action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Event Title -->
                    <div class="mb-3 col-md-4">
                        <label for="title" class="form-label">Event Title</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Event Title" required>
                        <div class="invalid-feedback">Please enter the event title.</div>
                    </div>
                    <!-- Event Description -->
                    <div class="mb-3 col-md-4">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" id="description" placeholder="Description">
                        <div class="invalid-feedback">Please enter a description (if any).</div>
                    </div>
                    <!-- Event Image -->
                    <div class="mb-3 col-md-4">
                        <label for="image" class="form-label">Event Image</label>
                        <input type="file" name="image" class="form-control" id="image">
                        <div class="invalid-feedback">Please select a valid image.</div>
                    </div>
                    <!-- Event Status -->
                    <div class="mb-3 col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-control" id="status" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>
                    <!-- Submit Button -->
                    <div class="mb-3 col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Events List -->
    <div class="card mt-4">
        <h5 class="card-header">Events List</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event Title</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>{{ $event->title }}</td>
                        <td>
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image" style="max-width: 35px; border-radius: 5px;">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ Str::limit($event->description, 50) }}</td>

                        <td>{{ ucfirst($event->status) }}</td>
                        <td>{{ $event->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ti tabler-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <!-- Trigger Edit Modal -->
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $event->id }}">
                                        <i class="ti tabler-pencil me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="ti tabler-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal for Event -->
                    <div class="modal fade" id="editModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Edit Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form class="needs-validation" novalidate action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              @method('PUT')
                              <div class="modal-body">
                                  <div class="mb-3">
                                      <label for="title_{{ $event->id }}" class="form-label">Event Title</label>
                                      <input type="text" name="title" class="form-control" id="title_{{ $event->id }}" value="{{ $event->title }}" required>
                                      <div class="invalid-feedback">Please enter the event title.</div>
                                  </div>
                                  <div class="mb-3">
                                      <label for="description_{{ $event->id }}" class="form-label">Description</label>
                                      <input type="text" name="description" class="form-control" id="description_{{ $event->id }}" value="{{ $event->description }}">
                                      <div class="invalid-feedback">Please enter a description (if any).</div>
                                  </div>
                                  <div class="mb-3">
                                      <label for="image_{{ $event->id }}" class="form-label">Event Image</label>
                                      <input type="file" name="image" class="form-control" id="image_{{ $event->id }}">
                                      <div class="invalid-feedback">Please select a valid image.</div>
                                      @if($event->image)
                                          <small>Current Image:</small>
                                          <div>
                                              <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image" style="max-width: 35px; border-radius: 5px;">
                                          </div>
                                      @endif
                                  </div>
                                  <div class="mb-3">
                                      <label for="status_{{ $event->id }}" class="form-label">Status</label>
                                      <select name="status" class="form-control" id="status_{{ $event->id }}" required>
                                          <option value="active" {{ $event->status == 'active' ? 'selected' : '' }}>Active</option>
                                          <option value="inactive" {{ $event->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                      </select>
                                      <div class="invalid-feedback">Please select a status.</div>
                                  </div>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-success">Update Event</button>
                              </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <!-- End Edit Modal -->
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Auto Remove Flash Message After 3 Seconds -->
<script>
    setTimeout(function () {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function (alert) {
            alert.style.transition = "opacity 0.5s";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000);
</script>
@endsection
