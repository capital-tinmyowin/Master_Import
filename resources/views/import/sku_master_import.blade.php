        <!DOCTYPE html>
        <html lang="en">

        <head>
            <title>SKU Master Import</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                .import-row {
                    display: flex;
                    align-items: center;
                    margin-bottom: 20px;
                }

                .import-label {
                    width: 230px;
                    font-size: 15px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .import-button {
                    margin-left: 40px;
                    width: 140px;
                }

                .preview-button {
                    background-color: #f8f9fa;
                    border-color: #6c757d;
                }

                .preview-button:hover {
                    background-color: #e9ecef;
                    border-color: #495057;
                }

                .error-message {
                    color: #dc3545;
                    font-size: 0.875em;
                    margin-top: 5px;
                    display: none;
                    margin-left: 50px;
                }

                .is-invalid {
                    border-color: #dc3545 !important;
                }
            </style>
        </head>

        <body>
            @include('navbar')

            <div class="container mt-5">
                <h3 class="mb-4">Import SKU Master</h3>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {!! nl2br(e(session('success'))) !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Excel Import Row with Preview -->
                <form id="excelImportForm" action="{{ route('sku-import.preview.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="import-row">
                        <div class="import-label">
                            <i class="fas fa-file-excel text-success"></i> SKU_Master Excel Import
                        </div>

                        <div style="width:250px;">
                            <input type="file" class="form-control form-control-sm" id="excelFile" name="file" accept=".xlsx,.xls" required>
                        </div>
                        <button type="submit" class="btn preview-button import-button">
                            インポート開始
                        </button>
                        <div id="excelError" class="error-message"></div>

                    </div>
                </form>

                <!-- CSV Import Row with Preview -->
                <form id="csvImportForm" action="{{ route('sku-import.preview.csv-process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="import-row">
                        <div class="import-label">
                            <i class="fas fa-file-csv text-info"></i> SKU_Master CSV Import
                        </div>

                        <div style="width:250px;">
                            <input type="file" class="form-control form-control-sm" id="csvFile" name="file" accept=".csv,.txt" required>
                        </div>
                        <button type="submit" class="btn preview-button import-button">
                            インポート開始
                        </button>
                        <div id="csvError" class="error-message"></div>
                    </div>
                </form>
                <div class="mt-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list"></i> Back to Item Main
                    </a>
                    <!-- <a href="{{ route('item-import.logs') }}" class="btn btn-outline-info ms-2">
                        <i class="fas fa-history"></i> View Import Logs
                    </a> -->
                </div>
                <!-- Format Information -->
                <!-- <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3"><i class="fas fa-table me-2"></i>Required Columns Format</h6>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge bg-success">SKU_Code</span>
                            <span class="badge bg-success">Item_Code</span>
                            <span class="badge bg-success">Size_Code</span>
                            <span class="badge bg-success">Color_Code</span>
                            <span class="badge bg-success">Barcode</span>
                            <span class="badge bg-success">Quantity</span>
                            <span class="badge bg-success">Price</span>
                        </div>
                        <p class="text-muted mb-0">
                            <small>Ensure your file contains all required columns in the correct order for successful import.</small>
                        </p>
                    </div> -->
            </div>

            <!-- Back Button -->

            <!-- <a href="{{ route('sku-import.logs') }}" class="btn btn-outline-info ms-2">
                        <i class="fas fa-history"></i> View Import Logs
                    </a> -->
            </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Auto-dismiss alerts after 5 seconds
                    setTimeout(() => {
                        const alerts = document.querySelectorAll('.alert');
                        alerts.forEach(alert => {
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        });
                    }, 5000);

                    // Validate file extension before form submission
                    const excelForm = document.getElementById('excelImportForm');
                    const csvForm = document.getElementById('csvImportForm');
                    const excelFileInput = document.getElementById('excelFile');
                    const csvFileInput = document.getElementById('csvFile');
                    const excelError = document.getElementById('excelError');
                    const csvError = document.getElementById('csvError');

                    // Valid file extensions
                    const validExcelExtensions = ['.xlsx', '.xls'];
                    const validCsvExtensions = ['.csv', '.txt'];

                    // Function to check file extension
                    function isValidFile(fileName, validExtensions) {
                        const extension = fileName.substring(fileName.lastIndexOf('.')).toLowerCase();
                        return validExtensions.includes(extension);
                    }

                    // Function to show error
                    function showError(inputElement, errorElement, message) {
                        inputElement.classList.add('is-invalid');
                        errorElement.textContent = message;
                        errorElement.style.display = 'block';
                    }

                    // Function to clear error
                    function clearError(inputElement, errorElement) {
                        inputElement.classList.remove('is-invalid');
                        errorElement.textContent = '';
                        errorElement.style.display = 'none';
                    }

                    // Excel form validation
                    excelForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        clearError(excelFileInput, excelError);

                        if (!excelFileInput.files || excelFileInput.files.length === 0) {
                            showError(excelFileInput, excelError, 'Please select an Excel file.');
                            return;
                        }

                        const file = excelFileInput.files[0];
                        const fileName = file.name;

                        if (!isValidFile(fileName, validExcelExtensions)) {
                            showError(excelFileInput, excelError,
                                'Please select a valid Excel file (.xlsx or .xls).');
                            return;
                        }

                        // // Check file size (10MB limit)
                        // const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                        // if (file.size > maxSize) {
                        //     showError(excelFileInput, excelError, 'File size must be less than 10MB.');
                        //     return;
                        // }

                        // All validations passed, submit the form
                        this.submit();
                    });

                    // CSV form validation
                    csvForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        clearError(csvFileInput, csvError);

                        if (!csvFileInput.files || csvFileInput.files.length === 0) {
                            showError(csvFileInput, csvError, 'Please select a CSV file.');
                            return;
                        }

                        const file = csvFileInput.files[0];
                        const fileName = file.name;

                        if (!isValidFile(fileName, validCsvExtensions)) {
                            showError(csvFileInput, csvError,
                                'Please select a valid CSV file (.csv or .txt).');
                            return;
                        }

                        // // Check file size (10MB limit)
                        // const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                        // if (file.size > maxSize) {
                        //     showError(csvFileInput, csvError, 'File size must be less than 10MB.');
                        //     return;
                        // }

                        // All validations passed, submit the form
                        this.submit();
                    });

                    // Clear errors when user selects a new file
                    excelFileInput.addEventListener('change', function() {
                        clearError(excelFileInput, excelError);

                        if (this.files.length > 0) {
                            const fileName = this.files[0].name;
                            const fileSize = (this.files[0].size / 1024).toFixed(2);
                            console.log(`Selected Excel file: ${fileName} (${fileSize} KB)`);

                            // Validate file type immediately
                            if (!isValidFile(fileName, validExcelExtensions)) {
                                showError(excelFileInput, excelError,
                                    'Please select a valid Excel file (.xlsx or .xls).');
                            }
                        }
                    });

                    csvFileInput.addEventListener('change', function() {
                        clearError(csvFileInput, csvError);

                        if (this.files.length > 0) {
                            const fileName = this.files[0].name;
                            const fileSize = (this.files[0].size / 1024).toFixed(2);
                            console.log(`Selected CSV file: ${fileName} (${fileSize} KB)`);

                            // Validate file type immediately
                            if (!isValidFile(fileName, validCsvExtensions)) {
                                showError(csvFileInput, csvError,
                                    'Please select a valid CSV file (.csv or .txt).');
                            }
                        }
                    });

                    // Add Bootstrap tooltips for file input hints
                    const fileInputs = document.querySelectorAll('input[type="file"]');
                    fileInputs.forEach(input => {
                        input.addEventListener('change', function() {
                            if (this.files.length > 0) {
                                const fileName = this.files[0].name;
                                const fileSize = (this.files[0].size / 1024).toFixed(2);
                                console.log(`Selected file: ${fileName} (${fileSize} KB)`);
                            }
                        });
                    });
                });
            </script>
        </body>

        </html>