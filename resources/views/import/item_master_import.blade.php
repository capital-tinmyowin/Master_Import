<!DOCTYPE html>
<html lang="en">

<head>
    <title>Import Items</title>
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
        <h3 class="mb-4">Import Items</h3>

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

        <!-- Excel form - Add id="excelImportForm" and error div -->
        <form id="excelImportForm" action="{{ route('item-import.preview.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="import-row">
                <div class="import-label">
                    <i class="fas fa-file-excel text-success"></i> Item_Master Excel Import
                </div>

                <!-- Wrap input in div and add id="excelFile" -->
                <div style="width:250px;">
                    <input type="file" class="form-control form-control-sm" id="excelFile" name="file" accept=".xlsx,.xls" required>
                </div>
                <button type="submit" class="btn preview-button import-button">
                    <i></i> インポート開始
                </button>
                <!-- Add error message div -->
                <div id="excelError" class="error-message"></div>
            </div>
        </form>

        <!-- CSV form - Add id="csvImportForm" -->
        <form id="csvImportForm" action="{{ route('item-import.preview.csv-process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="import-row">
                <div class="import-label">
                    <i class="fas fa-file-csv text-info"></i> Item_Master CSV Import
                </div>

                <!-- Wrap input in div and add id="csvFile" -->
                <div style="width:250px;">
                    <input type="file" class="form-control form-control-sm" id="csvFile" name="file" accept=".csv,.txt" required>
                </div>
                <button type="submit" class="btn preview-button import-button">
                    <i></i> インポート開始
                </button>
                <!-- Add error message div -->
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

                // All validations passed, submit the form
                this.submit();
            });

            // Clear errors when user selects a new file
            excelFileInput.addEventListener('change', function() {
                clearError(excelFileInput, excelError);

                if (this.files.length > 0) {
                    const fileName = this.files[0].name;
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
                    if (!isValidFile(fileName, validCsvExtensions)) {
                        showError(csvFileInput, csvError,
                            'Please select a valid CSV file (.csv or .txt).');
                    }
                }
            });
        });
    </script>
</body>

</html>