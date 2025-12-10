    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Import Preview</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .preview-container {
                margin-top: 20px;
                /* Remove these two lines to eliminate vertical scroll */
                /* max-height: 410px; */
                /* overflow-y: auto; */
                border: 1px solid #dee2e6;
                border-radius: 5px;
            }

            .alert-warning {
                max-height: 100px;
            }

            .preview-table thead tr:first-child th {
                border-top: none;
            }

            .preview-table {
                font-size: 13px;
                margin-bottom: 0;
                table-layout: fixed;
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
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
                /* Remove top border to eliminate space */
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
                max-height: 50px;
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

            /* Optionally style the tooltip itself */
            .tooltip-inner {
                max-width: 300px !important;
                text-align: left !important;
            }

            /* Fixed column widths for Item Master */
            .col-row {
                width: 70px;
                min-width: 70px;
                max-width: 70px;
            }

            .col-item-code {
                width: 120px;
                min-width: 120px;
                max-width: 120px;
            }

            .col-item-name {
                width: 180px;
                min-width: 180px;
                max-width: 180px;
            }

            .col-jancd {
                width: 140px;
                min-width: 140px;
                max-width: 140px;
            }

            .col-maker-name {
                width: 150px;
                min-width: 150px;
                max-width: 150px;
            }

            .col-memo {
                width: 150px;
                min-width: 150px;
                max-width: 150px;
            }

            .col-list-price {
                width: 100px;
                min-width: 100px;
                max-width: 100px;
            }

            .col-sale-price {
                width: 100px;
                min-width: 100px;
                max-width: 100px;
            }

            .col-status {
                width: 250px;
                min-width: 250px;
                max-width: 250px;
            }

            /* Text alignment classes */
            .text-center {
                text-align: center !important;
            }

            .text-left {
                text-align: left !important;
            }

            .text-right {
                text-align: right !important;
            }

            /* Truncation for long text */
            .truncate {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: inline-block;
                max-width: 100%;
                vertical-align: middle;
            }

            /* Hover effects */
            .preview-table tbody tr:hover {
                background-color: rgba(0, 0, 0, 0.05) !important;
            }

            .row-error:hover {
                background-color: #f1c6cb !important;
            }

            .row-success:hover {
                background-color: #b9dfd0 !important;
            }

            /* Status cell styling */
            .status-cell {
                font-size: 12px;
                line-height: 1.4;
            }
        </style>
    </head>

    <body>
        @include('navbar')

        <div class="container mt-4">
            <h3 class="mb-4">
                <i class="fas fa-eye text-primary"></i> Import Preview
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
                            <p class="mb-0">{{ $totalRecords }}</p> <!-- Changed -->
                        </div>
                        <div class="col-md-2">
                            <h6><i class="fas fa-check-circle"></i> Valid Records</h6>
                            <p class="mb-0">{{ $totalRecords - $errorCount }}</p> <!-- Changed -->
                        </div>
                        <div class="col-md-3">
                            <h6><i class="fas fa-exclamation-circle"></i> Error Records</h6>
                            <p class="mb-0">{{ $errorCount }}</p> <!-- Changed -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="preview-container">
                <table class="table table-bordered table-hover preview-table">
                    <thead>
                        <tr>
                            <th class="col-row text-center">Row #</th>
                            <th class="col-item-code text-center">Item Code</th>
                            <th class="col-item-name text-center">Item Name</th>
                            <th class="col-jancd text-center">JAN Code</th>
                            <th class="col-maker-name text-center">Maker Name</th>
                            <th class="col-memo text-center">Memo</th>
                            <th class="col-list-price text-center">List Price</th>
                            <th class="col-sale-price text-center">Sale Price</th>
                            <th class="col-status text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previewData->items() as $item)
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

                        // Determine row class based on error status
                        if ($hasError) {
                        $rowClass = 'row-error';
                        } else {
                        $rowClass = 'row-success';
                        }

                        // Check for price issues
                        $listPriceIssue = $item['listprice'] == 0;
                        $salePriceIssue = $item['saleprice'] == 0;

                        // Determine status icon and text
                        if ($hasError) {
                        $statusIcon = '<i class="fas fa-exclamation-circle text-danger"></i>';
                        $status = 'Error';
                        } else {
                        $statusIcon = '<i class="fas fa-check-circle text-success"></i>';
                        $status = 'Valid';
                        }
                        @endphp

                        <tr class="{{ $rowClass }}">
                            <td class="col-row text-center">{{ $item['row_number'] }}</td>
                            <td class="col-item-code text-center">
                                <span class="truncate" style="max-width: 110px;">{{ $item['item_code'] }}</span>
                            </td>
                            <td class="col-item-name text-left">
                                <span class="truncate" style="max-width: 170px;">{{ $item['itemname'] }}</span>
                            </td>
                            <td class="col-jancd text-center">
                                <span class="truncate" style="max-width: 130px;">{{ $item['jancd'] ?? '' }}</span>
                            </td>
                            <td class="col-maker-name text-left">
                                <span class="truncate" style="max-width: 140px;">{{ $item['makername'] ?? '' }}</span>
                            </td>
                            <td class="col-memo text-left">
                                <span class="truncate" style="max-width: 140px;">{{ $item['memo'] ?? '' }}</span>
                            </td>
                            <td class="col-list-price text-right" @if($listPriceIssue) @endif>
                                {{ $item['listprice'] == 0 ? '0 (Invalid)' : number_format($item['listprice'], 2) }}
                            </td>
                            <td class="col-sale-price text-right" @if($salePriceIssue) @endif>
                                {{ $item['saleprice'] == 0 ? '0 (Invalid)' : number_format($item['saleprice'], 2) }}
                            </td>
                            <td class="col-status text-left status-cell">
                                <span style="vertical-align: middle;">{!! $statusIcon !!}</span>
                                @if($hasError && !empty($errorMessages))
                                <span class="error-tooltip ms-1"
                                    data-bs-toggle="tooltip"
                                    data-bs-html="true"
                                    title="{{ implode(' ', $errorMessages) }}">
                                    {{ $status }}: {{ implode(', ', array_slice($errorMessages, 0, 2)) }}{{ count($errorMessages) > 2 ? '...' : '' }}
                                </span>
                                @else
                                <span class="ms-1">{{ $status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Simple pagination (if you still want pagination without breaking logic) -->
            @if($previewData->hasPages()) <div class="mt-3">
                <nav aria-label="Preview pagination">
                    <ul class="pagination justify-content-end mb-0">
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
                        // Show limited page numbers
                        $currentPage = $previewData->currentPage();
                        $lastPage = $previewData->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                        @endphp

                        @if($startPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $previewData->url(1) }}">1</a>
                        </li>
                        @if($startPage > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                        @endif
                        @endif

                        @for($page = $startPage; $page <= $endPage; $page++)
                            @if($page==$currentPage)
                            <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $previewData->url($page) }}">{{ $page }}</a>
                            </li>
                            @endif
                            @endfor

                            @if($endPage < $lastPage)
                                @if($endPage < $lastPage - 1)
                                <li class="page-item disabled">
                                <span class="page-link">...</span>
                                </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $previewData->url($lastPage) }}">{{ $lastPage }}</a>
                                </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if($previewData->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $previewData->nextPageUrl() }}" rel="next">&raquo;</a>
                                </li>
                                @else
                                <li class="page-item disabled">
                                    <span class="page-link">&raquo;</span>
                                </li>
                                @endif
                    </ul>
                </nav>
            </div>
            @endif
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <form action="{{ route('item-import.preview.cancel') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </form>

                <div>
                    <form action="{{ route('item-import.preview.confirm') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success" id="confirmBtn">
                            <i class="fas fa-check"></i> Confirm Import
                        </button>
                    </form>
                </div>
            </div>
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

                // Add tooltip for truncated text
                $('.truncate').each(function() {
                    if (this.offsetWidth < this.scrollWidth) {
                        $(this).attr('data-bs-toggle', 'tooltip')
                            .attr('data-bs-title', $(this).text())
                            .attr('data-bs-placement', 'top');
                    }
                });

                // Reinitialize tooltips for truncated elements
                tooltipTriggerList = [].slice.call(document.querySelectorAll('.truncate[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                    if (!tooltipTriggerEl._tooltip) {
                        new bootstrap.Tooltip(tooltipTriggerEl);
                    }
                });

                $('#confirmBtn').click(function(e) {
                    e.preventDefault(); // Prevent default form submission

                    const totalRecords = {
                        {
                            $totalRecords
                        }
                    }; // Changed
                    const errorCount = {
                        {
                            $errorCount
                        }
                    }; // Changed
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

                    // Disable button and show loading
                    $(this).prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i> Importing...');

                    // Submit the form
                    $(this).closest('form').submit();
                });
            });
        </script>
    </body>

    </html>