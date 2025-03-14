@extends('AdminPanel.admin-layout')

@section('scripts')
  <script src="{{ asset('assets/js/form-validation.js') }}"></script>
@endsection

@section('styles')
  <!-- Form Validation CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}" />
@endsection

@section('content')

<div class="container mt-4">
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

    <!-- Form for Adding New Category -->
    <div class="card">
        <h5 class="card-header">Add Celebrities Category</h5>
        <div class="card-body">
            <!-- Add enctype for file uploads -->
            <form  class="needs-validation" novalidate action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Category Name -->
                    <div class="mb-3 col-md-4">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Category name" required /> 
                        <div class="invalid-feedback"> Please enter Category Name. </div>
                    </div>

                    <!-- Parent Category -->
                    <div class="mb-3 col-md-4">
                        <label for="parent_id" class="form-label">Parent Category (Optional)</label>
                        <select name="parent_id" class="form-control">
                            <option value="">None (Main Category)</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @foreach($parent->subcategories as $sub)
                                    <option value="{{ $sub->id }}">— {{ $sub->name }}</option>
                                    @foreach($sub->subcategories as $subsub)
                                        <option value="{{ $subsub->id }}">—— {{ $subsub->name }}</option>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3 col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3 col-md-4">
                        <label for="image" class="form-label">Image (Optional)</label>
                        <input type="file" name="image" class="form-control" id="image" accept="image/*">
                    </div>

                    
                    <!-- Description -->
                    <div class="mb-3 col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" id="description" placeholder="Description..." />
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-3 col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories List -->
    <div class="card mt-4">
        <h5 class="card-header">Celebrities Categories List</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $index => $category)
                    <tr>
                        <td>{{ $index + 1 }}</td> {{-- Index number (starting from 1) --}}
                        <td>{{ $category->name }}</td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}" style="max-width: 35px; border-radius: 5px;">
                            @endif
                        </td>
                        <td>{{ ucfirst($category->status) }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                        <i class="ti ti-pencil me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="ti ti-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                
                    <!-- Include Edit Modal for Each Category -->
                    @include('AdminPanel.Categories.edit_modal', ['category' => $category])
                
                    <!-- Recursive Include for Subcategories -->
                    @include('AdminPanel.Categories.categories_recursive', ['category' => $category, 'level' => 1])
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
