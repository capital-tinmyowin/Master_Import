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

    .existing-photo-card {
        border: 2px solid #28a745;
    }

    #photoDropArea.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #e9ecef;
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

    /* Create form parallel layout styles */
    .card-body .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 2.5rem;
    }

    .card-body .form-label {
        width: 82px;
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

    #skuPopupTable {
        /* border-collapse: separate; */
        border-spacing: 0 5px;
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
        height: 38px !important;
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

    /* Fix error message positioning for price card only */
    .price-card .card-body .form-group .invalid-feedback {
        position: absolute;
        top: 100%;
        left: 82px;
        /* Match the label width */
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

    .price-card .card-body .form-group .invalid-feedback {
        position: absolute;
        top: 100%;
        left: 0;
        width: 200px;
    }
</style>
    @include('navbar')

<div class="container mt-4">
    <h3 class="mb-3">Edit Item</h3>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <a href="{{ route('mitems.index') }}" class="btn btn-secondary mb-3">← Back</a>

    <form action="{{ route('mitems.update', $item->ID) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- FIRST ROW: Item Info + 注記 -->
        <div class="row g-3">
            <!-- Left: 商品番号 / JANCD / メーカー名 + 商品名 -->
            <div class="col-md-7 d-flex">
                <div class="card shadow-sm flex-fill">
                    <div class="card-body">
                        <!-- Parallel form groups for 商品番号 and JANCD -->
                        <div class="parallel-form-groups">
                            <div class="form-group">
                                <label class="form-label">商品番号</label>
                                <input type="text" name="Item_Code" class="form-control @error('Item_Code') is-invalid @enderror" value="{{ old('Item_Code', $item->Item_Code) }}" required disabled>
                                @error('Item_Code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">JANCD</label>
                                <input type="text" name="JanCD" class="form-control @error('JanCD') is-invalid @enderror" value="{{ old('JanCD', $item->JanCD) }}">
                                @error('JanCD') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Single form group for メーカー名 -->
                        <div class="form-group">
                            <label class="form-label">メーカー名</label>
                            <input type="text" name="MakerName" class="form-control @error('MakerName') is-invalid @enderror" value="{{ old('MakerName', $item->MakerName) }}" required>
                            @error('MakerName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Single form group for 商品名 -->
                        <div class="form-group textarea-form-group">
                            <label class="form-label">商品名</label>
                            <textarea name="ItemName" class="form-control @error('ItemName') is-invalid @enderror item-name-textarea" rows="3" required>{{ old('ItemName', $item->ITemName) }}</textarea>
                            @error('ITemName') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                                <textarea name="Note" class="form-control @error('Note') is-invalid @enderror" rows="10" style="flex: 1;">{{ old('Note', $item->Memo) }}</textarea>
                                @error('Note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SKU CARD -->
        <div class="row g-3 mt-3">
            <!-- SKU 登録 -->
            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title fw-bold">SKU 登録</h6>
                            <button type="button" class="btn btn-primary btn-sm" id="addSkuBtn">+ Add SKU</button>
                        </div>
                        <div id="skuPreviewContainer" class="mt-3">
                            @if($skus && count($skus) > 0)
                            <!-- Display existing SKUs as matrix -->
                            <table class="table table-bordered text-center">
                                <thead class="table-warning">
                                    <tr>
                                        <th>Color_Name</th>
                                        @php
                                        $sizes = $skus->unique('Size_Name')->pluck('Size_Name');
                                        @endphp
                                        @foreach($sizes as $size)
                                        <th>{{ $size }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $colors = $skus->unique('Color_Name')->pluck('Color_Name');
                                    @endphp
                                    @foreach($colors as $color)
                                    <tr>
                                        <td>{{ $color }}</td>
                                        @foreach($sizes as $size)
                                        @php
                                        $sku = $skus->where('Color_Name', $color)->where('Size_Name', $size)->first();
                                        @endphp
                                        <td>{{ $sku ? $sku->Quantity : 0 }}</td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                        <input type="hidden" name="sku[Size_Name]" id="skuSizeName">
                        <input type="hidden" name="sku[Color_Name]" id="skuColorName">
                        <input type="hidden" name="sku[Size_Code]" id="skuSizeCode">
                        <input type="hidden" name="sku[Color_Code]" id="skuColorCode">
                        <input type="hidden" name="sku[JanCD]" id="skuJanCD">
                        <input type="hidden" name="sku[Quantity]" id="skuQuantity">
                        <!-- <input type="hidden" name="sku[Size_Name]" id="skuSizeName" value="{{ old('sku.Size_Name') }}">
                        <input type="hidden" name="sku[Color_Name]" id="skuColorName" value="{{ old('sku.Color_Name') }}">
                        <input type="hidden" name="sku[Size_Code]" id="skuSizeCode" value="{{ old('sku.Size_Code') }}">
                        <input type="hidden" name="sku[Color_Code]" id="skuColorCode" value="{{ old('sku.Color_Code') }}">
                        <input type="hidden" name="sku[JanCD]" id="skuJanCD" value="{{ old('sku.JanCD') }}">
                        <input type="hidden" name="sku[Quantity]" id="skuQuantity" value="{{ old('sku.Quantity') }}"> -->
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100 price-card">
                    <div class="card-body">
                        <!-- Price fields with horizontal layout -->
                        <div class="form-group">
                            <label class="form-label">定価 (List Price)</label>
                            <div class="input-group">
                                <span class="input-group-text">¥</span>
                                <input name="ListPrice" id="ListPrice" class="form-control text-end price-input @error('ListPrice') is-invalid @enderror" value="{{ old('ListPrice', number_format($item->ListPrice)) }}" required>
                                @error('ListPrice') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">原価 (Sale Price)</label>
                            <div class="input-group">
                                <span class="input-group-text">¥</span>
                                <input name="SalePrice" id="SalePrice" class="form-control text-end price-input @error('SalePrice') is-invalid @enderror" value="{{ old('SalePrice', number_format($item->SalePrice)) }}" required>
                                @error('SalePrice') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PHOTO UPLOAD CARD -->
        <div class="row g-3 mt-3 photo-upload-card">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">商品写真 (Product Photos)</h6>

                        <!-- Existing Images - Display Horizontally -->
                        @if($existingImages && count($existingImages) > 0)
                        <div class="row g-3 mb-4" id="existingPhotosContainer">
                            @foreach($existingImages as $image)
                            <div class="col-md-3 existing-photo-item" data-id="{{ $image->ID }}">
                                <div class="photo-card existing-photo-card">
                                    <img src="{{ asset('uploads/items/' . $image->Image_Name) }}" alt="Item Photo" class="img-fluid">
                                    <!-- REMOVE rename input -->
                                    <button type="button" class="btn btn-danger btn-sm w-100 mt-2 delete-existing-photo" data-id="{{ $image->ID }}">Delete</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <input type="hidden" name="deleted_images" id="deletedImagesInput" value="">

                        <!-- New Photo Preview Grid -->
                        <div id="photoPreviewList" class="row mt-3 g-3 mb-3"></div>

                        <!-- Drag/Click Upload Area for New Photos -->
                        <div id="photoDropArea" class="p-4 border border-secondary rounded text-center bg-light" style="cursor:pointer;">
                            <p class="mb-1">📷 Click or Drag & Drop NEW photos here</p>
                            <small class="text-muted">Supported: JPG, PNG, WEBP — Multiple allowed (5 max total)</small>
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
        <input
            type="file"
            id="photoInput"
            name="Photos[]"
            multiple
            hidden>
        <button class="btn btn-warning float-end mt-3" style="margin-bottom: 40px;">Update</button>
    </form>

    <!-- SKU Adding Form Modal -->
    <div class="modal fade" id="skuModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Edit SKU</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <table class="table table-bordered text-center align-middle" id="skuPopupTable">
                        <thead style="background:#84C8FF; border-top: 1px solid #1212 !important">
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
                            @if($skus && count($skus) > 0)
                            @foreach($skus as $sku)
                            <tr>
                                <td><button type="button" class="btn btn-danger btn-sm deleteRow">Delete</button></td>

                                <!-- Size Name with validation -->
                                <td>
                                    <div>
                                        <input type="text" class="form-control size-name" value="{{ $sku->Size_Name }}" required>
                                        <div class="invalid-feedback">必須</div>
                                    </div>
                                </td>

                                <!-- Color Name with validation -->
                                <td>
                                    <div>
                                        <input type="text" class="form-control color-name" value="{{ $sku->Color_Name }}" required>
                                        <div class="invalid-feedback">必須</div>
                                    </div>
                                </td>

                                <!-- Size Code with validation -->
                                <td>
                                    <div>
                                        <input type="text" class="form-control size-code" value="{{ $sku->Size_Code }}" required>
                                        <div class="invalid-feedback">必須</div>
                                    </div>
                                </td>

                                <!-- Color Code with validation -->
                                <td>
                                    <div>
                                        <input type="text" class="form-control color-code" value="{{ $sku->Color_Code }}" required>
                                        <div class="invalid-feedback">必須</div>
                                    </div>
                                </td>

                                <!-- JAN Code with validation -->
                                <td>
                                    <div>
                                        <input type="text" class="form-control jan" value="{{ $sku->JanCD }}" required maxlength="13" style="width: 170px !important; text-align: center;">
                                        <div class="invalid-feedback">13桁の数字</div>
                                    </div>
                                </td>

                                <!-- Quantity Flag -->
                                <td>
                                    <div>
                                        <select class="form-select qty-flag" style="width: 95px !important;">
                                            <option value="完売" {{ $sku->Quantity == 0 ? 'selected' : '' }}>完売 (0)</option>
                                            <option value="手入力" {{ $sku->Quantity > 0 ? 'selected' : '' }}>手入力</option>
                                        </select>
                                    </div>
                                </td>

                                <!-- Quantity with validation -->
                                <td>
                                    <div>
                                        <input type="number" class="form-control qty" value="{{ $sku->Quantity }}" style="width: 90px !important; text-align: right;" required>
                                        <div class="invalid-feedback">必須</div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="addNewRowBtn">Add New Row</button>
                    <button type="button" class="btn btn-success" id="saveSkuBtn">Save</button>
                    <button type="button" class="btn btn-secondary" id="closePopup" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/sku.js"></script>
<script>
    let dropArea, fileInput, previewList;
    let skuTempData = [];
    let dbSkus = [];

    // Helper function to get file extension
    function getFileExtension(filename) {
        return filename.split('.').pop();
    }

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

                    // Clear errors when user starts typing
                    clearFieldErrors(janField);
                }
            });

            // Handle paste events
            janField.addEventListener('paste', function(e) {
                setTimeout(() => {
                    // Remove any non-digit characters from pasted content
                    janField.value = janField.value.replace(/\D/g, '');
                    previousValue = janField.value;
                    clearFieldErrors(janField);
                }, 0);
            });

            // Validate on blur (use same message as create form)
            janField.addEventListener('blur', function() {
                clearFieldErrors(this);
                const value = this.value.trim();

                if (!value) {
                    showFieldError(this, 'JANコードは必須です');
                } else if (!/^\d{13}$/.test(value)) {
                    showFieldError(this, '「JANコードは13桁です」');
                }
            });

            // Also validate when the page loads if there's a value
            if (janField.value.trim() && !/^\d{13}$/.test(janField.value.trim())) {
                showFieldError(janField, '「JANコードは13桁です」');
            }
        }
    }


    function validateAllFields() {
        let isValid = true;

        // Clear all previous errors first
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('.validation-error, .jan-validation-error').forEach(el => {
            el.remove();
        });

        // 1. Validate MakerName (required)
        const makerNameField = document.querySelector('input[name="MakerName"]');
        const makerNameValue = makerNameField.value.trim();
        if (!makerNameValue) {
            showFieldError(makerNameField, 'メーカー名は必須です');
            isValid = false;
        } else if (getByteLength(makerNameValue) > 100) {
            showFieldError(makerNameField, 'メーカー名は100バイト以内で入力してください');
            isValid = false;
        }

        // 2. Validate ItemName (required)
        const itemNameField = document.querySelector('textarea[name="ItemName"]');
        const itemNameValue = itemNameField.value.trim();
        if (!itemNameValue) {
            showFieldError(itemNameField, '商品名は必須です');
            isValid = false;
        } else if (getByteLength(itemNameValue) > 200) {
            showFieldError(itemNameField, '商品名は200バイト以内で入力してください');
            isValid = false;
        }

        // 3. Validate ListPrice (required)
        const listPriceField = document.querySelector('input[name="ListPrice"]');
        const listPriceValue = listPriceField.value.replace(/,/g, '');
        if (!listPriceValue || isNaN(listPriceValue) || parseFloat(listPriceValue) <= 0) {
            showFieldError(listPriceField, '有効な定価を入力してください');
            isValid = false;
        }

        // 4. Validate SalePrice (required)
        const salePriceField = document.querySelector('input[name="SalePrice"]');
        const salePriceValue = salePriceField.value.replace(/,/g, '');
        if (!salePriceValue || isNaN(salePriceValue) || parseFloat(salePriceValue) <= 0) {
            showFieldError(salePriceField, '有効な原価を入力してください');
            isValid = false;
        }

        // 5. Validate JANCD (required and must be 13 digits)
        const janField = document.querySelector('input[name="JanCD"]');
        const janValue = janField.value.trim();
        if (!janValue) {
            showFieldError(janField, 'JANコードは必須です');
            isValid = false;
        } else if (!/^\d{13}$/.test(janValue)) {
            showFieldError(janField, '「JANコードは13桁です」');
            isValid = false;
        }

        // 6. Validate Note byte limit
        const noteField = document.querySelector('textarea[name="Note"]');
        const noteValue = noteField.value.trim();
        if (noteValue && getByteLength(noteValue) > 500) {
            showFieldError(noteField, '注記は500バイト以内で入力してください');
            isValid = false;
        }

        return isValid;
    }

    function setupUnifiedValidation() {
        // const fields = document.querySelectorAll('input:not([type="file"]), textarea');

        // fields.forEach(field => {
        //     // Clone the field to remove all event listeners
        //     const newField = field.cloneNode(true);
        //     field.parentNode.replaceChild(newField, field);
        // });

        // MakerName validation
        const makerNameField = document.querySelector('input[name="MakerName"]');
        if (makerNameField) {
            makerNameField.addEventListener('blur', function() {
                clearFieldErrors(this);
                const value = this.value.trim();
                if (!value) {
                    showFieldError(this, 'メーカー名は必須です');
                } else if (getByteLength(value) > 100) {
                    showFieldError(this, 'メーカー名は100バイト以内で入力してください');
                }
            });
            makerNameField.addEventListener('input', function() {
                const value = this.value.trim();
                if (value && getByteLength(value) <= 100) {
                    clearFieldErrors(this);
                }
            });
        }

        // ItemName validation
        const itemNameField = document.querySelector('textarea[name="ItemName"]');
        if (itemNameField) {
            itemNameField.addEventListener('blur', function() {
                clearFieldErrors(this);
                const value = this.value.trim();
                if (!value) {
                    showFieldError(this, '商品名は必須です');
                } else if (getByteLength(value) > 200) {
                    showFieldError(this, '商品名は200バイト以内で入力してください');
                }
            });
            itemNameField.addEventListener('input', function() {
                const value = this.value.trim();
                if (value && getByteLength(value) <= 200) {
                    clearFieldErrors(this);
                }
            });
        }

        // Price validation
        const priceFields = document.querySelectorAll('.price-input');
        priceFields.forEach(field => {
            field.addEventListener('blur', function() {
                clearFieldErrors(this);
                const value = this.value.replace(/,/g, '');
                if (!value || isNaN(value) || parseFloat(value) <= 0) {
                    const fieldName = this.name === 'ListPrice' ? '定価' : '原価';
                    showFieldError(this, `有効な${fieldName}を入力してください`);
                }
            });
            field.addEventListener('input', function() {
                const value = this.value.replace(/,/g, '');
                if (value && !isNaN(value) && parseFloat(value) > 0) {
                    clearFieldErrors(this);
                }
            });
        });

        // JANCD validation
        const janField = document.querySelector('input[name="JanCD"]');
        if (janField) {
            janField.addEventListener('blur', function() {
                clearFieldErrors(this);
                const value = this.value.trim();
                if (!value) {
                    showFieldError(this, 'JANコードは必須です');
                } else if (!/^\d{13}$/.test(value)) {
                    showFieldError(this, '「JANコードは13桁です」');
                }
            });
            janField.addEventListener('input', function() {
                const value = this.value.trim();
                if (value && /^\d{13}$/.test(value)) {
                    clearFieldErrors(this);
                }
            });
        }

        // Note validation
        const noteField = document.querySelector('textarea[name="Note"]');
        if (noteField) {
            noteField.addEventListener('blur', function() {
                clearFieldErrors(this);
                const value = this.value.trim();
                if (value && getByteLength(value) > 500) {
                    showFieldError(this, '注記は500バイト以内で入力してください');
                }
            });
            noteField.addEventListener('input', function() {
                const value = this.value.trim();
                if (value && getByteLength(value) <= 500) {
                    clearFieldErrors(this);
                }
            });
        }
    }

    function clearFieldErrors(field) {
        field.classList.remove('is-invalid');
        const existingErrors = field.parentNode.querySelectorAll('.validation-error, .jan-validation-error');
        existingErrors.forEach(error => error.remove());
    }

    // Helper function to show field errors (prevent duplicates)
    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback validation-error';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    function setupRealTimeValidation() {
        // This function is now replaced by setupUnifiedValidation
        setupUnifiedValidation();
    }

    // Helper function to create FileList (not directly possible, so we use this workaround)
    function createFileList(files) {
        const dt = new DataTransfer();
        files.forEach(file => dt.items.add(file));
        return dt.files;
    }

    function updateFileInputWithPhotos() {
        const fileInput = document.getElementById('photoInput');

        if (!fileInput) {
            console.error('File input (#photoInput) not found in the DOM');
            return;
        }

        // Create a DataTransfer object
        const dataTransfer = new DataTransfer();

        // Add all files from photoData to DataTransfer
        photoData.forEach(photo => {
            if (photo.file instanceof File) {
                dataTransfer.items.add(photo.file);
            }
        });

        // Update the file input with the new files
        fileInput.files = dataTransfer.files;

        console.log('Files in input:', fileInput.files.length);

        // Debug: Verify files are in the input
        const files = fileInput.files;
        for (let i = 0; i < files.length; i++) {
            console.log(`File ${i}: ${files[i].name} (${files[i].size} bytes)`);
        }
    }
    document.querySelectorAll(".price-input").forEach(function(input) {

        // Only allow numbers
        input.addEventListener("input", function() {
            this.value = this.value.replace(/[^\d]/g, "");
            this.value = numberWithCommas(this.value);
        });

        // When screen loads, format initial values
        if (input.value) {
            input.value = numberWithCommas(input.value.replace(/[^\d]/g, ""));
        }
    });

    function numberWithCommas(x) {
        if (!x) return "";
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    <?php
    if (isset($skus) && count($skus) > 0) {
        echo "skuTempData = " . json_encode($skus->map(function ($sku) {
            return [
                'Size_Name' => $sku->Size_Name,
                'Color_Name' => $sku->Color_Name,
                'Size_Code' => $sku->Size_Code,
                'Color_Code' => $sku->Color_Code,
                'JanCD' => $sku->JanCD,
                'Quantity' => $sku->Quantity,
                'isExisting' => true // mark as database row
            ];
        })) . ";";
    }
    ?>



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
                <input type="text" class="form-control jan" required maxlength="13" style="text-align:center;">
                <div class="invalid-feedback">13桁の数字</div>
            </div>
        </td>
        <td>
            <div>
                <select class="form-select qty-flag">
                    <option value="完売">完売 (0)</option>
                    <option value="手入力" selected>手入力</option> <!-- Make this default for new rows -->
                </select>
            </div>
        </td>
        <td>
            <div>
                <input type="number" class="form-control qty" style="text-align: right;" value="0" required>
                <div class="invalid-feedback">必須</div>
            </div>
        </td>
    </tr>
`;
    }



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


    // Update hidden SKU inputs
    function updateSkuHiddenInputs() {
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
    }

    function renderSkuMatrix() {
        const container = document.getElementById("skuPreviewContainer");

        if (skuTempData.length === 0) {
            container.innerHTML = "";
            return;
        }

        // Extract unique size names
        let sizes = [...new Set(skuTempData.map(x => x.Size_Name))];
        // Extract unique color names
        let colors = [...new Set(skuTempData.map(x => x.Color_Name))];

        // Build table HTML
        let html = `
        <table class="table table-bordered text-center">
            <thead class="table-warning">
                <tr>
                    <th>Color_Name</th>
        `;

        // Add size columns
        sizes.forEach(size => {
            html += `<th>${size}</th>`;
        });

        html += `</tr></thead><tbody>`;

        // Build rows for each color
        colors.forEach(color => {
            html += `<tr><td>${color}</td>`;

            sizes.forEach(size => {
                let row = skuTempData.find(
                    x => x.Color_Name === color && x.Size_Name === size
                );
                html += `<td>${row ? row.Quantity : 0}</td>`;
            });

            html += `</tr>`;
        });

        html += "</tbody></table>";
        container.innerHTML = html;
    }

    function normalizeCode4Digits(value) {
        return value.toString().trim().padStart(4, "0");
    }

    // const existingSkus = window.existingSkus || []; // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener("input", function(e) {
            const row = e.target.closest("#skuPopupTable tbody tr");
            if (!row) return;

            row.dataset.isDirty = "1";
        });

        document.addEventListener("input", function(e) {
            if (e.target.classList.contains("size-code") || e.target.classList.contains("color-code")) {
                // Remove non-digit characters
                e.target.value = e.target.value.replace(/\D/g, '');
                // Limit to 4 digits
                if (e.target.value.length > 4) {
                    e.target.value = e.target.value.slice(0, 4);
                }
            }
        });
        document.getElementById("addSkuBtn").addEventListener("click", function() {
            const tbody = document.querySelector("#skuPopupTable tbody");
            tbody.innerHTML = ""; // Clear previous rows

            // Render only saved SKUs
            skuTempData.forEach((sku) => {
                // alert(sku.Size_Name);
                const tr = document.createElement("tr");
                tr.dataset.isExisting = sku.isExisting ? "1" : "0";
                tr.dataset.isDirty = "0";
                tr.innerHTML = `
                        <td><button type="button" class="btn btn-danger btn-sm deleteRow">Delete</button></td>
                        <td><input type="text" class="form-control size-name" value="${sku.Size_Name}"></td>
                        <td><input type="text" class="form-control color-name" value="${sku.Color_Name}"></td>
                        <td><input type="text" class="form-control size-code" value="${sku.Size_Code}"></td>
                        <td><input type="text" class="form-control color-code" value="${sku.Color_Code}"></td>
                        <td><input type="text" class="form-control jan" style="text-align:center" value="${sku.JanCD}"></td>
                        <td>
                            <select class="form-select qty-flag">
                                <option value="完売" ${sku.Quantity_Flag === '完売' ? 'selected' : ''}>完売 (0)</option>
                                <option value="手入力" ${sku.Quantity_Flag === '手入力' ? 'selected' : ''}>手入力</option>
                            </select>
                        </td>
                        <td><input type="number" class="form-control qty" value="${sku.Quantity}" style="text-align:right;"></td>
                    `;
                tbody.appendChild(tr);
            });

            // Show modal
            new bootstrap.Modal(document.getElementById("skuModal")).show();
        });

        document.getElementById("addNewRowBtn").addEventListener("click", function() {
            const tbody = document.querySelector("#skuPopupTable tbody");
            tbody.insertAdjacentHTML("beforeend", createSkuRow());
        });

        // Delete a row
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("deleteRow")) {
                e.target.closest("tr").remove();
            }
        });

        document.getElementById("closePopup").addEventListener("click", function() {
            const tbody = document.querySelector("#skuPopupTable tbody");
            if (tbody) {
                tbody.innerHTML = "";
            }

            // Remove only non-existing rows from skuTempData
            // skuTempData = skuTempData.filter(item => item.isExisting);
        });

        document.getElementById("saveSkuBtn").addEventListener("click", function() {
            //   skuTempData = [];
            const nextSkuTempData = [];
            const existingSkus = window.existingSkus || [];
            let hasError = false;

            const sizeCodeToName = {};
            const sizeNameToCode = {};
            const colorCodeToName = {};
            const colorNameToCode = {};
            const sizeColorSet = new Set();

            // alert("reach");
            existingSkus.forEach(sku => {
                const sc = normalizeCode4Digits(sku.Size_Code);
                const cc = normalizeCode4Digits(sku.Color_Code);

                sizeCodeToName[sc] = sku.Size_Name;
                sizeNameToCode[sku.Size_Name] = sc;
                colorCodeToName[cc] = sku.Color_Name;
                colorNameToCode[sku.Color_Name] = cc;
                sizeColorSet.add(`${sc}|${cc}`);
            });

            // Maps for validation
            const sizeCodeNameMap = {}; // { sizeCode: sizeName }
            const sizeColorCombinationSet = new Set(); // "sizeCode|colorCode"

            // Clear previous errors
            document.querySelectorAll("#skuPopupTable .is-invalid").forEach(el => {
                el.classList.remove("is-invalid");
            });

            // Hide all error messages initially
            document.querySelectorAll("#skuPopupTable .invalid-feedback").forEach(el => {
                el.style.display = 'none';
            });

            document.querySelectorAll("#skuPopupTable tbody tr").forEach((row, index) => {
                const isExisting = row.dataset.isExisting === "1";
                const isDirty = row.dataset.isDirty === "1";

                const sizeName = row.querySelector(".size-name");
                const colorName = row.querySelector(".color-name");
                const sizeCode = row.querySelector(".size-code");
                const colorCode = row.querySelector(".color-code");
                const janCode = row.querySelector(".jan");
                const quantity = row.querySelector(".qty");

                let rowHasError = false;

                // -------------------------
                // Required field validation
                // -------------------------
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

                // JAN validation
                if (!janCode.value.trim() || !/^\d{13}$/.test(janCode.value.trim())) {
                    janCode.classList.add("is-invalid");
                    rowHasError = true;
                }

                // Quantity validation
                if (!quantity.value && quantity.value !== 0) {
                    quantity.classList.add("is-invalid");
                    rowHasError = true;
                }

                // -------------------------
                // 🔥 FINAL STRICT SKU VALIDATION (CORRECT)
                // -------------------------
                const scRaw = sizeCode.value.trim();
                const ccRaw = colorCode.value.trim();

                const sc = normalizeCode4Digits(scRaw);
                const cc = normalizeCode4Digits(ccRaw);

                const sn = sizeName.value.trim();
                const cn = colorName.value.trim();

                // 1️⃣ SizeCode ↔ SizeName (GLOBAL)
                if (sc && sn) {
                    if (
                        (sizeCodeToName[sc] && sizeCodeToName[sc] !== sn) ||
                        (sizeNameToCode[sn] && sizeNameToCode[sn] !== sc)
                    ) {
                        sizeCode.classList.add("is-invalid");
                        sizeName.classList.add("is-invalid");
                        rowHasError = true;
                    }
                }

                // 2️⃣ ColorCode ↔ ColorName (GLOBAL)
                if (cc && cn) {
                    if (
                        (colorCodeToName[cc] && colorCodeToName[cc] !== cn) ||
                        (colorNameToCode[cn] && colorNameToCode[cn] !== cc)
                    ) {
                        colorCode.classList.add("is-invalid");
                        colorName.classList.add("is-invalid");
                        rowHasError = true;
                    }
                }

                // 3️⃣ SizeCode + ColorCode must be unique (GLOBAL)
                if (sc && cc) {
                    const key = `${sc}|${cc}`;
                    if (sizeColorSet.has(key)) {
                        sizeCode.classList.add("is-invalid");
                        colorCode.classList.add("is-invalid");
                        rowHasError = true;
                    }
                }

                // ✅ Register ONLY IF row is valid
                // if (!rowHasError) {
                //     sizeCodeToName[sc] = sn;
                //     sizeNameToCode[sn] = sc;
                //     colorCodeToName[cc] = cn;
                //     colorNameToCode[cn] = cc;
                //     sizeColorSet.add(`${sc}|${cc}`);
                // }


                // -------------------------
                // Show errors if any
                // -------------------------
                if (rowHasError) {
                    if (isExisting && !isDirty) {
                        nextSkuTempData.push({
                            Size_Name: sizeName.value.trim(),
                            Color_Name: colorName.value.trim(),
                            Size_Code: sc,
                            Color_Code: cc,
                            JanCD: janCode.value.trim(),
                            Quantity: quantity.value,
                            isExisting: true
                        });
                        return;
                    }

                    // Otherwise → block save
                    hasError = true;

                    row.querySelectorAll(".is-invalid").forEach(input => {
                        if (input.nextElementSibling) {
                            input.nextElementSibling.style.display = "block";
                        }
                    });
                    return;
                }

                if (!rowHasError || (isExisting && !isDirty)) {

                    // Register mappings ONLY if row is valid
                    if (!rowHasError) {
                        sizeCodeToName[sc] = sn;
                        sizeNameToCode[sn] = sc;
                        colorCodeToName[cc] = cn;
                        colorNameToCode[cn] = cc;
                        sizeColorSet.add(`${sc}|${cc}`);
                    }

                    nextSkuTempData.push({
                        Size_Name: sizeName.value.trim(),
                        Color_Name: colorName.value.trim(),
                        Size_Code: sc,
                        Color_Code: cc,
                        JanCD: janCode.value.trim(),
                        Quantity: quantity.value,
                        isExisting: isExisting
                    });
                }

            });

            // Scroll to first error
            if (hasError) {
                const firstError = document.querySelector("#skuPopupTable .is-invalid");
                if (firstError) {
                    firstError.closest("td")?.scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });
                }
                return;
            }
            skuTempData = nextSkuTempData;
            // Proceed if valid
            renderSkuMatrix();
            bootstrap.Modal.getInstance(document.getElementById("skuModal")).hide();
            document.querySelector("#skuPopupTable tbody").innerHTML = "";
        });

        dropArea = document.getElementById("photoDropArea");
        fileInput = document.getElementById("photoInput");
        previewList = document.getElementById("photoPreviewList");

        if (!fileInput) {
            console.error('photoInput not found');
            return;
        }

        // Initialize photo UI
        updatePhotoCountAndDropArea();

        // CLICK → OPEN FILE DIALOG
        dropArea.addEventListener("click", () => {
            if (!dropArea.classList.contains('disabled')) {
                fileInput.click();
            }
        });

        // SELECT FILES
        fileInput.addEventListener("change", function() {
            handleFiles(this.files);
        });

        // DRAG EVENTS
        ["dragenter", "dragover"].forEach(eventName => {
            dropArea.addEventListener(eventName, e => {
                if (!dropArea.classList.contains('disabled')) {
                    e.preventDefault();
                    dropArea.classList.add("dragover");
                }
            });
        });

        ["dragleave", "drop"].forEach(eventName => {
            dropArea.addEventListener(eventName, e => {
                e.preventDefault();
                dropArea.classList.remove("dragover");
            });
        });

        dropArea.addEventListener("drop", e => {
            e.preventDefault();
            if (!dropArea.classList.contains('disabled')) {
                handleFiles(e.dataTransfer.files);
            }
        });

        // Existing setup
        const itemNameField = document.querySelector('textarea[name="ItemName"]');
        if (itemNameField) {
            setupByteLimitValidation(itemNameField, 200);
        }

        const noteField = document.querySelector('textarea[name="Note"]');
        if (noteField) {
            setupByteLimitValidation(noteField, 500);
        }

        const makerNameField = document.querySelector('input[name="MakerName"]');
        if (makerNameField) {
            setupByteLimitValidation(makerNameField, 100);
        }

        document.querySelectorAll('.rename-existing-input').forEach(input => {
            const imageId = input.dataset.id;
            existingImageNames[imageId] = input.value.trim();
        });

        document.querySelector('form').addEventListener('submit', function(e) {

            // Remove commas
            document.querySelectorAll('.price-input').forEach(input => {
                input.value = input.value.replace(/,/g, '');
            });

            // Validate
            if (!validateAllFields()) {
                e.preventDefault();
                return false;
            }

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
            updateFileInput();

            console.log('Submitting files:', fileInput.files.length);
        });

        updatePhotoCountAndDropArea();
        renderSkuMatrix();
        updateSkuHiddenInputs();

        // New validation setup - use unified validation
        setupUnifiedValidation();
        setupJanCDNumberOnly();
        setupSkuJanNumberOnly();
        setupSkuRealTimeValidation();

    });

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
            }
        });
    }

    // Photo upload functionality - FIXED VERSION
    let photoData = [];
    let deletedImages = [];
    const MAX_PHOTOS = 5;

    // Show photo limit error
    function showPhotoLimitError() {
        const existingCount = document.querySelectorAll('.existing-photo-item').length;
        const newCount = photoData.length;
        const totalCount = existingCount + newCount;
        alert(`Error: Maximum ${MAX_PHOTOS} photos allowed. You already have ${totalCount} photos. Please delete some photos before adding more.`);
    }

    // Update photo count and drop area state
    function updatePhotoCountAndDropArea() {
        const existingCount = document.querySelectorAll('.existing-photo-item').length;
        const newCount = photoData.length;
        const totalCount = existingCount + newCount;

        if (totalCount >= MAX_PHOTOS) {
            dropArea.classList.add('disabled');
            dropArea.style.cursor = 'not-allowed';
            dropArea.innerHTML = `
            <p class="mb-1 text-muted">📷 Maximum ${MAX_PHOTOS} photos reached</p>
            <small class="text-muted">Delete photos to add more</small>
        `;
        } else {
            dropArea.classList.remove('disabled');
            dropArea.style.cursor = 'pointer';
            const remaining = MAX_PHOTOS - totalCount;
            dropArea.innerHTML = `
            <p class="mb-1">📷 Click or Drag & Drop NEW photos here</p>
            <small class="text-muted">Supported: JPG, PNG, WEBP — ${remaining} more allowed</small>
        `;
        }
    }

    function handleFiles(files) {
        const existingCount = document.querySelectorAll('.existing-photo-item').length;
        const totalCount = existingCount + photoData.length;
        const remainingSlots = MAX_PHOTOS - totalCount;

        if (files.length > remainingSlots) {
            showPhotoLimitError();
            return;
        }

        console.log('handleFiles called with', files.length, 'files');

        Array.from(files).forEach(file => {
            console.log('Processing file:', file.name, file.type, file.size);

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert(`Invalid file type: ${file.type}. Please select only image files.`);
                return;
            }

            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert(`File ${file.name} is too large (${(file.size / 1024 / 1024).toFixed(2)}MB). Maximum size is 5MB.`);
                return;
            }

            const id = Date.now() + Math.random();

            // Create preview using FileReader for better compatibility
            const reader = new FileReader();

            reader.onload = function(e) {
                console.log('FileReader loaded file:', file.name);

                photoData.push({
                    id: id,
                    file: file, // Keep the original File object
                    preview: e.target.result, // Use DataURL for preview
                    baseName: getFileNameWithoutExtension(file.name)
                });

                console.log('Added to photoData. Total:', photoData.length);

                // Update file input
                updateFileInput();

                // Render previews
                renderPhotos();

                // Update UI
                updatePhotoCountAndDropArea();
            };

            reader.onerror = function(e) {
                console.error('FileReader error:', e);
                alert(`Error reading file: ${file.name}`);
            };

            // Read the file as DataURL for preview
            reader.readAsDataURL(file);
        });
    }

    function getFileNameWithoutExtension(filename) {
        return filename.replace(/\.[^/.]+$/, "");
    }

    function updateFileInput() {
        if (!fileInput) {
            console.error('updateFileInput called but fileInput is null');
            return;
        }

        const dt = new DataTransfer();

        photoData.forEach(photo => {
            if (photo.file instanceof File) {
                dt.items.add(photo.file);
            }
        });

        fileInput.files = dt.files;

        console.log('Synced files to input:', fileInput.files.length);
    }


    // RENDER PHOTO PREVIEW GRID
    function renderPhotos() {
        console.log('renderPhotos called. photoData length:', photoData.length);

        previewList.innerHTML = "";

        if (photoData.length === 0) {
            console.log('No photos to render');
            return;
        }

        photoData.forEach((photo, index) => {
            console.log(`Rendering photo ${index}:`, photo.file.name);

            previewList.insertAdjacentHTML("beforeend", `
            <div class="col-md-3">
                <div class="photo-card">
                    <img src="${photo.preview}" alt="Preview" style="width:100%; height:160px; object-fit:cover;">
                    <button class="btn btn-danger btn-sm w-100 mt-2 delete-photo"
                            data-id="${photo.id}">Delete</button>
                </div>
            </div>
        `);
        });
    }

    // DELETE NEW PHOTO
    document.addEventListener("click", e => {
        if (e.target.classList.contains("delete-photo")) {
            const id = e.target.dataset.id;
            console.log('Deleting photo with id:', id);

            // Remove from photoData
            const initialLength = photoData.length;
            photoData = photoData.filter(x => x.id != id);
            console.log(`Removed photo. Before: ${initialLength}, After: ${photoData.length}`);

            // Update file input
            updateFileInput();

            renderPhotos();
            updatePhotoCountAndDropArea();
        }
    });

    // DELETE EXISTING PHOTO
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("delete-existing-photo")) {
            const imageId = e.target.dataset.id;

            // Add to deleted images array
            if (!deletedImages.includes(imageId)) {
                deletedImages.push(imageId);
            }

            // Remove from DOM
            e.target.closest('.existing-photo-item').remove();

            // Update hidden input
            document.getElementById('deletedImagesInput').value = JSON.stringify(deletedImages);

            // Update photo count and drop area state
            updatePhotoCountAndDropArea();
        }
    });
</script>