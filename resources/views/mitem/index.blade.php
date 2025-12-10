<!DOCTYPE html>
<html lang="en">

<head>
    <title>M_Item List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-group {
            margin-bottom: 1rem;
            position: relative;
        }

        .invalid-feedback {
            display: block;
            height: 18px;
            font-size: 0.85rem;
        }

        .form-control {
            min-height: 38px;
        }

        .btn-sm,
        .form-control-sm {
            height: 32px !important;
            font-size: 0.875rem;
        }

        input[type="file"]::file-selector-button {
            height: 30px;
            font-size: 0.8rem;
        }

        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Custom column widths */
        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 120px;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 300px;
            max-width: 300px;
        }

        .sort-arrow {
            font-size: 12px;
            color: #ccc;
            transition: all 0.2s ease;
            display: inline-block;
            line-height: 1;
        }

        .sort-btn.active .sort-arrow {
            color: #ffc107;
        }

        .sort-btn.asc .sort-arrow {
            transform: rotate(0deg);
        }

        .sort-btn.desc .sort-arrow {
            transform: rotate(180deg);
        }

        .sort-btn:hover .sort-arrow {
            color: #ffc107;
        }

        .btn-sm {
            font-family: var(--bs-body-font-family) !important;
            font-size: var(--bs-body-font-size) !important;
            font-weight: 600;
            height: 37px !important;
        }
    </style>
</head>

