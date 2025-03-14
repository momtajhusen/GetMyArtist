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

  <!-- Users List -->
  <div class="card mt-4">
    <h5 class="card-header">Users List</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="ti ti-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                  <!-- Trigger Edit Modal -->
                  <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                    <i class="menu-icon tf-icons ti ti-users"></i> Profile
                  </button>
                  {{-- <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this user?');">
                      <i class="ti ti-trash me-1"></i> Delete
                    </button>
                  </form> --}}
                </div>
              </div>
            </td>
          </tr>
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
