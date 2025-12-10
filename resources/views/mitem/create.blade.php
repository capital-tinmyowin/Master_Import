<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .form-group {
            position: relative;
            margin-bottom: 1rem;
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

        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        .modal-xl {
            max-width: 1200px !important;
        }

        #skuPopupTableWrapper {
            overflow-x: auto;
            white-space: nowrap;
        }

        #skuPopupTable th {
            white-space: nowrap;
        }

        #photoDropArea.dragover {
            background: #d0ebff;
            border-color: #339af0;
        }

        .photo-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background: #fafafa;
        }

        .photo-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 6px;
        }

        #photoDropArea.dragover {
            background: #d0ebff;
            border-color: #339af0;
        }

        .photo-upload-card {
            margin-left: 0;
            padding: 0;
        }

        .item-name-textarea {
            height: 155px;
        }

        #photoDropArea.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background-color: #e9ecef;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }

        .invalid-feedback.jan-error {
            display: block;
            font-size: 0.8rem;
            color: #dc3545;
            margin-top: 0.25rem;
        }

        .form-control {
            min-height: 38px;
            transition: all 0.2s ease;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }

        .invalid-feedback {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            font-size: 0.8rem;
            color: #dc3545;
            margin-top: 0.25rem;
            z-index: 1;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }

        .form-control {
            transition: border-color 0.15s ease-in-out;
        }

        .byte-counter {
            font-size: 12px;
            color: #666;
            text-align: right;
            margin-top: 2px;
            font-family: Meiryo, sans-serif;
        }

        .byte-counter.warning {
            color: orange;
        }

        .byte-counter.error {
            color: red;
            font-weight: bold;
        }

        .item-name-textarea {
            height: 155px;
            resize: vertical;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .jan-error {
            display: block !important;
            position: absolute;
            width: 100%;
        }

        .is-valid {
            border-color: #198754 !important;
            background-color: #f8fff8 !important;
        }

        .form-control:focus.is-valid {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        .validation-error {
            display: block !important;
        }

        .form-control.is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        input[name="JanCD"] {
            font-family: monospace;
        }

        input[name="JanCD"]::placeholder {
            font-family: inherit;
        }

        .card .form-control,
        .card .form-select {
            border: 1px solid #000000ff;
            border-radius: 0.375rem;
        }

        .card .form-control:focus,
        .card .form-select:focus {
            border-color: #000000ff;
            outline: 0;
            box-shadow: 0 0 0 0.5rem rgba(13, 110, 253, 0.25);
        }

        .input-group-text {
            border: 1px solid #000000ff;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: box-shadow 0.15s ease-in-out, border-color 0.15s ease-in-out;
        }

        .shadow-sm {
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            border: 2px solid #c6c6c6;
        }

        .card-body .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .card-body .form-label {
            width: 80px;
            margin-bottom: 0;
            margin-right: 1rem;
            flex-shrink: 0;
            font-weight: 500;
            text-align: right;
        }

        .card-body .form-control,
        .card-body .form-select,
        .card-body .input-group {
            flex: 1;
        }

        .card-body .invalid-feedback {
            position: absolute;
            top: 100%;
            left: 120px;
            width: calc(100% - 120px);
        }

        .card-body textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .card-body .price-input {
            text-align: right;
        }

        .card-body .input-group {
            flex: 1;
        }

        .parallel-form-groups {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .parallel-form-groups .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .textarea-form-group {
            margin-bottom: 2rem !important;
        }

        .textarea-form-group .form-label {
            align-self: flex-start;
            margin-top: 0.5rem;
            text-align: right;
        }

        .card-body.d-flex.flex-column .form-group {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0;
            flex: 1;
        }

        .card-body.d-flex.flex-column .form-label {
            width: 120px;
            margin-bottom: 0;
            margin-right: 1rem;
            flex-shrink: 0;
            font-weight: 500;
            text-align: right;
            margin-top: 0.5rem;
        }

        .card-body.d-flex.flex-column textarea {
            flex: 1;
            resize: vertical;
        }

        .card-body.d-flex.flex-column .invalid-feedback {
            position: absolute;
            top: 100%;
            left: 120px;
            width: calc(100% - 120px);
        }

        .card-title.fw-bold {
            text-align: right;
        }

        .fw-bold.mb-3 {
            text-align: left;
        }

        .modal-title {
            text-align: right;
        }

        .mb-3 {
            text-align: right;
        }

        .card-body .form-group.h-100 {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .card-body .form-group.h-100 .form-label {
            width: 100%;
            text-align: right;
            margin-bottom: 0.5rem;
        }

        .card-body .form-group.h-100 textarea {
            flex: 1;
            resize: vertical;
        }

        /* SKU Table specific styles - FIXED VERSION */
        /* SKU Table specific styles - FIXED VERSION */
        #skuPopupTable {
            border-collapse: separate;
            border-spacing: 0 10px;
            /* Add space between rows */
            width: 100%;
            table-layout: fixed;
            /* Fixed layout for consistent columns */
        }

        #skuPopupTable thead {
            background: #84C8FF !important;
        }

        #skuPopupTable thead th {
            border-bottom: 2px solid #dee2e6;
            padding: 10px 4px !important;
            white-space: nowrap;
            vertical-align: middle !important;
        }

        /* Table cells - FIXED */
        #skuPopupTable tbody td {
            position: relative;
            padding: 4px 4px !important;
            /* Reduced top padding */
            vertical-align: top !important;
            /* Changed from middle to top */
            border: 1px solid #dee2e6;
            height: 70px;
            /* Fixed height to contain everything */
            overflow: visible;
            /* Allow error messages to be visible */
        }

        /* Container div inside each cell */
        #skuPopupTable td>div {
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            /* Align input to top */
        }

        /* Input field styling - positioned at top */
        #skuPopupTable .form-control {
            position: relative;
            z-index: 2;
            margin-bottom: auto;
            /* Push to top */
            padding: 5px 8px !important;
            font-size: 0.875rem !important;
            height: 32px !important;
            width: 100%;
            flex-shrink: 0;
            /* Don't shrink */
        }

        #skuPopupTable .form-select {
            padding: 5px 8px !important;
            font-size: 0.875rem !important;
            height: 32px !important;
            width: 100%;
            flex-shrink: 0;
            /* Don't shrink */
            margin-bottom: auto;
            /* Push to top */
        }

        /* Error message styling - positioned at bottom */
        #skuPopupTable .invalid-feedback {
            position: absolute !important;
            bottom: 2px !important;
            /* Position at bottom of cell */
            left: 4px !important;
            right: 4px !important;
            font-size: 0.7rem !important;
            color: #dc3545;
            padding: 1px 3px;
            border-radius: 2px;
            z-index: 3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: none !important;
            line-height: 1.2;
            margin-top: auto;
            /* Push to bottom */
        }

        #skuPopupTable .is-invalid~.invalid-feedback {
            display: block !important;
        }

        /* Make error messages shorter */
        #skuPopupTable .invalid-feedback {
            font-size: 0.65rem !important;
            padding: 1px 2px;
        }

        /* Delete button cell - different styling */
        #skuPopupTable td:first-child {
            padding: 8px 4px !important;
            vertical-align: middle !important;
            /* Center the button */
            height: auto;
        }

        #skuPopupTable td:first-child>div {
            display: block;
            /* Remove flex for button cell */
            height: auto;
        }

        /* Delete button styling */
        #skuPopupTable .deleteRow {
            padding: 4px 8px !important;
            font-size: 0.75rem !important;
            height: 28px !important;
            margin: 0 auto;
        }

        /* Column widths */
        #skuPopupTable th:nth-child(1) {
            width: 80px;
        }

        /* Delete */
        #skuPopupTable th:nth-child(2) {
            width: 120px;
        }

        /* サイズ名 */
        #skuPopupTable th:nth-child(3) {
            width: 120px;
        }

        /* カラー名 */
        #skuPopupTable th:nth-child(4) {
            width: 100px;
        }

        /* サイズコード */
        #skuPopupTable th:nth-child(5) {
            width: 100px;
        }

        /* カラーコード */
        #skuPopupTable th:nth-child(6) {
            width: 170px;
        }

        /* JANコード */
        #skuPopupTable th:nth-child(7) {
            width: 110px;
        }

        /* Qty-flag */
        #skuPopupTable th:nth-child(8) {
            width: 90px;
        }

        /* Modal body fixes */
        .modal-body {
            max-height: 65vh;
            overflow-y: auto;
            padding: 15px !important;
        }

        /* Ensure table doesn't overflow modal */
        #skuPopupTable {
            margin-bottom: 0;
        }

        /* Make the modal content area scrollable */
        .modal-xl .modal-content {
            max-height: 85vh;
        }

        .modal-xl .modal-body {
            overflow-y: auto;
            padding: 0 15px;
        }

        #skuPopupTable th:nth-child(1),
        #skuPopupTable td:nth-child(1) {
            width: 40px;
        }

        #skuPopupTable th:nth-child(2),
        #skuPopupTable td:nth-child(2) {
            width: 190px;
        }

        #skuPopupTable th:nth-child(3),
        #skuPopupTable td:nth-child(3) {
            width: 190px;
        }

        #skuPopupTable th:nth-child(4),
        #skuPopupTable td:nth-child(4) {
            width: 100px;
        }

        #skuPopupTable th:nth-child(5),
        #skuPopupTable td:nth-child(5) {
            width: 100px;
        }

        #skuPopupTable th:nth-child(6),
        #skuPopupTable td:nth-child(6) {
            width: 150px;
        }

        #skuPopupTable th:nth-child(7),
        #skuPopupTable td:nth-child(7) {
            width: 90px;
        }

        #skuPopupTable th:nth-child(8),
        #skuPopupTable td:nth-child(8) {
            width: 90px;
        }

        .price-card .form-group .invalid-feedback {
            position: absolute;
            top: 100%;
            left: 0;
            /* Adjust based on your label width */
            width: 200px;
        }

        .price-card .card-body .form-label {
            width: 120px;
            margin-bottom: 0;
            margin-right: 1rem;
            flex-shrink: 0;
            font-weight: 500;
            text-align: right;
        }
    </style>
