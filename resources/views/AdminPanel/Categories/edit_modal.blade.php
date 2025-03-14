<div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Add enctype for file upload -->
            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Category Name -->
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required />
                    </div>

                    <!-- Parent Category -->
                    <div class="mb-3">
                        <label class="form-label">Parent Category (Optional)</label>
                        <select name="parent_id" class="form-control">
                            <option value="">None (Main Category)</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}" 
                                    {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                                @foreach($parent->subcategories as $sub)
                                    <option value="{{ $sub->id }}" 
                                        {{ $category->parent_id == $sub->id ? 'selected' : '' }}>
                                        — {{ $sub->name }}
                                    </option>
                                    @foreach($sub->subcategories as $subsub)
                                        <option value="{{ $subsub->id }}" 
                                            {{ $category->parent_id == $subsub->id ? 'selected' : '' }}>
                                            —— {{ $subsub->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" value="{{ $category->description }}" />
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label class="form-label">Image (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($category->image)
                            <small>Current Image: <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}" style="max-width:50px;"></small>
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
