<!DOCTYPE html>
<html lang="en">

<head>
    <title>SKU Import Preview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .preview-container {
            margin-top: 20px;
            /* Remove max-height and overflow-y if you want full page scrolling */
            /* max-height: 410px; */
            /* overflow-y: auto; */
            border: 1px solid #dee2e6;
            border-radius: 5px;
            overflow-x: auto;
        }

        .preview-table {
            font-size: 13px;
            margin-bottom: 0;
            width: 100%;
            min-width: 1000px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .preview-table thead tr:first-child th {
            border-top: none;
        }

        .preview-table th {
            position: sticky;
            top: 0;
            background-color: #4a6491 !important;
            color: white !important;
            z-index: 10;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            padding: 8px 4px;
            border-right: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
            border-top: none;
        }

        .preview-table th:first-child {
            border-left: none;
        }

        .preview-table th:last-child {
            border-right: none;
        }

        .preview-table td {
            padding: 6px 4px;
            vertical-align: middle;
            border-right: 1px solid #dee2e6;
        }

        .preview-table td:last-child {
            border-right: none;
        }

        .row-error {
            background-color: #f8d7da !important;
        }

        .row-warning {
            background-color: #fff3cd !important;
        }

        .row-success {
            background-color: #d1e7dd !important;
        }

        .error-panel {
            max-height: 200px;
            overflow-y: auto;
            font-size: 12px;
        }

        .summary-card {
            border-left: 4px solid #0d6efd;
            margin-bottom: 20px;
        }

        /* Tooltip styles */
        .error-tooltip {
            cursor: pointer;
            display: inline-block;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }

        .error-full {
            font-size: 12px;
            line-height: 1.4;
        }

        .tooltip-inner {
            max-width: 300px !important;
            text-align: left !important;
        }

        /* Adjust column widths to be more flexible */
        .col-1 {
            width: 60px;
            min-width: 60px;
            max-width: 80px;
        }

        .col-2 {
            width: 100px;
            min-width: 100px;
            max-width: 120px;
        }

        .col-3 {
            width: 70px;
            min-width: 70px;
            max-width: 90px;
        }

        .col-4 {
            width: 120px;
            min-width: 120px;
            max-width: 150px;
        }

        .col-5 {
            width: 70px;
            min-width: 70px;
            max-width: 90px;
        }

        .col-6 {
            width: 120px;
            min-width: 120px;
            max-width: 150px;
        }

        .col-7 {
            width: 100px;
            min-width: 100px;
            max-width: 130px;
        }

        .col-8 {
            width: 70px;
            min-width: 70px;
            max-width: 90px;
        }

        .col-9 {
            width: 200px;
            min-width: 200px;
            max-width: 300px;
        }

        /* Responsive adjustments */
        @media (max-width: 1400px) {
            .preview-table {
                min-width: 900px;
            }

            .col-1 {
                max-width: 70px;
            }

            .col-2 {
                max-width: 110px;
            }

            .col-3 {
                max-width: 80px;
            }

            .col-4 {
                max-width: 130px;
            }

            .col-5 {
                max-width: 80px;
            }

            .col-6 {
                max-width: 130px;
            }

            .col-7 {
                max-width: 110px;
            }

            .col-8 {
                max-width: 80px;
            }

            .col-9 {
                max-width: 250px;
            }
        }

        @media (max-width: 1200px) {
            .preview-table {
                min-width: 1000px;
            }
        }

        .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            /* ← FIX */
            max-width: 150px;
            cursor: pointer;
            /* optional UX improvement */
        }
    </style>
</head>

