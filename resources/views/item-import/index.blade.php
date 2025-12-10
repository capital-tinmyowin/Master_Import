<!DOCTYPE html>
<html lang="en">
<head>
    <title>Import Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <!-- Excel Import Row with Preview -->
        <form action="{{ route('item-import.preview.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="import-row">
                <div class="import-label">
                    Item_Master Excel Import
                </div>

                <input type="file" class="form-control form-control-sm" name="file" style="width:250px;" accept=".xlsx,.xls,.csv,.txt" required>

                <button type="submit" class="btn btn-light border import-button">
                    Preview
                </button>
            </div>
        </form>

        <!-- Direct CSV Import (no preview) -->
        <form action="{{ route('item-import.csv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="import-row">
                <div class="import-label">
                    Item_Master CSV Import (Direct)
                </div>

                <input type="file" class="form-control form-control-sm" name="file" style="width:250px;" accept=".csv,.txt" required>

                <button type="submit" class="btn btn-light border import-button">
                    インポート開始
                </button>
            </div>
        </form>

        <!-- <div class="mt-4">
            <a href="{{ route('mitems.index') }}" class="btn btn-outline-secondary">
                Back to Item List
            </a>
            <a href="{{ route('item-import.logs') }}" class="btn btn-outline-info ms-2">
                View Import Logs
            </a>
        </div> -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>