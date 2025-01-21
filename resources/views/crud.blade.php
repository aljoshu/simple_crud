@extends('components.layout')

@section('title', 'Products')

@section('content')
<div id="layoutSidenav_content">
    <main>
        <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="filter"></i></div>
                                Products
                            </h1>
                            <div class="page-header-subtitle">Here, you can create, view, update, and delete products as you see fit.</div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main page content-->
        <div class="container-xl px-4 mt-n10">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <!-- Add Product Button -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus me-2"></i>Add Product
                    </button>

                    <!-- Import JSON Button -->
                    <form action="{{ route('crud.import-products') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            Import Products
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <!-- Session Feedback -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Products Table -->
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id_produk }}</td>
                                <td>{{ $product->nama_produk }}</td>
                                <td>{{ $product->harga }}</td>
                                <td>{{ $product->category->nama_kategori }}</td>
                                <td>{{ $product->status->nama_status }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <!-- Edit Button -->
                                        <li class="list-inline-item">
                                            <form method="GET" action="{{ route('crud.update', ['id_produk' => $product->id_produk]) }}">
                                                <button
                                                    type="button" 
                                                    class="btn btn-datatable btn-icon btn-transparent-dark me-2 edit-product-button"
                                                    data-id="{{ $product->id_produk }}" 
                                                    data-name="{{ $product->nama_produk }}" 
                                                    data-price="{{ $product->harga }}" 
                                                    data-category="{{ $product->kategori_id }}" 
                                                    data-status="{{ $product->status_id }}"
                                                    data-bs-toggle="modal" 
                                                    data-toggle="tooltip" 
                                                    data-bs-target="#editProductModal" 
                                                    data-placement="top" 
                                                    title="Update"
                                                >
                                                    <i data-feather="edit"></i>
                                                </button>
                                            </form>
                                        </li>

                                        <!-- Delete Button -->
                                        <li class="list-inline-item">
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmationModal"
                                                data-form-id="delete-form-{{ $product->id_produk }}"
                                                data-id="{{ $product->id_produk }}">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                            <form id="delete-form-{{ $product->id_produk }}" method="POST" action="{{ route('crud.delete', ['id_produk' => $product->id_produk]) }}">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('crud.create') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="add_name" name="nama_produk" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="add_price" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_category" class="form-label">Category</label>
                            <select class="form-select" id="add_category" name="kategori_id" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id_kategori }}">{{ $category->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="add_status" class="form-label">Status</label>
                            <select class="form-select" id="add_status" name="status_id" required>
                                <option value="" disabled selected>Select Status</option>
                                @foreach($status as $stat)
                                <option value="{{ $stat->id_status }}">{{ $stat->nama_status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm" method="POST" action="{{ route('crud.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="edit_name" name="nama_produk" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="edit_price" name="harga" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_category" class="form-label">Category</label>
                                <select class="form-select" id="edit_category" name="kategori_id" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id_kategori }}">{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status_id" required>
                                    <option value="" disabled selected>Select Status</option>
                                    @foreach($status as $stat)
                                    <option value="{{ $stat->id_status }}">{{ $stat->nama_status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" id="edit_product_id" name="edit_product_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this entry? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-admin mt-auto footer-light">
        <div class="container-xl px-4">
            <div class="row">
                <div class="col-md-6 small">Copyright © Alvin Joshua 2025</div>
                <div class="col-md-6 text-md-end small">
                    <a href="#">Privacy Policy</a>
                    ·
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection

@section('scripts')
<script
    src="https://code.jquery.com/jquery-3.7.1.js"
    integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous">
</script>
<script>
    $(document).ready(function() {
        $('.edit-product-button').click(function () {
            var productId = $(this).data('id');
            var productName = $(this).data('name');
            var productPrice = $(this).data('price');
            var productCategory = $(this).data('category');
            var productStatus = $(this).data('status');

            // Set modal input values
            $('#edit_product_id').val(productId);
            $('#edit_name').val(productName);
            $('#edit_price').val(productPrice);
            $('#edit_category').val(productCategory);
            $('#edit_status').val(productStatus);
        });

        // Delete
        $('#deleteConfirmationModal').on('show.bs.modal', function(e) {
            var formId = $(e.relatedTarget).data('form-id');
            $('#confirmDelete').data('form-id', formId);
        });

        $('#confirmDelete').click(function() {
            var formId = $(this).data('form-id');
            $('#' + formId).submit();
        });
    });
</script>
@endsection