<body>
    @include('navbar')

    <div class="container mt-4">
        <h3 class="mb-4">
            <i class="fas fa-eye text-primary"></i> SKU Import Preview
        </h3>
        <!-- Summary Card -->
        <div class="card summary-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6><i class="fas fa-file"></i> File Name</h6>
                        <p class="mb-0">{{ $fileName }}</p>
                    </div>
                    <div class="col-md-2">
                        <h6><i class="fas fa-file-alt"></i> File Type</h6>
                        <p class="mb-0">{{ strtoupper($fileType ?? 'excel') }}</p>
                    </div>
                    <div class="col-md-2">
                        <h6><i class="fas fa-list"></i> Total Records</h6>
                        <p class="mb-0">{{ $totalRecords }}</p>
                    </div>
                    <div class="col-md-2">
                        <h6><i class="fas fa-check-circle"></i> Valid Records</h6>
                        <p class="mb-0">{{ $totalRecords - $errorCount }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6><i class="fas fa-exclamation-circle"></i> Error Records</h6>
                        <p class="mb-0">{{ $errorCount }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Preview Table -->
        <div class="preview-container">
            <table class="table table-bordered table-hover preview-table">
                <thead>
                    <tr>
                        <th class="text-center">Row #</th>
                        <th class="text-center">Item Admin Code</th>
                        <th class="text-center">Item Code</th>
                        <th class="text-center">Size Code</th>
                        <th class="text-center">Color Code</th>

                        <th class="text-center">Size Name</th>
                        <th class="text-center">Color Name</th>
                        <th class="text-center">JanCD</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($previewData as $item)
                    @php
                    $hasError = false;
                    $errorMessages = [];
                    foreach ($errors as $error) {
                    if ($error['row'] == $item['row_number']) {
                    $hasError = true;
                    $errorMessages = $error['errors'];
                    break;
                    }
                    }

                    if ($hasError) {
                    $rowClass = 'row-error';
                    $status = 'Error';
                    $statusIcon = '<i class="fas fa-times-circle text-danger"></i>';
                    } else {
                    $rowClass = 'row-success';
                    $status = 'Valid';
                    $statusIcon = '<i class="fas fa-check-circle text-success"></i>';
                    }
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="text-center">{{ $item['row_number'] - 1 }}</td>
                        <td class="col-maker-name text-center">
                            <span class="truncate"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top" style="max-width: 130px; min-width: 130px;"
                                title="{{ $item['item_code'] ?? '' }}">
                                {{ $item['item_admin_code'] ?? '' }}
                            </span>
                        </td>
                        <td class="col-maker-name text-left">
                            <span class="truncate"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top" style="max-width: 100px; min-width: 100px;"
                                title="{{ $item['item_code'] ?? '' }}">
                                {{ $item['item_code'] ?? '' }}
                            </span>
                        </td>

                        <!-- <td class="col-2 text-center">{{ $item['item_code'] ?? '' }}</td> -->
                        <!-- <td class="col-3 text-center">{{ $item['size_code'] ?? '' }}</td> -->
                        <td class="col-maker-name text-center">
                            <span class="truncate"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top" style="max-width: 100px; min-width: 100px;"
                                title="{{ $item['size_code'] ?? '' }}">
                                {{ $item['size_code'] ?? '' }}
                            </span>
                        </td>
                        <td class="col-maker-name text-center">
                            <span class="truncate"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top" style="max-width: 100px; min-width: 100px;"
                                title="{{ $item['color_code'] ?? '' }}">
                                {{ $item['color_code'] ?? '' }}
                            </span>
                        </td>
                        <!-- <td class="col-4">{{ $item['size_name'] ?? '' }}</td> -->
                        <td class="col-maker-name text-left">
                            <span class="truncate"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top" style="max-width: 130px; min-width: 130px;"
                                title="{{ $item['size_name'] ?? '' }}">
                                {{ $item['size_name'] ?? '' }}
                            </span>
                        </td>


                        <!-- <td class="col-5 text-center">{{ $item['color_code'] ?? '' }}</td> -->
                        <!-- <td class="col-6">{{ $item['color_name'] ?? '' }}</td> -->
                        <td class="col-maker-name text-left">
                            <span class="truncate"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top" style="max-width: 130px; min-width: 130px;"
                                title="{{ $item['color_name'] ?? '' }}">
                                {{ $item['color_name'] ?? '' }}
                            </span>
                        </td>
                        <td class="col-maker-name text-center">
                            <span class="truncate"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top" style="max-width: 130px; min-width: 130px;"
                                title="{{ $item['jancd'] ?? '' }}">
                                {{ $item['jancd'] ?? '' }}
                            </span>
                        </td>
                        <!-- <td class="col-7 text-center">{{ $item['jancd'] ?? '' }}</td> -->
                        <td class="col-maker-name text-right">
                            <span class="truncate"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top" style="max-width: 100px; min-width: 80px; text-align: right;"
                                title="{{ $item['quantity'] ?? '' }}">
                                {{ $item['quantity'] ?? '' }}
                            </span>
                        </td>
                        <!-- <td class="col-8 text-end">{{ number_format($item['quantity'], 0) }}</td> -->
                        <td>
                            @php
                            $hasError = $item['Is_Valid'] == 0;
                            $statusIcon = $hasError
                            ? '<i class="fas fa-times-circle text-danger"></i>'
                            : '<i class="fas fa-check-circle text-success"></i>';
                            $statusText = $hasError ? 'Error' : 'Valid';
                            $statusMessage = $item['Status_Message']; // full SP error messages
                            @endphp

                            <div class="d-flex align-items-center">
                                <span>{!! $statusIcon !!}</span>
                                @if($hasError)
                                <span class="ms-1 error-tooltip truncate"
                                    data-bs-toggle="tooltip"
                                    data-bs-html="true"
                                    title="{{ $statusMessage }}">
                                    {{ \Illuminate\Support\Str::limit($statusMessage, 50) }}
                                </span>
                                @else
                                <span class="ms-1">{{ $statusText }}</span>
                                @endif
                            </div>

                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($previewData->hasPages())
        <div class="mt-3 d-flex justify-content-between align-items-center">
            {{-- Pagination info --}}
            <div class="small text-muted">
                Showing
                {{ $previewData->firstItem() }}
                @if ($previewData->lastItem() !== $previewData->firstItem())
                to {{ $previewData->lastItem() }}
                @endif
                of {{ $previewData->total() }}
            </div>

            {{-- Pagination links --}}
            <ul class="pagination mb-0">
                {{-- Previous Page Link --}}
                @if($previewData->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $previewData->previousPageUrl() }}" rel="prev">&laquo;</a>
                </li>
                @endif

                {{-- Pagination Elements --}}
                @php
                $currentPage = $previewData->currentPage();
                $lastPage = $previewData->lastPage();
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
                @endphp

                @if($startPage > 1)
                <li class="page-item"><a class="page-link" href="{{ $previewData->url(1) }}">1</a></li>
                @if($startPage > 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                @endif

                @for($page = $startPage; $page <= $endPage; $page++)
                    @if($page==$currentPage)
                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                    <li class="page-item"><a class="page-link" href="{{ $previewData->url($page) }}">{{ $page }}</a></li>
                    @endif
                    @endfor

                    @if($endPage < $lastPage)
                        @if($endPage < $lastPage - 1)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        <li class="page-item"><a class="page-link" href="{{ $previewData->url($lastPage) }}">{{ $lastPage }}</a></li>
                        @endif

                        {{-- Next Page Link --}}
                        @if($previewData->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $previewData->nextPageUrl() }}" rel="next">&raquo;</a>
                        </li>
                        @else
                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif
            </ul>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between mt-4">
            <form action="{{ route('sku-import.preview.cancel') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </form>

            <div>
                <form action="{{ route('sku-import.preview.confirm') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success" id="confirmBtn">
                        <i class="fas fa-check"></i> Confirm Import
                    </button>
                </form>
            </div>
        </div>

        <!-- Back Button -->
        <!-- <div class="mt-4">
            <a href="{{ route('sku-import.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Import Page
            </a>
        </div> -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    html: true,
                    boundary: 'window',
                    placement: 'top',
                    delay: {
                        show: 100,
                        hide: 100
                    }
                });
            });


            $('#confirmBtn').click(function(e) {
                e.preventDefault();

                const totalRecords = {
                    {
                        count($previewData)
                    }
                };
                const errorCount = {
                    {
                        count($errors)
                    }
                };
                const validCount = totalRecords - errorCount;

                if (errorCount > 0) {
                    if (!confirm(`There are ${errorCount} validation errors.\nOnly ${validCount} records will be imported.\n\nDo you want to continue?`)) {
                        return false;
                    }
                } else {
                    if (!confirm(`Are you sure you want to import ${totalRecords} records?`)) {
                        return false;
                    }
                }

                $(this).prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Importing...');

                $(this).closest('form').submit();
            });
        });
    </script>
</body>

</html>