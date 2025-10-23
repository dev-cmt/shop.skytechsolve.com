<x-backend-layout title="Attributes Management">

<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <h1 class="page-title fw-semibold fs-18 mb-0">Attributes Management</h1>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attributes</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between d-flex align-items-center">
                <div class="card-title">Attributes List</div>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAttributeModal">
                    <i class="ri-add-line me-1 fw-semibold align-middle"></i> Add Attribute
                </button>
            </div>
            <div class="card-body">

                <div id="alertMsg"></div>

                <div class="table-responsive">
                    <table class="table text-nowrap table-hover border table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Display Type</th>
                                <th>Has Image</th>
                                <th>Items</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="attributesTable">
                            @forelse($attributes as $attr)
                            <tr id="attr-{{ $attr->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $attr->name }}</td>
                                <td><span class="badge bg-info text-dark">{{ ucfirst($attr->display_type) }}</span></td>
                                <td>
                                    @if($attr->has_image)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <ul id="items-{{ $attr->id }}" class="list-unstyled mb-0">
                                        @foreach($attr->items as $item)
                                        <li id="item-{{ $item->id }}" class="d-flex justify-content-between align-items-center mb-1">
                                            {{ $item->name }}
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-icon btn-wave waves-effect waves-light btn-sm btn-primary-light editItemBtn"
                                                    data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}"
                                                    data-value="{{ $item->value }}"
                                                    data-sort="{{ $item->sort_order }}"
                                                    data-attribute="{{ $attr->id }}">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <button class="btn btn-icon btn-wave waves-effect waves-light btn-sm btn-danger-light deleteItem" data-id="{{ $item->id }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <button class="btn btn-sm btn-outline-success mt-1 addItemBtn" data-attribute="{{ $attr->id }}">
                                        <i class="ri-add-line"></i> Add Item
                                    </button>
                                </td>
                                <td class="text-center">
                                    <div class="btn-list">
                                        <button type="button" class="btn btn-sm btn-warning-light editAttrBtn"
                                            data-id="{{ $attr->id }}"
                                            data-name="{{ $attr->name }}"
                                            data-display="{{ $attr->display_type }}"
                                            data-image="{{ $attr->has_image }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#createAttributeModal">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger-light deleteAttrBtn" data-id="{{ $attr->id }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No attributes found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Attribute Modal -->
<div class="modal fade" id="createAttributeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="attributeForm" method="POST">
            @csrf
            <input type="hidden" name="id" id="attr_id">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">Add / Edit Attribute</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" id="attr_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Display Type</label>
                        <select name="display_type" id="attr_display" class="form-select" required>
                            <option value="text">Text</option>
                            <option value="color">Color</option>
                            <option value="image">Image</option>
                            <option value="dropdown">Dropdown</option>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="attr_image" name="has_image" value="1">
                        <label class="form-check-label" for="attr_image">Has Image</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="saveAttrBtn" type="submit">Save Attribute</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add/Edit Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="itemForm" method="POST">
            @csrf
            <input type="hidden" name="id" id="item_id">
            <input type="hidden" name="attribute_id" id="item_attr_id">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h6 class="modal-title">Add / Edit Item</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" id="item_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Value</label>
                        <input type="text" name="value" id="item_value" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" id="item_sort" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success" type="submit">Save Item</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
$(document).ready(function() {

    // ===== Attribute Modal =====
    $('#createAttributeModal').on('show.bs.modal', function(event){
        let button = $(event.relatedTarget);
        let form = $(this).find('form')[0];
        form.reset();
        $('#attr_id').val('');
        $(this).find('form').attr('action', "{{ route('attributes.store') }}");

        if(button.hasClass('editAttrBtn')){
            $('#attr_id').val(button.data('id'));
            $('#attr_name').val(button.data('name'));
            $('#attr_display').val(button.data('display'));
            $('#attr_image').prop('checked', button.data('image') == 1);
            $(this).find('form').attr('action', "{{ route('attributes.update') }}");
        }
    });

    $('#attributeForm').on('submit', function(e){
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function(res){
            if(res.success) location.reload();
        });
    });

    // ===== Attribute Item Modal =====
    $(document).on('click', '.addItemBtn', function(){
        $('#itemForm')[0].reset();
        $('#item_id').val('');
        $('#item_attr_id').val($(this).data('attribute'));
        $('#addItemModal').modal('show');
        $('#itemForm').attr('action', "{{ route('attribute-items.store') }}");
    });

    $(document).on('click', '.editItemBtn', function(){
        $('#itemForm')[0].reset();
        let btn = $(this);
        $('#item_id').val(btn.data('id'));
        $('#item_name').val(btn.data('name'));
        $('#item_value').val(btn.data('value'));
        $('#item_sort').val(btn.data('sort'));
        $('#item_attr_id').val(btn.data('attribute'));
        $('#addItemModal').modal('show');
        $('#itemForm').attr('action', "{{ route('attribute-items.update') }}");
    });

    $('#itemForm').on('submit', function(e){
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function(res){
            if(res.success) location.reload();
        });
    });

    // ===== Delete Attribute =====
    $(document).on('click', '.deleteAttrBtn', function(){
        if(!confirm('Delete this attribute?')) return;
        let id = $(this).data('id');
        $.post("{{ route('attributes.destroy') }}", {id:id, _token:"{{ csrf_token() }}"}, function(res){
            if(res.success) $('#attr-'+id).remove();
        });
    });

    // ===== Delete Item =====
    $(document).on('click', '.deleteItem', function(){
        if(!confirm('Delete this item?')) return;
        let id = $(this).data('id');
        $.post("{{ route('attribute-items.destroy') }}", {id:id, _token:"{{ csrf_token() }}"}, function(res){
            if(res.success) $('#item-'+id).remove();
        });
    });

});
</script>
@endpush

</x-backend-layout>