<body>
    @include('navbar')

    <div class="container mt-5">
        <h2 class="mb-4">M Item</h2>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
            <!-- Filter Form (Left Side) -->
            <form action="{{ route('mitems.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap" id="filterForm">
                <div class="d-flex align-items-center gap-2">
                    <label for="Item_Code" class="form-label mb-0 fw-semibold" style="white-space: nowrap;">商品番号:</label>
                    <input type="text" id="Item_Code" name="Item_Code" class="form-control form-control-sm" value="{{ request('Item_Code') }}" style="width:150px;">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <label for="ItemName" class="form-label mb-0 fw-semibold" style="white-space: nowrap;">商品名:</label>
                    <input type="text" id="ItemName" name="ItemName" class="form-control form-control-sm" value="{{ request('ItemName') }}" style="width:200px;">
                </div>

                <!-- Hidden inputs for sorting -->
                <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', '') }}">
                <input type="hidden" name="sort_order" id="sort_order" value="{{ request('sort_order', 'asc') }}">

                <button type="submit" class="btn btn-sm btn-outline-primary search">Search</button>
            </form>

            <!-- Right Side Buttons -->
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <!-- Export Excel Button -->
                <form action="{{ route('mitems.export.excel') }}" method="GET" class="d-inline" id="exportExcelForm">
                    <input type="hidden" name="Item_Code" value="{{ request('Item_Code') }}">
                    <input type="hidden" name="ItemName" value="{{ request('ItemName') }}">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </form>

                <!-- Export CSV Button -->
                <form action="{{ route('mitems.export.csv') }}" method="GET" class="d-inline" id="exportCsvForm">
                    <input type="hidden" name="Item_Code" value="{{ request('Item_Code') }}">
                    <input type="hidden" name="ItemName" value="{{ request('ItemName') }}">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                    <button type="submit" class="btn btn-sm btn-info">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                </form>

                <!-- Add New Button -->
                <a href="{{ route('mitems.create') }}" class="btn btn-sm btn-primary">+ Add New</a>
            </div>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>商品番号</th>
                    <th>商品名</th>
                    <th style="width: 120px;">JANCD</th>
                    <th>メーカー名</th>
                    <th style="width: 300px;">注記</th>
                    <th>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <span>定価</span>
                            <button class="btn btn-link p-0 sort-btn text-decoration-none" data-sort="ListPrice" style="color: #fff; border: none;">
                                <span class="sort-arrow">▲</span>
                            </button>
                        </div>
                    </th>
                    <th>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <span>原価</span>
                            <button class="btn btn-link p-0 sort-btn text-decoration-none" data-sort="SalePrice" style="color: #fff; border: none;">
                                <span class="sort-arrow">▲</span>
                            </button>
                        </div>
                    </th>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td class="text-start">
                        <a href="{{ route('mitems.edit', $item->ID) }}" class="text-primary text-decoration-none fw-bold">
                            {{ $item->Item_Code }}
                        </a>
                    </td>
                    <td class="text-start">{{ $item->ItemName }}</td>
                    <td class="text-start" style="width: 120px;">{{ $item->JanCD }}</td>
                    <td class="text-start">{{ $item->MakerName }}</td>
                    <td class="text-start" style="width: 300px; max-width: 300px;">{{ $item->Memo }}</td>
                    <td class="text-end">{{ number_format($item->ListPrice) }}</td>
                    <td class="text-end">{{ number_format($item->SalePrice) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end mt-3">
            {{ $items->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Excel Preview Modal -->
    <div class="modal fade" id="excelPreviewModal" tabindex="-1" aria-labelledby="excelPreviewLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="excelPreviewLabel">Excel Data Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div id="previewTableContainer" class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead id="previewTableHead" class="table-light"></thead>
                            <tbody id="previewTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- <form action="{{ route('mitems.import') }}" method="POST" enctype="multipart/form-data" id="confirmImportForm">
                        @csrf
                        <button type="submit" id="confirmBtn" class="btn btn-success">Confirm Import</button>
                    </form> -->

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD NEW ITEM MODAL -->
    <div class="modal fade @if ($errors->any()) show @endif" id="addItemModal" tabindex="-1" aria-labelledby="addItemLabel" aria-modal="true" role="dialog" style="@if ($errors->any()) display:block; @endif">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('mitems.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- First Row: Item Info + Note -->
                        <div class="row g-3">
                            <!-- Left: Main Info -->
                            <div class="col-md-7 d-flex">
                                <div class="card shadow-sm flex-fill">
                                    <div class="card-body">
                                        <!-- 商品番号 / JANCD / メーカー名 -->
                                        <div class="row g-3">
                                            <div class="col-md-4 form-group">
                                                <label class="form-label">商品番号</label>
                                                <input type="text" name="Item_Code" class="form-control @error('Item_Code') is-invalid @enderror" value="{{ old('Item_Code') }}" required>
                                                <div class="invalid-feedback">@error('Item_Code') {{ $message }} @enderror</div>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="form-label">JANCD</label>
                                                <input type="text" name="JanCD" class="form-control @error('JanCD') is-invalid @enderror" value="{{ old('JanCD') }}">
                                                <div class="invalid-feedback">@error('JanCD') {{ $message }} @enderror</div>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="form-label">メーカー名</label>
                                                <input type="text" name="MakerName" class="form-control @error('MakerName') is-invalid @enderror" value="{{ old('MakerName') }}">
                                                <div class="invalid-feedback">@error('MakerName') {{ $message }} @enderror</div>
                                            </div>
                                        </div>

                                        <!-- 商品名 -->
                                        <div class="row g-3 mt-3">
                                            <div class="col-12 form-group">
                                                <label class="form-label">商品名</label>
                                                <textarea name="ItemName" class="form-control @error('ItemName') is-invalid @enderror" rows="3" required>{{ old('ItemName') }}</textarea>
                                                <div class="invalid-feedback">@error('ItemName') {{ $message }} @enderror</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: 注記 -->
                            <div class="col-md-5 d-flex">
                                <div class="card shadow-sm flex-fill">
                                    <div class="card-body d-flex flex-column">
                                        <label class="form-label">注記</label>
                                        <textarea name="Note" class="form-control flex-grow-1 @error('Note') is-invalid @enderror" rows="10">{{ old('Note') }}</textarea>
                                        <div class="invalid-feedback">@error('Note') {{ $message }} @enderror</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SKU CARD -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="card-title fw-bold">SKU 登録</h6>
                                            <button type="button" class="btn btn-primary btn-sm" id="addSkuBtn">+ Add SKU</button>
                                        </div>

                                        <!-- SKU List Table -->
                                        <table class="table table-bordered" id="skuTable">
                                            <thead class="table-light">
                                                <tr class="text-center">
                                                    <th>Size Name</th>
                                                    <th>Color Name</th>
                                                    <th>Size Code</th>
                                                    <th>Color Code</th>
                                                    <th>JANCD</th>
                                                    <th>Quantity</th>
                                                    <th width="80">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Dynamic rows appear here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PRICE CARD -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label">定価 (List Price)</label>
                                            <input type="number" name="ListPrice" class="form-control @error('ListPrice') is-invalid @enderror" value="{{ old('ListPrice') }}" required>
                                            <div class="invalid-feedback">@error('ListPrice') {{ $message }} @enderror</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">原価 (Sale Price)</label>
                                            <input type="number" name="SalePrice" class="form-control @error('SalePrice') is-invalid @enderror" value="{{ old('SalePrice') }}" required>
                                            <div class="invalid-feedback">@error('SalePrice') {{ $message }} @enderror</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT ITEM MODAL -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="editItemLabel">Edit Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <!-- First Row: Item Info + Note -->
                        <div class="row g-3">
                            <!-- Left: Main Info -->
                            <div class="col-md-7 d-flex">
                                <div class="card shadow-sm flex-fill">
                                    <div class="card-body">
                                        <!-- 商品番号 / JANCD / メーカー名 -->
                                        <div class="row g-3">
                                            <div class="col-md-4 form-group">
                                                <label class="form-label">商品番号</label>
                                                <input type="text" name="Item_Code" id="edit_Item_Code" class="form-control @error('Item_Code') is-invalid @enderror" required>
                                                <div class="invalid-feedback">@error('Item_Code') {{ $message }} @enderror</div>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="form-label">JANCD</label>
                                                <input type="text" name="JanCD" id="edit_JanCD" class="form-control @error('JanCD') is-invalid @enderror">
                                                <div class="invalid-feedback">@error('JanCD') {{ $message }} @enderror</div>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="form-label">メーカー名</label>
                                                <input type="text" name="MakerName" id="edit_MakerName" class="form-control @error('MakerName') is-invalid @enderror">
                                                <div class="invalid-feedback">@error('MakerName') {{ $message }} @enderror</div>
                                            </div>
                                        </div>

                                        <!-- 商品名 -->
                                        <div class="row g-3 mt-3">
                                            <div class="col-12 form-group">
                                                <label class="form-label">商品名</label>
                                                <textarea name="ItemName" id="edit_ItemName" class="form-control @error('ItemName') is-invalid @enderror" rows="3" required></textarea>
                                                <div class="invalid-feedback">@error('ItemName') {{ $message }} @enderror</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: 注記 -->
                            <div class="col-md-5 d-flex">
                                <div class="card shadow-sm flex-fill">
                                    <div class="card-body d-flex flex-column">
                                        <label class="form-label">注記</label>
                                        <textarea name="Note" id="edit_Note" class="form-control flex-grow-1 @error('Note') is-invalid @enderror" rows="10"></textarea>
                                        <div class="invalid-feedback">@error('Note') {{ $message }} @enderror</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SKU CARD -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="card-title fw-bold">SKU 登録</h6>
                                            <button type="button" class="btn btn-primary btn-sm" id="editAddSkuBtn">+ Add SKU</button>
                                        </div>

                                        <!-- SKU List Table -->
                                        <table class="table table-bordered" id="editSkuTable">
                                            <thead class="table-light">
                                                <tr class="text-center">
                                                    <th>Size Name</th>
                                                    <th>Color Name</th>
                                                    <th>Size Code</th>
                                                    <th>Color Code</th>
                                                    <th>JANCD</th>
                                                    <th>Quantity</th>
                                                    <th width="80">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Dynamic rows will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PRICE CARD -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label">定価 (List Price)</label>
                                            <input type="number" name="ListPrice" id="edit_ListPrice" class="form-control @error('ListPrice') is-invalid @enderror" required>
                                            <div class="invalid-feedback">@error('ListPrice') {{ $message }} @enderror</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">原価 (Sale Price)</label>
                                            <input type="number" name="SalePrice" id="edit_SalePrice" class="form-control @error('SalePrice') is-invalid @enderror" required>
                                            <div class="invalid-feedback">@error('SalePrice') {{ $message }} @enderror</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Update Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let selectedFile = null;

        // Document ready function
        document.addEventListener('DOMContentLoaded', function() {
            // Export form loading states
            const excelForm = document.getElementById('exportExcelForm');
            if (excelForm) {
                excelForm.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[type="submit"]');
                    const originalText = button.innerHTML;

                    button.innerHTML = '⏳ Preparing Excel...';
                    button.disabled = true;

                    // Re-enable after 5 seconds in case of error
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 5000);
                });
            }

            const csvForm = document.getElementById('exportCsvForm');
            if (csvForm) {
                csvForm.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[type="submit"]');
                    const originalText = button.innerHTML;

                    button.innerHTML = '⏳ Preparing CSV...';
                    button.disabled = true;

                    // Re-enable after 5 seconds in case of error
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 5000);
                });
            }

            // Sync filter form with export forms
            const filterForm = document.getElementById('filterForm');
            const exportForms = [excelForm, csvForm];

            if (filterForm) {
                exportForms.forEach(exportForm => {
                    if (exportForm) {
                        // Update export form when filter form changes
                        filterForm.addEventListener('input', function() {
                            const formData = new FormData(filterForm);
                            exportForm.querySelector('input[name="Item_Code"]').value = formData.get('Item_Code') || '';
                            exportForm.querySelector('input[name="ItemName"]').value = formData.get('ItemName') || '';
                            exportForm.querySelector('input[name="sort_by"]').value = formData.get('sort_by') || '';
                            exportForm.querySelector('input[name="sort_order"]').value = formData.get('sort_order') || '';
                        });
                    }
                });
            }

            // Sorting functionality
            const sortButtons = document.querySelectorAll('.sort-btn');

            // Get current sort parameters from URL
            const urlParams = new URLSearchParams(window.location.search);
            const currentSort = urlParams.get('sort_by');
            const currentOrder = urlParams.get('sort_order');

            // Update button appearance based on current sort
            sortButtons.forEach(button => {
                if (button.dataset.sort === currentSort) {
                    button.classList.add('active');
                    if (currentOrder === 'desc') {
                        button.classList.add('desc');
                        button.classList.remove('asc');
                    } else {
                        button.classList.add('asc');
                        button.classList.remove('desc');
                    }
                }
            });

            sortButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const sortBy = this.dataset.sort;
                    let sortOrder = 'asc';

                    // If already sorting by this column, toggle order
                    if (sortBy === document.getElementById('sort_by').value) {
                        sortOrder = document.getElementById('sort_order').value === 'asc' ? 'desc' : 'asc';
                    }

                    // Update hidden inputs
                    document.getElementById('sort_by').value = sortBy;
                    document.getElementById('sort_order').value = sortOrder;

                    // Submit the form
                    document.getElementById('filterForm').submit();
                });
            });

            // Edit Button Click Handler
            document.querySelectorAll('.editBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    document.getElementById('editForm').action = `/mitems/${id}`;

                    // Bind main item data
                    document.getElementById('edit_Item_Code').value = this.dataset.item_code;
                    document.getElementById('edit_ItemName').value = this.dataset.itemname;
                    document.getElementById('edit_JanCD').value = this.dataset.jancd;
                    document.getElementById('edit_MakerName').value = this.dataset.makername;
                    document.getElementById('edit_ListPrice').value = this.dataset.listprice;
                    document.getElementById('edit_SalePrice').value = this.dataset.saleprice;
                    document.getElementById('edit_Note').value = this.dataset.note || '';

                    // Clear existing SKU rows
                    document.querySelector('#editSkuTable tbody').innerHTML = '';

                    // Fetch and bind SKU data via AJAX
                    fetch(`/mitems/${id}/skus`)
                        .then(response => response.json())
                        .then(skus => {
                            skus.forEach(sku => {
                                addEditSkuRow(sku);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching SKU data:', error);
                        });
                });
            });

            // Add SKU Row for Edit Form
            document.getElementById('editAddSkuBtn').addEventListener('click', function() {
                addEditSkuRow();
            });

            // Original Add New Form SKU functionality
            document.getElementById('addSkuBtn').addEventListener('click', function() {
                let table = document.querySelector('#skuTable tbody');

                let row = `
                    <tr>
                        <td><input type="text" name="sku[Size_Name][]" class="form-control" required></td>
                        <td><input type="text" name="sku[Color_Name][]" class="form-control" required></td>
                        <td><input type="text" name="sku[Size_Code][]" class="form-control"></td>
                        <td><input type="text" name="sku[Color_Code][]" class="form-control"></td>
                        <td><input type="text" name="sku[JanCD][]" class="form-control"></td>
                        <td><input type="number" name="sku[Quantity][]" class="form-control"></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm removeSku">X</button>
                        </td>
                    </tr>
                `;
                table.insertAdjacentHTML('beforeend', row);
            });

            // Remove row from add new form
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('removeSku')) {
                    e.target.closest('tr').remove();
                }
            });

            // Excel import functionality
            const previewBtn = document.getElementById('previewBtn');
            const confirmForm = document.getElementById('confirmImportForm');

            // if (previewBtn) {
            //     previewBtn.addEventListener('click', function() {
            //         const fileInput = document.getElementById('excelFile');
            //         const file = fileInput.files[0];
            //         if (!file) {
            //             alert('Please select a file first.');
            //             return;
            //         }
            //         selectedFile = file;

            //         const formData = new FormData();
            //         formData.append('file', file);
            //         formData.append('_token', '{{ csrf_token() }}');

            //         fetch("{{ route('mitems.preview') }}", {
            //                 method: 'POST',
            //                 body: formData
            //             })
            //             .then(res => res.json())
            //             .then(data => {
            //                 const thead = document.getElementById('previewTableHead');
            //                 const tbody = document.getElementById('previewTableBody');
            //                 thead.innerHTML = '';
            //                 tbody.innerHTML = '';

            //                 if (data.headers.length > 0) {
            //                     thead.innerHTML = '<tr>' + data.headers.map(h => `<th>${h}</th>`).join('') + '</tr>';
            //                 }
            //                 data.rows.forEach(rowObj => {
            //                     let rowHtml = '<tr>' + rowObj.data.map(cell => `<td>${cell ?? ''}</td>`).join('') + '</tr>';
            //                     tbody.innerHTML += rowHtml;
            //                 });

            //                 let errorHtml = '';
            //                 if (data.errors.length > 0) {
            //                     data.errors.forEach(err => {
            //                         err.messages.forEach(msg => {
            //                             errorHtml += `<li>Row ${err.row}: ${msg}</li>`;
            //                         });
            //                     });
            //                     errorHtml = `<div class="alert alert-danger"><ul>${errorHtml}</ul></div>`;
            //                 }

            //                 let errorContainer = document.getElementById('previewErrors');
            //                 if (!errorContainer) {
            //                     errorContainer = document.createElement('div');
            //                     errorContainer.id = 'previewErrors';
            //                     document.getElementById('previewTableContainer').prepend(errorContainer);
            //                 }
            //                 errorContainer.innerHTML = errorHtml;

            //                 document.getElementById('confirmImportForm').querySelector('button[type="submit"]').disabled = data.errors.length > 0;
            //                 new bootstrap.Modal(document.getElementById('excelPreviewModal')).show();
            //             })
            //             .catch(err => {
            //                 console.error(err);
            //                 alert('Error reading Excel file.');
            //             });
            //     });
            // }

            if (confirmForm) {
                confirmForm.addEventListener('submit', function(e) {
                    if (!selectedFile) {
                        e.preventDefault();
                        alert('No file selected for import!');
                        return;
                    }

                    const oldFileInput = confirmForm.querySelector('.hidden-import-file');
                    if (oldFileInput) oldFileInput.remove();

                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.name = 'file';
                    fileInput.classList.add('hidden-import-file');

                    const dt = new DataTransfer();
                    dt.items.add(selectedFile);
                    fileInput.files = dt.files;

                    confirmForm.appendChild(fileInput);
                });
            }
        });

        // Function to add SKU row to edit form
        function addEditSkuRow(skuData = {}) {
            let table = document.querySelector('#editSkuTable tbody');

            const row = `
                <tr>
                    <td>
                        <input type="text" name="sku[Size_Name][]" class="form-control" 
                            value="${skuData.Size_Name || ''}" required>
                    </td>
                    <td>
                        <input type="text" name="sku[Color_Name][]" class="form-control" 
                            value="${skuData.Color_Name || ''}" required>
                    </td>
                    <td>
                        <input type="text" name="sku[Size_Code][]" class="form-control" 
                            value="${skuData.Size_Code || ''}">
                    </td>
                    <td>
                        <input type="text" name="sku[Color_Code][]" class="form-control" 
                            value="${skuData.Color_Code || ''}">
                    </td>
                    <td>
                        <input type="text" name="sku[JanCD][]" class="form-control" 
                            value="${skuData.JanCD || ''}">
                    </td>
                    <td>
                        <input type="number" name="sku[Quantity][]" class="form-control" 
                            value="${skuData.Quantity || ''}">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm removeEditSku">X</button>
                    </td>
                </tr>
            `;
            table.insertAdjacentHTML('beforeend', row);
        }

        // Remove SKU row from edit form
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeEditSku')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
</body>

</html>