@extends('AdminPanel.admin-layout')

@section('scripts')
  <script src="{{ asset('assets/js/form-validation.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/tables-datatables-advanced.js') }}"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <!-- Flat Picker -->
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection


@section('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}">
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


                    {{-- Parent Category (Optional) --}}
                    @php
                    $categoryOptions = [];
                    foreach($categories as $parent) {
                        $categoryOptions[$parent->id] = $parent->name;
                        foreach($parent->subcategories as $sub) {
                            $categoryOptions[$sub->id] = '— ' . $sub->name;
                            foreach($sub->subcategories as $subsub) {
                                $categoryOptions[$subsub->id] = '—— ' . $subsub->name;
                            }
                        }
                    }
                @endphp
                <div class="mb-3 col-md-4">
                    @include('components.icon-select', [
                        'name' => 'parent_id', 
                        'label' => 'Parent Category (Optional)', 
                        'icon' => 'bx bx-category', 
                        'options' => $categoryOptions
                    ])
                </div>

                <!-- Status -->
                <div class="mb-3 col-md-4">
                    @include('components.icon-select', [
                        'name' => 'status', 
                        'label' => 'Status', 
                        'icon' => 'bx bx-category', 
                        'options' => [
                                        'active' => 'Active',
                                        'inactive' => 'Inactive'
                        ]
                    ])
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
                        <td>
                            <span class="badge {{ $category->status === 'inactive' ? 'bg-label-danger' : 'bg-label-info' }}">
                                {{ ucfirst($category->status) }}
                              </span>
                        </td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ti tabler-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                        <i class="ti tabler-pencil me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
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
                
                    <!-- Include Edit Modal for Each Category -->
                    @include('AdminPanel.Categories.edit_modal', ['category' => $category])
                
                    <!-- Recursive Include for Subcategories -->
                    @include('AdminPanel.Categories.categories_recursive', ['category' => $category, 'level' => 1])
                @endforeach
                
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <!-- Categories List in Content Table -->
      <div class="card">
        <h5 class="card-header pb-0 text-md-start text-center">Celebrities Categories List</h5>
        <div class="table-responsive"> <!-- 👈 Bootstrap Scrollable Table -->
          <table class="datatables-ajax table table-bordered text-nowrap">
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
                    <img src="{{ asset('storage/'.$category->image) }}" 
                        alt="{{ $category->name }}" 
                        style="max-width: 35px; border-radius: 5px;">
                  @endif
                </td>
                <td>
                  <span class="badge {{ $category->status === 'inactive' ? 'bg-label-danger' : 'bg-label-info' }}">
                    {{ ucfirst($category->status) }}
                  </span>
                </td>
                <td>{{ $category->description }}</td>
                <td>
                  <div class="dropdown">
                    <button type="button" 
                            class="btn p-0 dropdown-toggle hide-arrow" 
                            data-bs-toggle="dropdown">
                      <i class="ti tabler-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                      <button class="dropdown-item" 
                              data-bs-toggle="modal" 
                              data-bs-target="#editModal{{ $category->id }}">
                        <i class="ti tabler-pencil me-1"></i> Edit
                      </button>
                      <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
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

              <!-- Include Edit Modal for Each Category -->
              @include('AdminPanel.Categories.edit_modal', ['category' => $category])

              <!-- Recursive Include for Subcategories -->
              @include('AdminPanel.Categories.categories_recursive', ['category' => $category, 'level' => 1])
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!--/ Categories List in Content Table -->
    </div>
    <!-- / Content -->

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
