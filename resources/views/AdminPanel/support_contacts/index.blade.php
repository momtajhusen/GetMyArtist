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

  <!-- Form for Adding New Support Contact -->
  <div class="card">
      <h5 class="card-header">Add New Support Contact</h5>
      <div class="card-body">
          <form class="needs-validation" novalidate action="{{ route('support_contacts.store') }}" method="POST">
              @csrf
              <div class="row">
                  <!-- Hidden Admin ID (Logged in admin) -->
                  <input type="hidden" name="admin_id" value="{{ auth()->user()->id }}">
                  
                  <!-- Contact Type (Select) -->
                  <div class="mb-3 col-md-4">
                      <label for="type" class="form-label">Contact Type</label>
                      <select name="type" id="type" class="form-control" required>
                          <option value="">Select Contact Type</option>
                          <option value="email">Email</option>
                          <option value="phone">Phone</option>
                          <option value="whatsapp">WhatsApp</option>
                          <option value="other">Other</option>
                      </select>
                      <div class="invalid-feedback">Please select the contact type.</div>
                  </div>
                  
                  <!-- Contact Value -->
                  <div class="mb-3 col-md-4">
                      <label for="value" class="form-label">Contact Value</label>
                      <input type="text" name="value" class="form-control" id="value" placeholder="Enter contact detail" required>
                      <div class="invalid-feedback">Please enter the contact value.</div>
                  </div>
                  
                  <!-- Submit Button -->
                  <div class="mb-3 col-md-2 d-flex align-items-end">
                      <button type="submit" class="btn btn-primary w-100">Submit</button>
                  </div>
              </div>
          </form>
      </div>
  </div>

  <!-- Support Contacts List -->
  <div class="card mt-4">
      <h5 class="card-header">Support Contacts List</h5>
      <div class="table-responsive text-nowrap">
          <table class="table">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Contact Type</th>
                      <th>Contact Value</th>
                      <th>Admin</th>
                      <th>Created At</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($contacts as $contact)
                  <tr>
                      <td>{{ $contact->id }}</td>
                      <td>{{ ucfirst($contact->type) }}</td>
                      <td>{{ $contact->value }}</td>
                      <td>{{ $contact->admin->name ?? 'N/A' }}</td>
                      <td>{{ $contact->created_at->format('d-m-Y H:i') }}</td>
                      <td>
                          <div class="dropdown">
                              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                  <i class="ti ti-dots-vertical"></i>
                              </button>
                              <div class="dropdown-menu">
                                  <!-- Trigger Edit Modal -->
                                  <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $contact->id }}">
                                      <i class="ti ti-pencil me-1"></i> Edit
                                  </button>
                                  <form action="{{ route('support_contacts.destroy', $contact->id) }}" method="POST">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this contact?');">
                                          <i class="ti ti-trash me-1"></i> Delete
                                      </button>
                                  </form>
                              </div>
                          </div>
                      </td>
                  </tr>

                  <!-- Edit Modal for Support Contact -->
                  <div class="modal fade" id="editModal{{ $contact->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Edit Support Contact</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="needs-validation" novalidate action="{{ route('support_contacts.update', $contact->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <!-- Hidden Admin ID -->
                                <input type="hidden" name="admin_id" value="{{ auth()->user()->id }}">
                                
                                <!-- Contact Type (Select) -->
                                <div class="mb-3">
                                    <label for="type_{{ $contact->id }}" class="form-label">Contact Type</label>
                                    <select name="type" id="type_{{ $contact->id }}" class="form-control" required>
                                        <option value="">Select Contact Type</option>
                                        <option value="email" {{ $contact->type == 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="phone" {{ $contact->type == 'phone' ? 'selected' : '' }}>Phone</option>
                                        <option value="whatsapp" {{ $contact->type == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                        <option value="other" {{ $contact->type == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div class="invalid-feedback">Please select the contact type.</div>
                                </div>
                                
                                <!-- Contact Value -->
                                <div class="mb-3">
                                    <label for="value_{{ $contact->id }}" class="form-label">Contact Value</label>
                                    <input type="text" name="value" class="form-control" id="value_{{ $contact->id }}" value="{{ $contact->value }}" required>
                                    <div class="invalid-feedback">Please enter the contact value.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Update Contact</button>
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
