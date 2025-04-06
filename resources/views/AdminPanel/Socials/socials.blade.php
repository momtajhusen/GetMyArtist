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

    <!-- Form for Adding New Social -->
    <div class="card">
        <h5 class="card-header">Add New Social</h5>
        <div class="card-body">
            <form class="needs-validation" novalidate action="{{ route('socials.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Icon -->
                    <div class="mb-3 col-md-4">
                        <label for="icon" class="form-label">Icon</label>
                        <input type="text" name="icon" class="form-control" id="icon" placeholder="Enter Icon" required>
                        <div class="invalid-feedback">Please enter an icon.</div>
                    </div>
                    <!-- URL -->
                    <div class="mb-3 col-md-4">
                        <label for="url" class="form-label">URL</label>
                        <input type="url" name="url" class="form-control" id="url" placeholder="https://example.com" required>
                        <div class="invalid-feedback">Please enter a valid URL.</div>
                    </div>
                    <!-- Contact -->
                    <div class="mb-3 col-md-4">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" name="contact" class="form-control" id="contact" placeholder="Enter Contact" required>
                        <div class="invalid-feedback">Please enter contact details.</div>
                    </div>
                    <!-- Submit Button -->
                    <div class="mb-3 col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Socials List -->
    <div class="card mt-4">
        <h5 class="card-header">Socials List</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Icon</th>
                        <th>URL</th>
                        <th>Contact</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($socials as $social)
                    <tr>
                        <td>{{ $social->id }}</td>
                        <td>{{ $social->icon }}</td>
                        <td>{{ $social->url }}</td>
                        <td>{{ $social->contact }}</td>
                        <td>{{ $social->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ti tabler-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <!-- Trigger Edit Modal -->
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $social->id }}">
                                        <i class="ti tabler-pencil me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('socials.destroy', $social->id) }}" method="POST">
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

                    <!-- Edit Modal for Social -->
                    <div class="modal fade" id="editModal{{ $social->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Edit Social</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form class="needs-validation" novalidate action="{{ route('socials.update', $social->id) }}" method="POST">
                              @csrf
                              @method('PUT')
                              <div class="modal-body">
                                  <div class="mb-3">
                                      <label for="icon_{{ $social->id }}" class="form-label">Icon</label>
                                      <input type="text" name="icon" class="form-control" id="icon_{{ $social->id }}" value="{{ $social->icon }}" required>
                                      <div class="invalid-feedback">Please enter an icon.</div>
                                  </div>
                                  <div class="mb-3">
                                      <label for="url_{{ $social->id }}" class="form-label">URL</label>
                                      <input type="url" name="url" class="form-control" id="url_{{ $social->id }}" value="{{ $social->url }}" required>
                                      <div class="invalid-feedback">Please enter a valid URL.</div>
                                  </div>
                                  <div class="mb-3">
                                      <label for="contact_{{ $social->id }}" class="form-label">Contact</label>
                                      <input type="text" name="contact" class="form-control" id="contact_{{ $social->id }}" value="{{ $social->contact }}" required>
                                      <div class="invalid-feedback">Please enter contact details.</div>
                                  </div>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-success">Update Social</button>
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
