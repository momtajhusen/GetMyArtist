@foreach($category->subcategories as $index => $sub)
    <tr>
        <td>
            &nbsp;&nbsp;&nbsp;&nbsp;
            {{ $index + 1 }} {{-- Index number (starting from 1) --}}
        </td>
        <td>
            @for ($i = 0; $i < $level; $i++)
                &nbsp;&nbsp;&nbsp;&nbsp;
            @endfor
            {{ $sub->name }}
        </td>
        <td>
            @if($sub->image)
                &nbsp;&nbsp;&nbsp;&nbsp;
                <img src="{{ asset('storage/'.$sub->image) }}" alt="{{ $sub->name }}" style="max-width: 30px; border-radius: 5px;">
            @endif
        </td>
        <td> 
          <span class="badge {{ $sub->status === 'inactive' ? 'bg-label-danger' : 'bg-label-info' }}">
            {{ ucfirst($sub->status) }}
          </span>
        </td>
        <td>{{ $sub->description }}</td>
        <td>
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="ti tabler-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $sub->id }}">
                        <i class="ti tabler-pencil me-1"></i> Edit
                    </button>
                    <form action="{{ route('categories.destroy', $sub->id) }}" method="POST">
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

    <!-- Edit Modal for Subcategories -->
    @include('AdminPanel.Categories.edit_modal', ['category' => $sub])

    <!-- Recursive call for deeper subcategories -->
    @include('AdminPanel.Categories.categories_recursive', ['category' => $sub, 'level' => $level + 1])
@endforeach
