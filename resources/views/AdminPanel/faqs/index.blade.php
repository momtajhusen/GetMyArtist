@extends('AdminPanel.admin-layout')

@section('styles')
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

  <!-- Form for Adding New FAQ -->
  <div class="card">
      <h5 class="card-header">Add New FAQ</h5>
      <div class="card-body">
          <form class="needs-validation" novalidate action="{{ route('faqs.store') }}" method="POST">
              @csrf
              <div class="row">
                  <!-- FAQ Type -->
                  <div class="mb-3 col-md-4">
                      <label for="type" class="form-label">FAQ Type (Module)</label>
                      <input type="text" name="type" class="form-control" id="type" placeholder="Enter type" required>
                      <div class="invalid-feedback">Please enter the FAQ type.</div>
                  </div>
                  <!-- Question -->
                  <div class="mb-3 col-md-4">
                      <label for="question" class="form-label">Question</label>
                      <input type="text" name="question" class="form-control" id="question" placeholder="Enter question" required>
                      <div class="invalid-feedback">Please enter the question.</div>
                  </div>
                  <!-- Audience -->
                  <div class="mb-3 col-md-4">
                      <label for="audience" class="form-label">Audience</label>
                      <select name="audience" id="audience" class="form-control" required>
                          <option value="">Select Audience</option>
                          <option value="user">User</option>
                          <option value="artist">Artist</option>
                          <option value="both" selected>Both</option>
                      </select>
                      <div class="invalid-feedback">Please select the intended audience.</div>
                  </div>
                  <!-- Answer -->
                  <div class="mb-3 col-md-6">
                      <label for="answer" class="form-label">Answer</label>
                      <textarea name="answer" class="form-control" id="answer" placeholder="Enter answer" required></textarea>
                      <div class="invalid-feedback">Please enter the answer.</div>
                  </div>
                  <!-- Status -->
                  <div class="mb-3 col-md-3">
                      <label for="status" class="form-label">Status</label>
                      <select name="status" class="form-control" id="status" required>
                          <option value="published" selected>Published</option>
                          <option value="draft">Draft</option>
                      </select>
                      <div class="invalid-feedback">Please select the status.</div>
                  </div>
                  <!-- Submit Button -->
                  <div class="mb-3 col-md-2 d-flex align-items-end">
                      <button type="submit" class="btn btn-primary w-100">Submit</button>
                  </div>
              </div>
          </form>
      </div>
  </div>

 
    <!-- FAQs List -->
    <div class="card mt-4">
        <h5 class="card-header">FAQs List</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Question</th>
                        <th>Audience</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $previousType = null; @endphp
                    @foreach($faqs as $faq)
                    <tr>
                        <td>{{ $faq->id }}</td>

                        <td>
                          <strong>{{ ucfirst($faq->type) }}</strong>
                        </td>

                        <td>{{ $faq->question }}</td>
                        <td>{{ ucfirst($faq->audience) }}</td>
                        <td>{{ ucfirst($faq->status) }}</td>
                        <td>{{ $faq->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $faq->id }}">
                                        <i class="ti ti-pencil me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('faqs.destroy', $faq->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this FAQ?');">
                                            <i class="ti ti-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $faq->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit FAQ</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('faqs.update', $faq->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <label class="form-label">FAQ Type</label>
                                        <input type="text" name="type" class="form-control" value="{{ $faq->type }}" required>

                                        <label class="form-label mt-2">Question</label>
                                        <input type="text" name="question" class="form-control" value="{{ $faq->question }}" required>

                                        <label class="form-label mt-2">Answer</label>
                                        <textarea name="answer" class="form-control" required>{{ $faq->answer }}</textarea>

                                        <label class="form-label mt-2">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="published" {{ $faq->status == 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="draft" {{ $faq->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success">Update</button>
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

<script>
    setTimeout(function () {
        document.querySelectorAll('.alert').forEach(function(alert) {
            alert.style.transition = "opacity 0.5s";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000);
</script>
@endsection