</head>

<body>
    @include('navbar')

    <div class="container mt-4">
        <h3 class="mb-3">Add New Item</h3>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <a href="{{ route('mitems.index') }}" class="btn btn-secondary mb-3">← Back</a>

        <form action="{{ route('mitems.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-7 d-flex">
                    <div class="card shadow-sm flex-fill">
                        <div class="card-body">
                            <div class="parallel-form-groups">
                                <div class="form-group">
                                    <label class="form-label">商品番号</label>
                                    <input type="text" name="Item_Code" class="form-control @error('Item_Code') is-invalid @enderror" value="{{ old('Item_Code') }}" required>
                                    @error('Item_Code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">JANCD</label>
                                    <input type="text" name="JanCD" class="form-control @error('JanCD') is-invalid @enderror" value="{{ old('JanCD') }}">
                                    @error('JanCD') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Single form group for メーカー名 -->
                            <div class="form-group">
                                <label class="form-label">メーカー名</label>
                                <input type="text" name="MakerName" class="form-control @error('MakerName') is-invalid @enderror" value="{{ old('MakerName') }}" required>
                                @error('MakerName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Single form group for 商品名 -->
                            <div class="form-group textarea-form-group">
                                <label class="form-label">商品名</label>
                                <textarea name="ItemName" class="form-control @error('ItemName') is-invalid @enderror item-name-textarea" rows="3" required>{{ old('ItemName') }}</textarea>
                                @error('ItemName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: 注記 -->
                <div class="col-md-5 d-flex">
                    <div class="card shadow-sm flex-fill">
                        <div class="card-body">
                            <div class="form-group" style="height: 100%; display: flex; align-items: flex-start;">
                                <label class="form-label">注記</label>
                                <div style="flex: 1; display: flex; flex-direction: column;">
                                    <textarea name="Note" class="form-control @error('Note') is-invalid @enderror" rows="10" style="flex: 1;">{{ old('Note') }}</textarea>
                                    @error('Note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--SKU CARD -->
            <div class="row g-3 mt-3">
                <!-- SKU 登録 -->
                <div class="col-md-8">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="card-title fw-bold">SKU 登録</h6>
                                <button type="button" class="btn btn-primary btn-sm" id="addSkuBtn">+ Add SKU</button>
                            </div>
                            <div id="skuPreviewContainer" class="mt-3"></div>
                            <input type="hidden" name="sku[Size_Name]" id="skuSizeName">
                            <input type="hidden" name="sku[Color_Name]" id="skuColorName">
                            <input type="hidden" name="sku[Size_Code]" id="skuSizeCode">
                            <input type="hidden" name="sku[Color_Code]" id="skuColorCode">
                            <input type="hidden" name="sku[JanCD]" id="skuJanCD">
                            <input type="hidden" name="sku[Quantity]" id="skuQuantity">
                        </div>
                    </div>
                </div>

                <!-- PRICE CARD -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 price-card">
                        <div class="card-body">
                            <!-- Price fields with horizontal layout -->
                            <div class="form-group">
                                <label class="form-label">定価 (List Price)</label>
                                <div class="input-group" style="width: 200px; flex: 0 0 auto;"> 
                                    <span class="input-group-text">¥</span>
                                    <input name="ListPrice" id="ListPrice" class="form-control text-end price-input @error('ListPrice') is-invalid @enderror" value="{{ old('ListPrice') }}" required style="width: 150px;"> <!-- CHANGED HERE -->
                                    @error('ListPrice') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">原価 (Sale Price)</label>
                                <div class="input-group" style="width: 200px; flex: 0 0 auto;"> 
                                    <span class="input-group-text">¥</span>
                                    <input name="SalePrice" id="SalePrice" class="form-control text-end price-input @error('SalePrice') is-invalid @enderror" value="{{ old('SalePrice') }}" required style="width: 150px;"> <!-- CHANGED HERE -->
                                    @error('SalePrice') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--  PHOTO UPLOAD CARD -->
                <div class="row g-3 mt-3 photo-upload-card">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">商品写真 (Product Photos)</h6>
                                <div id="photoPreviewList" class="row mb-3 mt-3 g-3"></div>

                                <!-- Drag/Click Upload Area -->
                                <div id="photoDropArea" class="p-4 border border-secondary rounded text-center bg-light" style="cursor:pointer;">
                                    <p class="mb-1">📷 Click or Drag & Drop photos here</p>
                                    <small class="text-muted">Supported: JPG — Multiple allowed (5 max)</small> <input type="file" id="photoInput" name="Photos[]" class="d-none" accept="image/*" multiple>
                                </div>

                                <!-- Hidden JSON to send to backend -->
                                <input type="hidden" name="PhotoData" id="hiddenPhotoData">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <table id="finalSkuTable" class="d-none">
                <tbody id="finalSkuBody">
                    <!-- JS will insert hidden inputs here -->
                </tbody>
            </table>

            <input type="hidden" name="sku_data" id="skuDataInput">
            <button id="saveItem" type="submit" class="btn btn-success float-end mt-3">Save</button>
        </form>

        <!-- SKU Adding Form Modal -->
        <div class="modal fade" id="skuModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">SKU Adding Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <table class="table table-bordered text-center align-middle" id="skuPopupTable">
                            <thead style="background:#84C8FF;">
                                <tr>
                                    <th style="width:80px;">Delete</th>
                                    <th>サイズ名<br><small>(項目選択肢別在庫用横軸選択肢)</small></th>
                                    <th>カラー名<br><small>(項目選択肢別在庫用縦軸選択肢)</small></th>
                                    <th>サイズコード</th>
                                    <th>カラーコード</th>
                                    <th>JANコード</th>
                                    <th>Qty-flag</th>
                                    <th>在庫数</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be added by JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="addNewRowBtn">Add New Row</button>
                        <button type="button" class="btn btn-success" id="saveSkuBtn">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let skuTempData = [];
        let photoData = [];
        const MAX_PHOTOS = 5;
        let allPhotoFiles = []; /

        // Byte counting functions
        function getByteLength(text) {
            var byteLength = 0;
            for (let i = 0; i < text.length; i++) {
                let charCode = text.charCodeAt(i);

                if (charCode <= 0x007F) {
                    byteLength += 1; // ASCII (1 byte)
                } else if (charCode <= 0x07FF) {
                    byteLength += 2; // 2-byte characters
                } else if (charCode >= 0xD800 && charCode <= 0xDBFF) {
                    // Surrogate pair (4 bytes for UTF-8)
                    byteLength += 4;
                    i++; // Skip the next surrogate
                } else if (charCode >= 0xDC00 && charCode <= 0xDFFF) {
                    // Low surrogate - should be handled by the high surrogate case
                    byteLength += 3;
                } else {
                    byteLength += 3; // Standard 3-byte characters (CJK, Japanese, etc.)
                }
            }
            return byteLength;
        }

        function setupByteLimitValidation(inputElement, maxBytes) {
            let previousText = inputElement.value;

            const handleInput = function(event) {
                var currentText = inputElement.value;
                var totalByteLength = getByteLength(currentText);

                if (totalByteLength > maxBytes) {
                    // Restore previous text if exceeds limit - user cannot type more
                    inputElement.value = previousText;
                } else {
                    // Update the previous text to the current text
                    previousText = currentText;
                }
            };

            // Add event listener
            inputElement.addEventListener('input', handleInput);
        }

        function setupItemCodeValidation() {
            const itemCodeField = document.querySelector('input[name="Item_Code"]');

            if (itemCodeField) {
                let previousValue = itemCodeField.value;

                const handleInput = function(event) {
                    const newValue = itemCodeField.value;

                    // Check if the new value contains invalid characters
                    if (!/^[a-zA-Z0-9_-]*$/.test(newValue)) {
                        // Restore previous valid value - user cannot type invalid characters
                        itemCodeField.value = previousValue;
                    } else {
                        // Update previous value
                        previousValue = newValue;
                    }
                };

                itemCodeField.addEventListener('input', handleInput);

                //handle paste events
                itemCodeField.addEventListener('paste', function(e) {
                    // Let the paste happen first, then clean it
                    setTimeout(() => {
                        // Remove any invalid characters from pasted content
                        itemCodeField.value = itemCodeField.value.replace(/[^a-zA-Z0-9_-]/g, '');
                        previousValue = itemCodeField.value;
                    }, 0);
                });
            }
        }

        function numberWithCommas(x) {
            if (!x) return "";
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Create new row for popup
        function createSkuRow() {
            return `
            <tr>
                <td>
                    <div>
                        <button type="button" class="btn btn-danger btn-sm deleteRow">Delete</button>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text" class="form-control size-name" required>
                        <div class="invalid-feedback">必須</div>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text" class="form-control color-name" required>
                        <div class="invalid-feedback">必須</div>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text" class="form-control size-code" required>
                        <div class="invalid-feedback">必須</div>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text" class="form-control color-code" required>
                        <div class="invalid-feedback">必須</div>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text" class="form-control jan" required maxlength="13">
                        <div class="invalid-feedback">13桁の数字</div>
                    </div>
                </td>
                <td>
                    <div>
                        <select class="form-select qty-flag">
                            <option value="完売">完売 (0)</option>
                            <option value="手入力">手入力</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="number" class="form-control qty" style="text-align: right;" required>
                        <div class="invalid-feedback">必須</div>
                    </div>
                </td>
            </tr>
        `;
        }

        // Real-time Item_Code validation with save button control
        function setupItemCodeRealTimeValidation() {
            const itemCodeField = document.querySelector('input[name="Item_Code"]');
            const saveButton = document.getElementById('saveItem');

            if (!itemCodeField || !saveButton) return;

            let validationTimeout;

            const validateItemCode = function() {
                const itemCode = itemCodeField.value.trim();

                // Clear previous states
                itemCodeField.classList.remove('is-invalid', 'is-valid');
                const existingError = itemCodeField.parentNode.querySelector('.item-code-error');
                if (existingError) existingError.remove();

                // Enable save button by default
                saveButton.disabled = false;
                saveButton.classList.remove('btn-secondary');
                saveButton.classList.add('btn-success');

                if (itemCode === '') {
                    // If empty, keep save button enabled (other validations will catch this)
                    return;
                }

                // FIXED: Use correct route - change /items/ to /mitems/
                fetch(`/mitems/check-item-code?item_code=${encodeURIComponent(itemCode)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.exists) {
                            itemCodeField.classList.add('is-invalid');
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback item-code-error';
                            errorDiv.textContent = '重複した商品番号。';
                            itemCodeField.parentNode.appendChild(errorDiv);

                            saveButton.disabled = true;
                            saveButton.classList.remove('btn-success');
                            saveButton.classList.add('btn-secondary');
                            saveButton.title = '商品番号が重複しているため保存できません';
                        } else {
                            itemCodeField.classList.add('is-valid');
                            saveButton.disabled = false;
                            saveButton.classList.remove('btn-secondary');
                            saveButton.classList.add('btn-success');
                            saveButton.title = '';
                        }
                    })
                    .catch(error => {
                        console.error('Validation error:', error);
                        // Don't disable button on network error, just show warning
                        saveButton.title = '確認エラー: ネットワーク接続を確認してください';
                    });
            };

            itemCodeField.addEventListener('input', function() {
                clearTimeout(validationTimeout);
                validationTimeout = setTimeout(validateItemCode, 800);
            });

            itemCodeField.addEventListener('blur', validateItemCode);

            // Run initial validation if there's already a value
            if (itemCodeField.value.trim()) {
                validateItemCode();
            }
        }

        function renderSkuMatrix() {
            if (skuTempData.length === 0) {
                document.getElementById("skuPreviewContainer").innerHTML = "";
                return;
            }

            // Extract unique size names (XS, S, M...)
            let sizes = [...new Set(skuTempData.map(x => x.Size_Name))];

            // Extract unique color names (Red, White, Black...)
            let colors = [...new Set(skuTempData.map(x => x.Color_Name))];

            // Begin table
            let html = `
                    <table class="table table-bordered text-center">
                        <thead class="table-warning">
                            <tr>
                                <th>Color_Name</th>`;
            // Add size columns
            sizes.forEach(size => {
                html += `<th>${size}</th>`;
            });

            html += `</tr></thead><tbody>`;

            // Build rows for each color
            colors.forEach(color => {
                html += `<tr><td>${color}</td>`;

                sizes.forEach(size => {
                    // Find qty for matching (Color + Size)
                    let row = skuTempData.find(
                        x => x.Color_Name === color && x.Size_Name === size
                    );

                    html += `<td>${row ? row.Quantity : 0}</td>`;
                });

                html += `</tr>`;
            });

            html += "</tbody></table>";

            document.getElementById("skuPreviewContainer").innerHTML = html;
        }

        const dropArea = document.getElementById("photoDropArea");
        const fileInput = document.getElementById("photoInput");
        const previewList = document.getElementById("photoPreviewList");
        const hiddenPhotoData = document.getElementById("hiddenPhotoData");

        function showPhotoLimitError() {
            alert(`Error: Maximum ${MAX_PHOTOS} photos allowed. You already have ${photoData.length} photos. Please delete some photos before adding more.`);
        }

        function updateDropAreaState() {
            if (photoData.length >= MAX_PHOTOS) {
                dropArea.style.opacity = "0.6";
                dropArea.style.cursor = "not-allowed";
                dropArea.innerHTML = `
                        <p class="mb-1 text-muted">📷 Maximum ${MAX_PHOTOS} photos reached</p>
                        <small class="text-muted">Delete photos to add more</small>
                    `;
            } else {
                dropArea.style.opacity = "1";
                dropArea.style.cursor = "pointer";
                dropArea.innerHTML = `
                        <p class="mb-1">📷 Click or Drag & Drop photos here</p>
                        <small class="text-muted">Supported: JPG — Multiple allowed (${MAX_PHOTOS} max)</small>
                    `;
            }
        }

        function renderPhotos() {
            previewList.innerHTML = "";

            photoData.forEach(photo => {
                previewList.insertAdjacentHTML("beforeend", `
                        <div class="col-md-3" data-id="${photo.id}">
                            <div class="photo-card">
                                <img src="${photo.preview}">
                                <!-- No rename input anymore -->
                                <button class="btn btn-danger btn-sm w-100 mt-2 delete-photo"
                                        data-id="${photo.id}">Delete</button>
                            </div>
                        </div>
                    `);
            });
        }

        function getFileNameWithoutExtension(filename) {
            return filename.replace(/\.[^/.]+$/, "");
        }

        function validateAllFields() {
            let isValid = true;

            // Clear previous errors (but preserve item-code errors)
            document.querySelectorAll('.is-invalid').forEach(el => {
                if (!el.classList.contains('item-code-error')) {
                    el.classList.remove('is-invalid');
                }
            });
            document.querySelectorAll('.validation-error, .jan-validation-error').forEach(el => {
                el.remove();
            });

            // 1. Validate Item_Code (required)
            const itemCodeField = document.querySelector('input[name="Item_Code"]');
            if (!itemCodeField.value.trim()) {
                showFieldError(itemCodeField, '商品番号は必須です');
                isValid = false;
            }

            // 2. Validate MakerName (required)
            const makerNameField = document.querySelector('input[name="MakerName"]');
            const makerNameValue = makerNameField.value.trim();
            if (!makerNameValue) {
                showFieldError(makerNameField, 'メーカー名は必須です');
                isValid = false;
            } else if (getByteLength(makerNameValue) > 100) {
                showFieldError(makerNameField, 'メーカー名は100バイト以内で入力してください');
                isValid = false;
            }

            // 3. Validate ItemName (required)
            const itemNameField = document.querySelector('textarea[name="ItemName"]');
            const itemNameValue = itemNameField.value.trim();
            if (!itemNameValue) {
                showFieldError(itemNameField, '商品名は必須です');
                isValid = false;
            } else if (getByteLength(itemNameValue) > 200) {
                showFieldError(itemNameField, '商品名は200バイト以内で入力してください');
                isValid = false;
            }

            // 4. Validate ListPrice (required)
            const listPriceField = document.querySelector('input[name="ListPrice"]');
            const listPriceValue = listPriceField.value.replace(/,/g, '');
            if (!listPriceValue || isNaN(listPriceValue) || parseFloat(listPriceValue) <= 0) {
                showFieldError(listPriceField, '有効な定価を入力');
                isValid = false;
            }

            // 5. Validate SalePrice (required)
            const salePriceField = document.querySelector('input[name="SalePrice"]');
            const salePriceValue = salePriceField.value.replace(/,/g, '');
            if (!salePriceValue || isNaN(salePriceValue) || parseFloat(salePriceValue) <= 0) {
                showFieldError(salePriceField, '有効な原価を入力');
                isValid = false;
            }

            // 6. Validate JANCD (required and must be 13 digits)
            const janField = document.querySelector('input[name="JanCD"]');
            const janValue = janField.value.trim();
            if (!janValue) {
                showFieldError(janField, 'JANコードは必須です');
                isValid = false;
            } else if (!/^\d{13}$/.test(janValue)) {
                showFieldError(janField, '「JANコードは13桁です」');
                isValid = false;
            }
            // 7. Validate Note byte limit
            const noteField = document.querySelector('textarea[name="Note"]');
            const noteValue = noteField.value.trim();
            if (noteValue && getByteLength(noteValue) > 500) {
                showFieldError(noteField, '注記は500バイト以内で入力してください');
                isValid = false;
            }

            return isValid;
        }
        // Helper function to show field errors
        function showFieldError(field, message) {
            field.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback validation-error';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }

        function setupRealTimeValidation() {
            // Item_Code validation 
            const itemCodeField = document.querySelector('input[name="Item_Code"]');
            if (itemCodeField) {
                itemCodeField.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        showFieldError(this, '商品番号は必須です');
                    } else {
                        clearFieldError(this);
                    }
                });
                // Also clear error on input when user starts typing
                itemCodeField.addEventListener('input', function() {
                    if (this.value.trim()) {
                        clearFieldError(this);
                    }
                });
            }

            // MakerName validation - IMPROVED
            const makerNameField = document.querySelector('input[name="MakerName"]');
            if (makerNameField) {
                makerNameField.addEventListener('blur', function() {
                    const value = this.value.trim();
                    if (!value) {
                        showFieldError(this, 'メーカー名は必須です');
                    } else if (getByteLength(value) > 100) {
                        showFieldError(this, 'メーカー名は100バイト以内で入力してください');
                    } else {
                        clearFieldError(this);
                    }
                });

                makerNameField.addEventListener('input', function() {
                    const value = this.value.trim();
                    // Clear error as soon as user types anything
                    if (value) {
                        clearFieldError(this);
                    }
                });
            }

            // ItemName validation - IMPROVED
            const itemNameField = document.querySelector('textarea[name="ItemName"]');
            if (itemNameField) {
                itemNameField.addEventListener('blur', function() {
                    const value = this.value.trim();
                    if (!value) {
                        showFieldError(this, '商品名は必須です');
                    } else if (getByteLength(value) > 200) {
                        showFieldError(this, '商品名は200バイト以内で入力してください');
                    } else {
                        clearFieldError(this);
                    }
                });
                itemNameField.addEventListener('input', function() {
                    // Clear error as soon as user types anything
                    if (this.value.trim()) {
                        clearFieldError(this);
                    }
                });
            }

            // Price validation - IMPROVED
            const priceFields = document.querySelectorAll('.price-input');
            priceFields.forEach(field => {
                field.addEventListener('blur', function() {
                    const value = this.value.replace(/,/g, '');
                    if (!value || isNaN(value) || parseFloat(value) <= 0) {
                        const fieldName = this.name === 'ListPrice' ? '定価' : '原価';
                        showFieldError(this, `有効な${fieldName}を入力`);
                    } else {
                        clearFieldError(this);
                    }
                });
                field.addEventListener('input', function() {
                    // Clear error as soon as user types any number
                    if (this.value.replace(/,/g, '').trim()) {
                        clearFieldError(this);
                    }
                });
            });

            // JANCD validation - REMOVE THIS SECTION COMPLETELY
            // We'll keep your setupJanCDNumberOnly() function for this

            // Note validation - IMPROVED
            const noteField = document.querySelector('textarea[name="Note"]');
            if (noteField) {
                noteField.addEventListener('blur', function() {
                    const value = this.value.trim();
                    if (value && getByteLength(value) > 500) {
                        showFieldError(this, '注記は500バイト以内で入力してください');
                    } else {
                        clearFieldError(this);
                    }
                });
                noteField.addEventListener('input', function() {
                    // Clear error as soon as user types
                    if (this.value.trim()) {
                        clearFieldError(this);
                    }
                });
            }
        }
        // Helper function to clear field errors
        // Helper function to clear field errors
        function clearFieldError(field) {
            field.classList.remove('is-invalid');

            // Remove any validation errors
            const error = field.parentNode.querySelector('.validation-error');
            if (error) error.remove();

            // Also remove jan-validation-error
            const janError = field.parentNode.querySelector('.jan-validation-error');
            if (janError) janError.remove();

            // Also remove item-code-error (but keep duplicate check errors)
            if (!field.classList.contains('item-code-error')) {
                const itemCodeError = field.parentNode.querySelector('.item-code-error');
                if (itemCodeError) itemCodeError.remove();
            }
        }

        function setupJanCDNumberOnly() {
            const janField = document.querySelector('input[name="JanCD"]');
            if (janField) {
                let previousValue = janField.value;

                janField.addEventListener('input', function() {
                    const newValue = janField.value;

                    // Only allow numbers
                    if (!/^\d*$/.test(newValue)) {
                        // Restore previous valid value
                        janField.value = previousValue;
                    } else {
                        // Update previous value
                        previousValue = newValue;

                        // Clear error as soon as user types
                        clearFieldError(janField);

                        // Auto-validate when reaching 13 digits
                        if (janField.value.length === 13) {
                            validateJanCDField(janField);
                        }
                    }
                });

                // Handle paste events
                janField.addEventListener('paste', function(e) {
                    setTimeout(() => {
                        // Remove any non-digit characters from pasted content
                        janField.value = janField.value.replace(/\D/g, '');
                        previousValue = janField.value;
                        clearFieldError(janField);

                        // Auto-validate if 13 digits after paste
                        if (janField.value.length === 13) {
                            validateJanCDField(janField);
                        }
                    }, 0);
                });

                // Validate on blur
                janField.addEventListener('blur', function() {
                    validateJanCDField(this);
                });

                // Also validate on input to show real-time feedback
                janField.addEventListener('input', function() {
                    // Clear error when user starts typing
                    if (this.value.trim()) {
                        clearFieldError(this);
                    }
                });
            }
        }

        function validateJanCDField(field) {
            const value = field.value.trim();

            // Clear previous errors
            field.classList.remove('is-invalid');
            const existingError = field.parentNode.querySelector('.jan-validation-error');
            if (existingError) existingError.remove();

            if (!value) {
                field.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback jan-validation-error';
                errorDiv.textContent = 'JANコードは必須です';
                field.parentNode.appendChild(errorDiv);
                return false;
            } else if (!/^\d{13}$/.test(value)) {
                field.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback jan-validation-error';
                errorDiv.textContent = '「JANコードは13桁です」';
                field.parentNode.appendChild(errorDiv);
                return false;
            }

            return true;
        }

        function setupSkuJanNumberOnly() {
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('jan')) {
                    const janField = e.target;
                    const newValue = janField.value;

                    // Only allow numbers
                    if (!/^\d*$/.test(newValue)) {
                        // Remove non-digit characters
                        janField.value = newValue.replace(/\D/g, '');
                    }

                    // Clear error when user types
                    if (janField.value.trim()) {
                        janField.classList.remove('is-invalid');
                        if (janField.nextElementSibling) {
                            janField.nextElementSibling.style.display = 'none';
                        }
                    }
                }
            });
        }
        // UPDATE PHOTO DATA JSON FOR BACKEND
        function updateHiddenInput() {
            hiddenPhotoData.value = JSON.stringify(photoData.map(p => ({
                id: p.id,
                oldName: p.file.name,
                newName: p.baseName
            })));
        }

        // HANDLE MULTIPLE FILES
        function handleFiles(files) {
            const remainingSlots = MAX_PHOTOS - photoData.length;

            if (files.length > remainingSlots) {
                showPhotoLimitError();
                files = Array.from(files).slice(0, remainingSlots);
            }

            if (files.length === 0) return;

            let filesProcessed = 0;

            [...files].forEach(file => {
                const id = Date.now() + Math.random(); // unique id

                const reader = new FileReader();
                reader.onload = e => {
                    file.photoId = id;

                    // Add to both arrays
                    allPhotoFiles.push(file); // Store the actual file object
                    photoData.push({
                        id: id,
                        baseName: file.name,
                        file: file,
                        preview: e.target.result
                    });

                    filesProcessed++;

                    if (filesProcessed === files.length) {
                        renderPhotos();
                        updateHiddenInput();
                        updateFileInput(); // Update the actual file input
                    }
                };

                reader.readAsDataURL(file);
            });
        }

        function updateFileInput() {
            const dt = new DataTransfer();

            allPhotoFiles.forEach(file => {
                dt.items.add(file);
            });

            fileInput.files = dt.files;

            // Debug: Check how many files are actually in the input
            console.log('Files in input:', fileInput.files.length);
        }

        document.addEventListener('DOMContentLoaded', function() {
            setupItemCodeValidation();
            setupItemCodeRealTimeValidation();
            setupRealTimeValidation();
            setupJanCDNumberOnly();
            setupSkuJanNumberOnly();
            setupSkuRealTimeValidation();

            // Your existing price formatting code
            document.querySelectorAll(".price-input").forEach(function(input) {
                input.addEventListener("input", function() {
                    this.value = this.value.replace(/[^\d]/g, "");
                    this.value = numberWithCommas(this.value);
                });

                if (input.value) {
                    input.value = numberWithCommas(input.value.replace(/[^\d]/g, ""));
                }
            });

            const itemNameField = document.querySelector('textarea[name="ItemName"]');
            if (itemNameField) {
                setupByteLimitValidation(itemNameField, 200);
            }

            const noteField = document.querySelector('textarea[name="Note"]');
            if (noteField) {
                setupByteLimitValidation(noteField, 500);
            }

            // Setup byte limit validation for MakerName
            const makerNameField = document.querySelector('input[name="MakerName"]');
            if (makerNameField) {
                setupByteLimitValidation(makerNameField, 100);
            }

            // Event listeners
            document.getElementById("addSkuBtn").addEventListener("click", function() {
                var skuModal = new bootstrap.Modal(document.getElementById("skuModal"));
                skuModal.show();
            });

            document.getElementById("addNewRowBtn").addEventListener("click", function() {
                document.querySelector("#skuPopupTable tbody").insertAdjacentHTML("beforeend", createSkuRow());
            });

            // Delete a row
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("deleteRow")) {
                    e.target.closest("tr").remove();
                }
            });

            // Save SKU popup
            // Save SKU popup
            document.getElementById("saveSkuBtn").addEventListener("click", function() {
                skuTempData = [];
                let hasError = false;

                // Clear previous errors
                document.querySelectorAll("#skuPopupTable .is-invalid").forEach(el => {
                    el.classList.remove("is-invalid");
                });

                // Hide all error messages initially
                document.querySelectorAll("#skuPopupTable .invalid-feedback").forEach(el => {
                    el.style.display = 'none';
                });

                document.querySelectorAll("#skuPopupTable tbody tr").forEach((row, index) => {
                    const sizeName = row.querySelector(".size-name");
                    const colorName = row.querySelector(".color-name");
                    const sizeCode = row.querySelector(".size-code");
                    const colorCode = row.querySelector(".color-code");
                    const janCode = row.querySelector(".jan");
                    const quantity = row.querySelector(".qty");
                    const rowNumber = index + 1;

                    let rowHasError = false;

                    // Validate all required fields
                    if (!sizeName.value.trim()) {
                        sizeName.classList.add("is-invalid");
                        rowHasError = true;
                    }

                    if (!colorName.value.trim()) {
                        colorName.classList.add("is-invalid");
                        rowHasError = true;
                    }

                    if (!sizeCode.value.trim()) {
                        sizeCode.classList.add("is-invalid");
                        rowHasError = true;
                    }

                    if (!colorCode.value.trim()) {
                        colorCode.classList.add("is-invalid");
                        rowHasError = true;
                    }

                    // Validate JAN code
                    if (!janCode.value.trim()) {
                        janCode.classList.add("is-invalid");
                        rowHasError = true;
                    } else if (!/^\d{13}$/.test(janCode.value.trim())) {
                        janCode.classList.add("is-invalid");
                        rowHasError = true;
                    }

                    // Validate quantity
                    if (!quantity.value && quantity.value !== 0) {
                        quantity.classList.add("is-invalid");
                        rowHasError = true;
                    }

                    if (rowHasError) {
                        hasError = true;

                        // Show error messages for invalid fields in this row
                        if (sizeName.classList.contains('is-invalid')) {
                            sizeName.nextElementSibling.style.display = 'block';
                        }
                        if (colorName.classList.contains('is-invalid')) {
                            colorName.nextElementSibling.style.display = 'block';
                        }
                        if (sizeCode.classList.contains('is-invalid')) {
                            sizeCode.nextElementSibling.style.display = 'block';
                        }
                        if (colorCode.classList.contains('is-invalid')) {
                            colorCode.nextElementSibling.style.display = 'block';
                        }
                        if (janCode.classList.contains('is-invalid')) {
                            janCode.nextElementSibling.style.display = 'block';
                        }
                        if (quantity.classList.contains('is-invalid')) {
                            quantity.nextElementSibling.style.display = 'block';
                        }
                    }

                    skuTempData.push({
                        Size_Name: sizeName.value.trim(),
                        Color_Name: colorName.value.trim(),
                        Size_Code: sizeCode.value.trim(),
                        Color_Code: colorCode.value.trim(),
                        JanCD: janCode.value.trim(),
                        Quantity: quantity.value,
                    });
                });

                if (hasError) {
                    // Scroll to first error
                    const firstError = document.querySelector("#skuPopupTable .is-invalid");
                    if (firstError) {
                        const errorCell = firstError.closest('td');
                        if (errorCell) {
                            errorCell.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    }
                    return; // Don't proceed if there are errors
                }

                // If no errors, proceed
                renderSkuMatrix();
                bootstrap.Modal.getInstance(document.getElementById("skuModal")).hide();
            });

            // Add real-time validation for SKU fields
            function setupSkuRealTimeValidation() {
                // Listen for input events in the SKU modal
                document.addEventListener('input', function(e) {
                    if (e.target.matches('#skuPopupTable .size-name, #skuPopupTable .color-name, #skuPopupTable .size-code, #skuPopupTable .color-code, #skuPopupTable .jan, #skuPopupTable .qty')) {
                        const field = e.target;

                        // Clear error when user starts typing
                        if (field.value.trim()) {
                            field.classList.remove('is-invalid');
                            if (field.nextElementSibling && field.nextElementSibling.classList.contains('invalid-feedback')) {
                                field.nextElementSibling.style.display = 'none';
                            }
                        }

                        // Special validation for JAN field
                        if (field.classList.contains('jan') && field.value.trim()) {
                            if (/^\d{13}$/.test(field.value.trim())) {
                                field.classList.remove('is-invalid');
                                if (field.nextElementSibling) {
                                    field.nextElementSibling.style.display = 'none';
                                }
                            }
                        }
                    }
                });

                // Validate on blur for better UX
                document.addEventListener('blur', function(e) {
                    if (e.target.matches('#skuPopupTable .size-name, #skuPopupTable .color-name, #skuPopupTable .size-code, #skuPopupTable .color-code, #skuPopupTable .jan, #skuPopupTable .qty')) {
                        const field = e.target;

                        if (!field.value.trim()) {
                            field.classList.add('is-invalid');
                            if (field.nextElementSibling && field.nextElementSibling.classList.contains('invalid-feedback')) {
                                field.nextElementSibling.style.display = 'block';
                            }
                        } else if (field.classList.contains('jan') && !/^\d{13}$/.test(field.value.trim())) {
                            field.classList.add('is-invalid');
                            if (field.nextElementSibling) {
                                field.nextElementSibling.style.display = 'block';
                            }
                        }
                    }
                }, true); // Use capture phase
            }

            // When main Save is clicked → convert to hidden JSON
            document.getElementById("saveItem").addEventListener("click", function(e) {
                e.preventDefault();

                // Check if save button is disabled due to duplicate item code
                if (this.disabled) {
                    alert('商品番号が重複しているため保存できません。別の商品番号を入力してください。');
                    document.querySelector('input[name="Item_Code"]').focus();
                    return false;
                }

                // Validate all fields
                if (!validateAllFields()) {
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                    return false;
                }

                let hasErrors = false;

                // Validate main JANCD
                const mainJanField = document.querySelector('input[name="JanCD"]');
                const mainJan = mainJanField.value.trim();
                if (!mainJan) {
                    mainJanField.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback validation-error';
                    errorDiv.textContent = 'JANコードは必須です';
                    mainJanField.parentNode.appendChild(errorDiv);
                    hasErrors = true;
                } else if (!/^\d{13}$/.test(mainJan)) {
                    mainJanField.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback validation-error';
                    errorDiv.textContent = '「JANコードは13桁です」';
                    mainJanField.parentNode.appendChild(errorDiv);
                    hasErrors = true;
                }

                // Validate SKU JAN codes
                // Validate SKU JAN codes
                let hasSkuJanErrors = false;
                skuTempData.forEach((sku, index) => {
                    if (sku.JanCD && !/^\d{13}$/.test(sku.JanCD)) {
                        hasSkuJanErrors = true;
                    }
                });

                if (hasSkuJanErrors) {
                    // Show SKU modal with inline errors
                    var skuModal = new bootstrap.Modal(document.getElementById("skuModal"));
                    skuModal.show();

                    // Mark invalid JAN fields
                    document.querySelectorAll("#skuPopupTable tbody tr").forEach((row, index) => {
                        const janField = row.querySelector(".jan");
                        const janValue = janField.value.trim();
                        if (janValue && !/^\d{13}$/.test(janValue)) {
                            janField.classList.add('is-invalid');
                            if (janField.nextElementSibling && janField.nextElementSibling.classList.contains('invalid-feedback')) {
                                janField.nextElementSibling.style.display = 'block';
                                janField.nextElementSibling.textContent = '「JANコードは13桁です」';
                            }
                        }
                    });

                    // Scroll to first error
                    const firstError = document.querySelector("#skuPopupTable .is-invalid");
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstError.focus();
                    }
                    return false;
                }

                // IMPORTANT: Update file input one more time before submission
                updateFileInput();

                console.log('Total files to be submitted:', document.getElementById('photoInput').files.length);

                // Prepare SKU data
                let sizes = [],
                    colors = [],
                    sizeCodes = [],
                    colorCodes = [],
                    jans = [],
                    qtys = [];

                skuTempData.forEach(item => {
                    sizes.push(item.Size_Name);
                    colors.push(item.Color_Name);
                    sizeCodes.push(item.Size_Code);
                    colorCodes.push(item.Color_Code);
                    jans.push(item.JanCD);
                    qtys.push(item.Quantity);
                });

                document.getElementById("skuSizeName").value = JSON.stringify(sizes);
                document.getElementById("skuColorName").value = JSON.stringify(colors);
                document.getElementById("skuSizeCode").value = JSON.stringify(sizeCodes);
                document.getElementById("skuColorCode").value = JSON.stringify(colorCodes);
                document.getElementById("skuJanCD").value = JSON.stringify(jans);
                document.getElementById("skuQuantity").value = JSON.stringify(qtys);

                // Create FormData
                const form = document.querySelector('form');
                const formData = new FormData(form);

                // Remove existing Photos[] entries
                formData.delete('Photos[]');

                // Clear the PhotoData hidden input first
                document.getElementById('hiddenPhotoData').value = '';

                // Create photoData array for JSON
                let photoDataForJson = [];

                // Add the new photos from photoData with their renamed names
                photoData.forEach(photo => {
                    // Get the user's input value from the rename input
                    const photoElement = document.querySelector(`[data-id="${photo.id}"]`);
                    let newName = '';

                    if (photoElement) {
                        const renameInput = photoElement.querySelector('.rename-input');
                        if (renameInput) {
                            newName = renameInput.value.trim();
                        }
                    }

                    // If no rename input or empty, use the original baseName without extension
                    if (!newName) {
                        newName = getFileNameWithoutExtension(photo.baseName);
                    }

                    // Store in JSON array for the hidden input
                    photoDataForJson.push({
                        id: photo.id,
                        oldName: photo.file.name,
                        newName: newName
                    });

                    // Add the actual file to FormData (with original name)
                    formData.append('Photos[]', photo.file);
                });

                // Update the hidden PhotoData input with JSON
                document.getElementById('hiddenPhotoData').value = JSON.stringify(photoDataForJson);

                // Submit the form normally (not with fetch)
                form.submit();

            });

            dropArea.addEventListener("click", () => {
                if (photoData.length < MAX_PHOTOS) {
                    fileInput.click();
                } else {
                    showPhotoLimitError();
                }
            });

            // CLICK → OPEN FILE DIALOG
            dropArea.addEventListener("drop", e => {
                if (photoData.length >= MAX_PHOTOS) {
                    showPhotoLimitError();
                    return;
                }

                const files = e.dataTransfer.files;
                const remainingSlots = MAX_PHOTOS - photoData.length;

                if (files.length > remainingSlots) {
                    showPhotoLimitError();
                    return;
                }

                handleFiles(files);
            });

            // SELECT FILES
            fileInput.addEventListener("change", function() {
                const files = this.files;
                const remainingSlots = MAX_PHOTOS - photoData.length;

                if (files.length > remainingSlots) {
                    showPhotoLimitError();
                    this.value = '';
                    return;
                }

                handleFiles(files);
                this.value = '';
            });

            // DRAG ENTER + HOVER
            ["dragenter", "dragover"].forEach(eventName => {
                dropArea.addEventListener(eventName, e => {
                    if (photoData.length >= MAX_PHOTOS) {
                        e.preventDefault();
                        return; // PREVENTS DRAG OVER WHEN LIMIT REACHED
                    }
                    e.preventDefault();
                    dropArea.classList.add("dragover");
                });
            });

            // DRAG LEAVE
            ["dragleave", "drop"].forEach(eventName => {
                dropArea.addEventListener(eventName, e => {
                    e.preventDefault();
                    dropArea.classList.remove("dragover");
                });
            });

            // Update RENAME PHOTO event to check duplicates
            document.addEventListener("input", e => {
                if (e.target.classList.contains("rename-input")) {
                    const id = e.target.dataset.id;
                    const newName = e.target.value;

                    const photo = photoData.find(x => x.id == id);
                    if (photo) {
                        photo.baseName = newName.trim();
                        updateHiddenInput();

                    }
                }
            });

            // Update DELETE PHOTO event to check duplicates
            document.addEventListener("click", e => {
                if (e.target.classList.contains("delete-photo")) {
                    const id = e.target.dataset.id;

                    // Find the photo to delete
                    const photoIndex = photoData.findIndex(x => x.id == id);
                    if (photoIndex !== -1) {
                        const fileName = photoData[photoIndex].file.name;

                        // Remove from photoData
                        photoData.splice(photoIndex, 1);

                        // Remove from allPhotoFiles
                        allPhotoFiles = allPhotoFiles.filter(file => file.name !== fileName);
                    }

                    // Re-render UI and update inputs
                    renderPhotos();
                    updateFileInput();
                    updateHiddenInput();

                    setTimeout(() => {}, 100);
                }
            });
        });
    </script>
</body>

</html>