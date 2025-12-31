<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import System | Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 1500px;
            padding: 30px;
        }

        .links-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e1e5eb;
            margin-bottom: 30px;
        }

        .import-link {
            display: block;
            padding: 30px 35px;
            text-decoration: none;
            color: #2c3e50;
            font-size: 18px;
            font-weight: 500;
            border-bottom: 1px solid #eef2f7;
            transition: all 0.3s ease;
            position: relative;
        }

        .import-link:last-child {
            border-bottom: none;
        }

        .import-link:hover {
            background-color: #f8fafc;
            padding-left: 40px;
        }

        .import-link:active {
            background-color: #edf2f7;
        }

        .link-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .link-text {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .link-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3498db;
        }

        #itemMasterImport .link-icon {
            color: #4e73df;
            /* Blue for Item Master */
        }

        #skuMasterImport .link-icon {
            color: #1cc88a;
            /* Green for SKU Master */
        }

        .link-arrow {
            color: #bdc3c7;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .import-link:hover .link-arrow {
            color: #3498db;
            transform: translateX(5px);
        }

        #itemMasterImport:hover .link-arrow {
            color: #4e73df;
        }

        #skuMasterImport:hover .link-arrow {
            color: #1cc88a;
        }

        /* Welcome Message */
        .welcome-message {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
            text-align: center;
        }

        .welcome-message h2 {
            font-size: 1.5rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Logs Container */
        .logs-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e1e5eb;
            padding: 25px;
        }

        .logs-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .logs-header h3 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
        }



        /* Import type badges - force text align left */
        .import-type-badge {
            padding: 4px 1px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            min-width: 100px;
            text-align: left !important;
        }

        .import-type-badge {
            padding: 4px 1px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: flex-start !important;
            white-space: nowrap;
            min-width: 100px;
            width: 100%;
        }

        /* Basic table styles - for all tables */
        .table {
            border: 1px solid #dee2e6;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            text-align: center !important;
            vertical-align: middle;
            border-right: 1px solid #dee2e6;
            padding: 12px 8px;
        }

        .table th:last-child {
            border-right: none;
        }

        .table td {
            vertical-align: middle;
            text-align: center !important;
            border-right: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 8px;
        }

        .table td:last-child {
            border-right: none;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Add border radius to first and last cell in header */
        .table thead th:first-child {
            border-top-left-radius: 8px;
        }

        .table thead th:last-child {
            border-top-right-radius: 8px;
        }

        /* Add border radius to first and last cell in last row */
        .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 8px;
        }

        .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 8px;
        }

        /* For detail page tables */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        /* Make badges more compact and remove extra spacing */
        .import-type-badge {
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            min-width: 100px;
        }

        /* Adjust padding for better alignment */
        #importLogsTable th,
        #importLogsTable td {
            padding: 12px 8px;
        }

        /* Detail button */
        .detail-btn {
            padding: 4px 12px;
            font-size: 12px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 1px solid #6c757d;
        }

        .detail-btn:hover {
            background-color: #6c757d;
            color: white;
            border-color: #6c757d;
        }

        /* Center badges properly */
        td .import-type-badge {
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            width: fit-content;
        }

        /* Back button */
        .back-btn {
            margin-bottom: 20px;
        }

        /* Number links styling */
        .record-link {
            text-decoration: underline !important;
            color: #000 !important;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .record-link:hover {
            color: #0d6efd !important;
            text-decoration: underline !important;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 15px;
            }

            .logs-container {
                padding: 15px;
                overflow-x: auto;
            }

            .table {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px 15px;
            }

            .import-link {
                padding: 25px 20px;
            }

            .import-link:hover {
                padding-left: 25px;
            }

            .welcome-message {
                padding: 15px;
            }

            .welcome-message h2 {
                font-size: 1.3rem;
            }
        }

        /* =========================================== */
        /* SKU ALL RECORDS TABLE SPECIFIC STYLES */
        /* =========================================== */
        .sku-all-table {
            table-layout: fixed;
            width: 100%;
        }

        /* Column widths for SKU All Records */
        .sku-all-table th:nth-child(1),
        .sku-all-table td:nth-child(1) {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
            text-align: center !important;
        }

        .sku-all-table th:nth-child(2),
        .sku-all-table td:nth-child(2) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .sku-all-table th:nth-child(3),
        .sku-all-table td:nth-child(3) {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
            text-align: center !important;
        }

        .sku-all-table th:nth-child(4),
        .sku-all-table td:nth-child(4) {
            width: 90px;
            min-width: 90px;
            max-width: 90px;
            text-align: center !important;
        }

        .sku-all-table th:nth-child(5),
        .sku-all-table td:nth-child(5) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: left !important;
        }

        .sku-all-table th:nth-child(6),
        .sku-all-table td:nth-child(6) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: left !important;
        }

        .sku-all-table th:nth-child(7),
        .sku-all-table td:nth-child(7) {
            width: 140px;
            min-width: 140px;
            max-width: 140px;
            text-align: center !important;
        }

        .sku-all-table th:nth-child(8),
        .sku-all-table td:nth-child(8) {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
            text-align: right !important;
        }

        .sku-all-table th:nth-child(9),
        .sku-all-table td:nth-child(9) {
            width: 200px;
            min-width: 200px;
            max-width: 200px;
            text-align: left !important;
        }

        /* JanCD specific styles for SKU All Records */
        .sku-all-table .jancd-cell {
            position: relative;
            width: 140px !important;
            min-width: 140px !important;
            max-width: 140px !important;
        }

        .sku-all-table .truncated-text {
            display: inline-block;
            max-width: 130px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
            cursor: help;
        }

        /* Error message cell for SKU All Records */
        .sku-all-table .error-message-cell {
            position: relative;
            max-width: 200px;
        }

        .sku-all-table .truncated-error {
            display: inline-block;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
            cursor: help;
            font-size: 0.9em;
        }

        /* Quantity column formatting */
        .sku-all-table td.number-column {
            text-align: right !important;
            font-family: 'Courier New', monospace;
        }

        /* Prevent text wrapping in SKU All Records */
        .sku-all-table td {
            white-space: nowrap !important;
            overflow: hidden !important;
        }

        /* =========================================== */
        /* SKU SUCCESS RECORDS TABLE SPECIFIC STYLES */
        /* =========================================== */
        .sku-success-table {
            table-layout: fixed;
            width: 100%;
        }

        /* Column widths for SKU Success Records */
        .sku-success-table th:nth-child(1),
        .sku-success-table td:nth-child(1) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: center !important;
        }

        .sku-success-table th:nth-child(2),
        .sku-success-table td:nth-child(2) {
            width: 90px;
            min-width: 90px;
            max-width: 90px;
            text-align: center !important;
        }

        .sku-success-table th:nth-child(3),
        .sku-success-table td:nth-child(3) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .sku-success-table th:nth-child(4),
        .sku-success-table td:nth-child(4) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: center !important;
        }

        .sku-success-table th:nth-child(5),
        .sku-success-table td:nth-child(5) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: center !important;
        }

        .sku-success-table th:nth-child(6),
        .sku-success-table td:nth-child(6) {
            width: 140px;
            min-width: 140px;
            max-width: 140px;
            text-align: center !important;
        }

        .sku-success-table th:nth-child(7),
        .sku-success-table td:nth-child(7) {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
            text-align: center !important;
        }

        /* JanCD specific styles for SKU Success Records */
        .sku-success-table .jancd-cell {
            width: 140px !important;
            min-width: 140px !important;
            max-width: 140px !important;
        }

        /* =========================================== */
        /* SKU ERROR RECORDS TABLE SPECIFIC STYLES */
        /* =========================================== */
        .sku-error-table {
            table-layout: fixed;
            width: 100%;
        }

        /* Column widths for SKU Error Records */
        .sku-error-table th:nth-child(1),
        .sku-error-table td:nth-child(1) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: center !important;
        }

        .sku-error-table th:nth-child(2),
        .sku-error-table td:nth-child(2) {
            width: 90px;
            min-width: 90px;
            max-width: 90px;
            text-align: center !important;
        }

        .sku-error-table th:nth-child(3),
        .sku-error-table td:nth-child(3) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .sku-error-table th:nth-child(4),
        .sku-error-table td:nth-child(4) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: center !important;
        }

        .sku-error-table th:nth-child(5),
        .sku-error-table td:nth-child(5) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: center !important;
        }

        .sku-error-table th:nth-child(6),
        .sku-error-table td:nth-child(6) {
            width: 140px;
            min-width: 140px;
            max-width: 140px;
            text-align: center !important;
        }

        .sku-error-table th:nth-child(7),
        .sku-error-table td:nth-child(7) {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
            text-align: center !important;
        }

        .sku-error-table th:nth-child(8),
        .sku-error-table td:nth-child(8) {
            width: 200px;
            min-width: 200px;
            max-width: 200px;
            text-align: center !important;
        }

        /* JanCD specific styles for SKU Error Records */
        .sku-error-table .jancd-cell {
            width: 140px !important;
            min-width: 140px !important;
            max-width: 140px !important;
        }

        /* Error message cell for SKU Error Records */
        .sku-error-table .error-message-cell {
            position: relative;
            max-width: 200px;
        }

        /* =========================================== */
        /* ITEM MASTER TABLES STYLES */
        /* =========================================== */
        /* These will use default table styles */

        /* =========================================== */
        /* TOOLTIP STYLES (shared) */
        /* =========================================== */
        .custom-tooltip .tooltip-inner {
            max-width: 400px !important;
            text-align: left !important;
            white-space: pre-wrap !important;
            word-wrap: break-word !important;
            background-color: #333;
            font-size: 0.9em;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .custom-tooltip .tooltip-arrow {
            display: none !important;
        }

        /* Make tooltip appear above all content */
        .tooltip {
            z-index: 9999 !important;
        }

        /* =========================================== */
        /* ITEM MASTER TABLES STYLES */
        /* =========================================== */
        .item-master-table {
            table-layout: fixed;
            width: 100%;
        }

        /* Column widths for Item Master All Records (9 columns) */
        .item-master-table th:nth-child(1),
        .item-master-table td:nth-child(1) {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
            text-align: center !important;
        }

        .item-master-table th:nth-child(2),
        .item-master-table td:nth-child(2) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .item-master-table th:nth-child(3),
        .item-master-table td:nth-child(3) {
            width: 180px;
            min-width: 180px;
            max-width: 180px;
            text-align: left !important;
        }

        .item-master-table th:nth-child(4),
        .item-master-table td:nth-child(4) {
            width: 140px;
            min-width: 140px;
            max-width: 140px;
            text-align: center !important;
        }

        .item-master-table th:nth-child(5),
        .item-master-table td:nth-child(5) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: center !important;
        }

        .item-master-table th:nth-child(6),
        .item-master-table td:nth-child(6) {
            width: 150px;
            min-width: 150px;
            max-width: 150px;
            text-align: center !important;
        }

        .item-master-table th:nth-child(7),
        .item-master-table td:nth-child(7) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .item-master-table th:nth-child(8),
        .item-master-table td:nth-child(8) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .item-master-table th:nth-child(9),
        .item-master-table td:nth-child(9) {
            width: 200px;
            min-width: 200px;
            max-width: 200px;
            text-align: center !important;
        }

        /* Column widths for Item Master Success Records (7 columns) */
        .item-master-success-table {
            table-layout: fixed;
            width: 100%;
        }

        .item-master-success-table th:nth-child(1),
        .item-master-success-table td:nth-child(1) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .item-master-success-table th:nth-child(2),
        .item-master-success-table td:nth-child(2) {
            width: 180px;
            min-width: 180px;
            max-width: 180px;
            text-align: left !important;
        }

        .item-master-success-table th:nth-child(3),
        .item-master-success-table td:nth-child(3) {
            width: 140px;
            min-width: 140px;
            max-width: 140px;
            text-align: center !important;
        }

        .item-master-success-table th:nth-child(4),
        .item-master-success-table td:nth-child(4) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: left !important;
        }

        .item-master-success-table th:nth-child(5),
        .item-master-success-table td:nth-child(5) {
            width: 150px;
            min-width: 150px;
            max-width: 150px;
            text-align: center !important;
        }

        .item-master-success-table th:nth-child(6),
        .item-master-success-table td:nth-child(6) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .item-master-success-table th:nth-child(7),
        .item-master-success-table td:nth-child(7) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        /* Column widths for Item Master Error Records (8 columns) */
        .item-master-error-table {
            table-layout: fixed;
            width: 100%;
        }

        .item-master-error-table th:nth-child(1),
        .item-master-error-table td:nth-child(1) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .item-master-error-table th:nth-child(2),
        .item-master-error-table td:nth-child(2) {
            width: 180px;
            min-width: 180px;
            max-width: 180px;
            text-align: center !important;
        }

        .item-master-error-table th:nth-child(3),
        .item-master-error-table td:nth-child(3) {
            width: 140px;
            min-width: 140px;
            max-width: 140px;
            text-align: center !important;
        }

        .item-master-error-table th:nth-child(4),
        .item-master-error-table td:nth-child(4) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
            text-align: center !important;
        }

        .item-master-error-table th:nth-child(5),
        .item-master-error-table td:nth-child(5) {
            width: 150px;
            min-width: 150px;
            max-width: 150px;
            text-align: center !important;
        }

        .item-master-error-table th:nth-child(6),
        .item-master-error-table td:nth-child(6) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .item-master-error-table th:nth-child(7),
        .item-master-error-table td:nth-child(7) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
            text-align: center !important;
        }

        .item-master-error-table th:nth-child(8),
        .item-master-error-table td:nth-child(8) {
            width: 200px;
            min-width: 200px;
            max-width: 200px;
            text-align: center !important;
        }

        /* Text truncation and tooltip styles for Item Master */
        .item-master-table .truncated-text,
        .item-master-success-table .truncated-text,
        .item-master-error-table .truncated-text {
            display: inline-block;
            max-width: 95%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
            cursor: help;
        }

        /* Item Name cell */
        .item-name-cell {
            max-width: 180px !important;
            min-width: 180px !important;
        }

        /* JanCD cell */
        .item-jancd-cell {
            width: 140px !important;
            min-width: 140px !important;
            max-width: 140px !important;
        }

        /* Maker Name cell */
        .maker-name-cell {
            max-width: 120px !important;
            min-width: 120px !important;
        }

        /* Memo cell */
        .memo-cell {
            max-width: 150px !important;
            min-width: 150px !important;
        }

        /* Error Message cell */
        .item-error-message-cell {
            position: relative;
            max-width: 200px !important;
            min-width: 200px !important;
        }

        .item-master-table .truncated-error,
        .item-master-error-table .truncated-error {
            display: inline-block;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
            cursor: help;
        }

        /* Price columns formatting */
        .item-master-table td.price-column,
        .item-master-success-table td.price-column,
        .item-master-error-table td.price-column {
            text-align: right !important;
        }

        /* Prevent text wrapping in Item Master tables */
        .item-master-table td,
        .item-master-success-table td,
        .item-master-error-table td {
            white-space: nowrap !important;
            overflow: hidden !important;
        }

        .item-code-link {
            color: #0066cc !important;
            text-decoration: none !important;
            font-weight: 500;
            padding: 2px 4px;
            border-radius: 3px;
            transition: all 0.2s ease;
        }

        .item-code-link:hover {
            color: #004499 !important;
            text-decoration: underline !important;
            background-color: #f0f8ff;
        }

        /* For table cells containing the link */
        td .item-code-link {
            display: inline-block;
            min-width: 60px;
        }
    </style>
</head>

<body>
    <!-- Navbar (Your exact code) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <!-- Brand/Logo -->
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-warehouse me-2"></i>Import System
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Left Side: Navigation Menu -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mitems.index') }}">
                            <i class="fas fa-box me-1"></i> Item Master
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('import.sku-master') }}">
                            <i class="fas fa-tags me-1"></i> SKU Master
                        </a>
                    </li>
                </ul>

                <!-- Right Side: User Info & Logout -->
                <ul class="navbar-nav ms-auto">
                    <!-- User Info -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                            id="userDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="me-2">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ Auth::user()->name ?? 'User' }}</span>
                                <small class="text-light opacity-75">{{ Auth::user()->email ?? 'user@example.com' }}</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-cog me-2"></i> Profile Settings
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <!-- Logout Form -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Check if we're on detail page -->
            @if(isset($logDetail))
            <!-- Detail Page -->
            <div class="back-btn">
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Import Logs
                </a>
            </div>

            <div class="logs-container">
                <div class="logs-header">
                    <h3><i class="fas fa-info-circle me-2"></i>Import Log Details</h3>
                </div>

                <!-- Log Summary -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Import Log ID:</strong>
                                <div class="mt-1">{{ $logDetail->ImportLog_ID }}</div>
                            </div>
                            <div class="col-md-3">
                                <strong>Date:</strong>
                                <div class="mt-1">{{ $logDetail->Imported_Date->format('Y/m/d H:i:s') }}</div>
                            </div>
                            <div class="col-md-3">
                                <strong>Imported By:</strong>
                                <div class="mt-1">{{ $logDetail->Imported_By }}</div>
                            </div>
                            <div class="col-md-3">
                                <strong>Type:</strong>
                                <div class="mt-1">
                                    @if($logDetail->Import_Type == 1)
                                    <span class="import-type-badge import-type-master">Item_Master</span>
                                    @else
                                    <span class="import-type-badge import-type-sku">SKU</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Total Records:</strong>
                                <div class="mt-1">
                                    {{ $logDetail->Record_Count }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <strong>Error Records:</strong>
                                <div class="mt-1">
                                    {{ $logDetail->Error_Count }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <strong>Success Records:</strong>
                                <div class="mt-1">
                                    {{ $logDetail->Record_Count - $logDetail->Error_Count }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Check if showing specific type only (from records route) -->
                @if(isset($showType) && $showType == 'data')
                <h4 class="mb-3"><i class="fas fa-check-circle me-2 text-success"></i>Success Records ({{ $dataCount ?? 0 }})</h4>
                @elseif(isset($showType) && $showType == 'errors')
                <h4 class="mb-3"><i class="fas fa-exclamation-circle me-2 text-danger"></i>Error Records ({{ $errorCount ?? 0 }})</h4>
                @else
                <!-- Show ALL records in one table -->
                <!-- <h4 class="mb-3"><i class="fas fa-list me-2"></i>Import Records</h4> -->
                @endif

                <!-- Combined Table for All Records -->
                <div class="table-responsive">
                    @if(isset($showType) && $showType == 'data')
                    <!-- Show only success records -->
                    @if(isset($dataLogs) && count($dataLogs) > 0)
                    @if($logDetail->Import_Type == 1)
                    <!-- Item Master Success Records -->
                    <table class="table table-hover item-master-success-table">
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #dee2e6;">Item Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Item Name</th>
                                <th style="border-right: 1px solid #dee2e6;">JanCD</th>
                                <th style="border-right: 1px solid #dee2e6;">Maker Name</th>
                                <th style="border-right: 1px solid #dee2e6;">Memo</th>
                                <th style="border-right: 1px solid #dee2e6;">List Price</th>
                                <th>Sale Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataLogs as $data)
                            <tr>
                                <td style="text-align: center !important;">
                                    @if(isset($data->Item_Code) && !empty($data->Item_Code))
                                    @php
                                    // Find the item by Item_Code to get its ID
                                    $item = \App\Models\MItem::where('Item_Code', $data->Item_Code)->first();
                                    @endphp
                                    @if($item)
                                    <a href="{{ route('mitems.edit', $item->ID) }}" class="text-primary text-decoration-none fw-bold">
                                        {{ $item->Item_Code }}
                                    </a>
                                    @else
                                    {{ $data->Item_Code }}
                                    @endif
                                    @else
                                    {{ $data->Item_Code ?? '-' }}
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="item-name-cell">
                                    @php
                                    $itemName = $data->Item_Name ?? '-';
                                    $itemNameLength = strlen($itemName);
                                    @endphp
                                    @if($itemNameLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($itemName, ENT_QUOTES) }}">
                                        {{ substr($itemName, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $itemName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;" class="item-jancd-cell">
                                    @php
                                    $janCode = $data->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="maker-name-cell">
                                    @php
                                    $makerName = $data->MakerName ?? '-';
                                    $makerNameLength = strlen($makerName);
                                    @endphp
                                    @if($makerNameLength > 15)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($makerName, ENT_QUOTES) }}">
                                        {{ substr($makerName, 0, 15) }}...
                                    </span>
                                    @else
                                    <span>{{ $makerName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="memo-cell">
                                    @php
                                    $memo = $data->Memo ?? '-';
                                    $memoLength = strlen($memo);
                                    @endphp
                                    @if($memoLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($memo, ENT_QUOTES) }}">
                                        {{ substr($memo, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $memo }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $data->ListPrice ?? '-' }}
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $data->SalePrice ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination for Item Master Success Records -->
                    @if($dataLogs->hasPages())
                    <div class="mt-3">
                        {{ $dataLogs->links('pagination::bootstrap-5') }}
                    </div>
                    @endif

                    @else
                    <!-- SKU Success Records -->
                    <table class="table table-hover sku-success-table">
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #dee2e6;">Item Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Size Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Color Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Size Name</th>
                                <th style="border-right: 1px solid #dee2e6;">Color Name</th>
                                <th style="border-right: 1px solid #dee2e6; width: 140px;">JanCD</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataLogs as $data)
                            <tr>
                                <td style="text-align: center !important;">
                                    @if(isset($data->Item_Code) && !empty($data->Item_Code))
                                    @php
                                    // Find the item by Item_Code to get its ID
                                    $item = \App\Models\MItem::where('Item_Code', $data->Item_Code)->first();
                                    @endphp
                                    @if($item)
                                    <a href="{{ route('mitems.edit', $item->ID) }}" class="text-primary text-decoration-none fw-bold">
                                        {{ $item->Item_Code }}
                                    </a>
                                    @else
                                    {{ $data->Item_Code }}
                                    @endif
                                    @else
                                    {{ $data->Item_Code ?? '-' }}
                                    @endif
                                </td>
                                <td style="text-align: center !important;">{{ $data->Size_Code ?? '-' }}</td>
                                <td style="text-align: center !important;">{{ $data->Color_Code ?? '-' }}</td>
                                <td style="text-align: left !important;">{{ $data->Size_Name ?? '-' }}</td>
                                <td style="text-align: left !important;">{{ $data->Color_Name ?? '-' }}</td>
                                <td style="text-align: center !important;" class="jancd-cell">
                                    @php
                                    $janCode = $data->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="number-column">
                                    {{ number_format($data->Quantity ?? 0) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination for SKU Success Records -->
                    @if($dataLogs->hasPages())
                    <div class="mt-3">
                        {{ $dataLogs->links('pagination::bootstrap-5') }}
                    </div>
                    @endif

                    @endif
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No imported data found for this log.
                    </div>
                    @endif

                    @elseif(isset($showType) && $showType == 'errors')
                    <!-- Show only error records -->
                    @if(isset($errorLogs) && count($errorLogs) > 0)
                    @if($logDetail->Import_Type == 1)
                    <!-- Item Master Error Records -->
                    <table class="table table-hover item-master-error-table">
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #dee2e6;">Item Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Item Name</th>
                                <th style="border-right: 1px solid #dee2e6;">JanCD</th>
                                <th style="border-right: 1px solid #dee2e6;">Maker Name</th>
                                <th style="border-right: 1px solid #dee2e6;">Memo</th>
                                <th style="border-right: 1px solid #dee2e6;">List Price</th>
                                <th style="border-right: 1px solid #dee2e6;">Sale Price</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($errorLogs as $error)
                            <tr>
                                <td style="text-align: center !important;">{{ $error->Item_Code }}</td>
                                <td style="text-align: left !important;" class="item-name-cell">
                                    @php
                                    $itemName = $error->Item_Name ?? '-';
                                    $itemNameLength = strlen($itemName);
                                    @endphp
                                    @if($itemNameLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($itemName, ENT_QUOTES) }}">
                                        {{ substr($itemName, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $itemName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;" class="item-jancd-cell">
                                    @php
                                    $janCode = $error->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="maker-name-cell">
                                    @php
                                    $makerName = $error->MakerName ?? '-';
                                    $makerNameLength = strlen($makerName);
                                    @endphp
                                    @if($makerNameLength > 15)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($makerName, ENT_QUOTES) }}">
                                        {{ substr($makerName, 0, 15) }}...
                                    </span>
                                    @else
                                    <span>{{ $makerName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="memo-cell">
                                    @php
                                    $memo = $error->Memo ?? '-';
                                    $memoLength = strlen($memo);
                                    @endphp
                                    @if($memoLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($memo, ENT_QUOTES) }}">
                                        {{ substr($memo, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $memo }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $error->ListPrice ?? '-' }}
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $error->SalePrice ?? '-' }}
                                </td>
                                <td style="text-align: left !important;" class="item-error-message-cell">
                                    @if(strlen($error->Error_Msg) > 30)
                                    <span class="truncated-error" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($error->Error_Msg, ENT_QUOTES) }}">
                                        {{ substr($error->Error_Msg, 0, 30) }}...
                                    </span>
                                    @else
                                    <span>{{ $error->Error_Msg }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination for Item Master Error Records -->
                    @if($errorLogs->hasPages())
                    <div class="mt-3">
                        {{ $errorLogs->links('pagination::bootstrap-5') }}
                    </div>
                    @endif

                    @else
                    <!-- SKU Error Records -->
                    <table class="table table-hover sku-error-table">
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #dee2e6;">Item Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Size Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Color Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Size Name</th>
                                <th style="border-right: 1px solid #dee2e6;">Color Name</th>
                                <th style="border-right: 1px solid #dee2e6; width: 140px;">JanCD</th>
                                <th style="border-right: 1px solid #dee2e6;">Quantity</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($errorLogs as $error)
                            <tr>
                                <!-- Item Code -->
                                <td style="text-align: center !important;">
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ htmlspecialchars($error->Item_Code ?? '-', ENT_QUOTES) }}">
                                        {{ strlen($error->Item_Code ?? '-') > 15 ? substr($error->Item_Code, 0, 15).'...' : ($error->Item_Code ?? '-') }}
                                    </span>
                                </td>

                                <!-- Size Code -->
                                <td style="text-align: center !important;">
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ htmlspecialchars($error->Size_Code ?? '-', ENT_QUOTES) }}">
                                        {{ strlen($error->Size_Code ?? '-') > 10 ? substr($error->Size_Code, 0, 10).'...' : ($error->Size_Code ?? '-') }}
                                    </span>
                                </td>

                                <!-- Color Code -->
                                <td style="text-align: center !important;">
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ htmlspecialchars($error->Color_Code ?? '-', ENT_QUOTES) }}">
                                        {{ strlen($error->Color_Code ?? '-') > 10 ? substr($error->Color_Code, 0, 10).'...' : ($error->Color_Code ?? '-') }}
                                    </span>
                                </td>

                                <!-- Size Name -->
                                <td style="text-align: left !important;">
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ htmlspecialchars($error->Size_Name ?? '-', ENT_QUOTES) }}">
                                        {{ strlen($error->Size_Name ?? '-') > 15 ? substr($error->Size_Name, 0, 15).'...' : ($error->Size_Name ?? '-') }}
                                    </span>
                                </td>

                                <!-- Color Name -->
                                <td style="text-align: left !important;">
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ htmlspecialchars($error->Color_Name ?? '-', ENT_QUOTES) }}">
                                        {{ strlen($error->Color_Name ?? '-') > 15 ? substr($error->Color_Name, 0, 15).'...' : ($error->Color_Name ?? '-') }}
                                    </span>
                                </td>

                                <!-- JanCD -->
                                <td style="text-align: center !important;" class="jancd-cell">
                                    @php $janCode = $error->JanCD ?? '-'; @endphp
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ strlen($janCode) > 13 ? substr($janCode, 0, 13).'...' : $janCode }}
                                    </span>
                                </td>

                                <!-- Quantity -->
                                <td style="text-align: right !important;" class="number-column">
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ number_format($error->Quantity ?? 0) }}">
                                        {{ number_format($error->Quantity ?? 0) }}
                                    </span>
                                </td>

                                <!-- Error Message -->
                                <td style="text-align: left !important;" class="error-message-cell">
                                    @php $errorMsg = $error->Error_Msg ?? '-'; @endphp
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ htmlspecialchars($errorMsg, ENT_QUOTES) }}">
                                        {{ strlen($errorMsg) > 30 ? substr($errorMsg, 0, 30).'...' : $errorMsg }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>

                    <!-- Pagination for SKU Error Records -->
                    @if($errorLogs->hasPages())
                    <div class="mt-3">
                        {{ $errorLogs->links('pagination::bootstrap-5') }}
                    </div>
                    @endif

                    @endif
                    @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> No errors found for this import.
                    </div>
                    @endif

                    @else
                    <!-- Show ALL records in one table -->
                    @if(isset($combinedLogs) && $combinedLogs->count() > 0)
                    @if($logDetail->Import_Type == 1)
                    <!-- Item Master All Records -->
                    <table class="table table-hover item-master-table">
                        <thead>
                            <tr>
                                <th style="border-right: 1px solid #dee2e6;">Status</th>
                                <th style="border-right: 1px solid #dee2e6;">Item Code</th>
                                <th style="border-right: 1px solid #dee2e6;">Item Name</th>
                                <th style="border-right: 1px solid #dee2e6;">JanCD</th>
                                <th style="border-right: 1px solid #dee2e6;">Maker Name</th>
                                <th style="border-right: 1px solid #dee2e6;">Memo</th>
                                <th style="border-right: 1px solid #dee2e6;">List Price</th>
                                <th style="border-right: 1px solid #dee2e6;">Sale Price</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($combinedLogs as $record)
                            <tr>
                                <td style="text-align: center !important;">
                                    @if($record->status == 'success')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Success</span>
                                    @else
                                    <span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i> Error</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;">
                                    @if($record->status == 'success' && isset($record->Item_Code) && !empty($record->Item_Code))
                                    @php
                                    // Find the item by Item_Code to get its ID
                                    $item = \App\Models\MItem::where('Item_Code', $record->Item_Code)->first();
                                    @endphp
                                    @if($item)
                                    <a href="{{ route('mitems.edit', $item->ID) }}" class="text-primary text-decoration-none fw-bold">
                                        {{ $item->Item_Code }}
                                    </a>
                                    @else
                                    {{ $record->Item_Code }}
                                    @endif
                                    @else
                                    {{ $record->Item_Code ?? '-' }}
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="item-name-cell">
                                    @php
                                    $itemName = $record->Item_Name ?? '-';
                                    $itemNameLength = strlen($itemName);
                                    @endphp
                                    @if($itemNameLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($itemName, ENT_QUOTES) }}">
                                        {{ substr($itemName, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $itemName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;" class="item-jancd-cell">
                                    @php
                                    $janCode = $record->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="maker-name-cell">
                                    @php
                                    $makerName = $record->MakerName ?? '-';
                                    $makerNameLength = strlen($makerName);
                                    @endphp
                                    @if($makerNameLength > 15)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($makerName, ENT_QUOTES) }}">
                                        {{ substr($makerName, 0, 15) }}...
                                    </span>
                                    @else
                                    <span>{{ $makerName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;" class="memo-cell">
                                    @php
                                    $memo = $record->Memo ?? '-';
                                    $memoLength = strlen($memo);
                                    @endphp
                                    @if($memoLength > 20)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($memo, ENT_QUOTES) }}">
                                        {{ substr($memo, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $memo }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $record->ListPrice ?? '-' }}
                                </td>
                                <td style="text-align: right !important;" class="price-column">
                                    {{ $record->SalePrice ?? '-' }}
                                </td>
                                <td style="text-align: left !important;" class="item-error-message-cell">
                                    @if($record->status == 'error' && strlen($record->Error_Msg) > 30)
                                    <span class="truncated-error" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($record->Error_Msg, ENT_QUOTES) }}">
                                        {{ substr($record->Error_Msg, 0, 30) }}...
                                    </span>
                                    @elseif($record->status == 'error')
                                    <span>{{ $record->Error_Msg }}</span>
                                    @else
                                    <span class="truncated-error">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Single Pagination for Combined Item Master Records -->
                    @if($combinedLogs->hasPages())
                    <div class="mt-3">
                        {{ $combinedLogs->links('pagination::bootstrap-5') }}
                    </div>
                    @endif

                    @else
                    <!-- SKU All Records -->
                    <table class="table table-hover sku-all-table">
                        <thead>
                            <tr>
                                <th style="width: 80px; text-align: center !important;">Status</th>
                                <th style="width: 100px; text-align: center !important;">Item Code</th>
                                <th style="width: 80px; text-align: center !important;">Size Code</th>
                                <th style="width: 90px; text-align: center !important;">Color Code</th>
                                <th style="width: 120px; text-align: center !important;">Size Name</th>
                                <th style="width: 120px; text-align: center !important;">Color Name</th>
                                <th style="width: 120px; text-align: center !important;">JanCD</th>
                                <th style="width: 80px; text-align: center !important;">Quantity</th>
                                <th style="width: 200px; text-align: center !important;">Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($combinedLogs as $record)
                            <tr>
                                <td style="text-align: center !important;">
                                    @if($record->status == 'success')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Success</span>
                                    @else
                                    <span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i> Error</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;">
                                    @php
                                    $itemCode = $record->Item_Code ?? '-';
                                    @endphp

                                    @if(strlen($itemCode) > 15)
                                    <span class="truncated-text"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($itemCode, ENT_QUOTES) }}">
                                        {{ substr($itemCode, 0, 15) }}...
                                    </span>
                                    @else
                                    <span>{{ $itemCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;">
                                    @php
                                    $sizeCode = $record->Size_Code ?? '-';
                                    @endphp

                                    @if(strlen($sizeCode) > 10)
                                    <span class="truncated-text"
                                        data-bs-toggle="tooltip"
                                        data-bs-title="{{ htmlspecialchars($sizeCode, ENT_QUOTES) }}">
                                        {{ substr($sizeCode, 0, 10) }}...
                                    </span>
                                    @else
                                    <span>{{ $sizeCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;">
                                    @php
                                    $colorCode = $record->Color_Code ?? '-';
                                    @endphp

                                    @if(strlen($colorCode) > 10)
                                    <span class="truncated-text"
                                        data-bs-toggle="tooltip"
                                        data-bs-title="{{ htmlspecialchars($colorCode, ENT_QUOTES) }}">
                                        {{ substr($colorCode, 0, 10) }}...
                                    </span>
                                    @else
                                    <span>{{ $colorCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;">
                                    @php
                                    $sizeName = $record->Size_Name ?? '-';
                                    @endphp

                                    @if(strlen($sizeName) > 20)
                                    <span class="truncated-text"
                                        data-bs-toggle="tooltip"
                                        data-bs-title="{{ htmlspecialchars($sizeName, ENT_QUOTES) }}">
                                        {{ substr($sizeName, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $sizeName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: left !important;">
                                    @php
                                    $colorName = $record->Color_Name ?? '-';
                                    @endphp

                                    @if(strlen($colorName) > 20)
                                    <span class="truncated-text"
                                        data-bs-toggle="tooltip"
                                        data-bs-title="{{ htmlspecialchars($colorName, ENT_QUOTES) }}">
                                        {{ substr($colorName, 0, 20) }}...
                                    </span>
                                    @else
                                    <span>{{ $colorName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;" class="jancd-cell">
                                    @php
                                    $janCode = $record->JanCD ?? '-';
                                    $janLength = strlen($janCode);
                                    @endphp
                                    @if($janLength > 13)
                                    <span class="truncated-text" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($janCode, ENT_QUOTES) }}">
                                        {{ substr($janCode, 0, 13) }}...
                                    </span>
                                    @else
                                    <span>{{ $janCode }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right !important;" class="number-column">
                                    {{ number_format($record->Quantity ?? 0) }}
                                </td>
                                <td style="text-align: left !important;" class="error-message-cell">
                                    @if($record->status == 'error' && strlen($record->Error_Msg) > 30)
                                    <span class="truncated-error" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ htmlspecialchars($record->Error_Msg, ENT_QUOTES) }}">
                                        {{ substr($record->Error_Msg, 0, 30) }}...
                                    </span>
                                    @elseif($record->status == 'error')
                                    <span>{{ $record->Error_Msg }}</span>
                                    @else
                                    <span class="truncated-error">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Single Pagination for Combined SKU Records -->
                    @if($combinedLogs->hasPages())
                    <div class="mt-3">
                        {{ $combinedLogs->links('pagination::bootstrap-5') }}
                    </div>
                    @endif

                    @endif
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No import records found for this log.
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            @else
            <!-- Home Page with Import Logs -->
            <!-- Import Links -->
            <div class="links-container">
                <a href="{{ route('import.item-master') }}" class="import-link" id="itemMasterImport">
                    <div class="link-content">
                        <div class="link-text">
                            <span>Item Master Import</span>
                        </div>
                        <div class="link-arrow">→</div>
                    </div>
                </a>

                <a href="{{ route('import.sku-master') }}" class="import-link" id="skuMasterImport">
                    <div class="link-content">
                        <div class="link-text">
                            <span>SKU Master Import</span>
                        </div>
                        <div class="link-arrow">→</div>
                    </div>
                </a>
            </div>

            <!-- Import Logs Table -->
            <div class="logs-container">
                <div class="logs-header">
                    <h3><i class="fas fa-history me-2"></i>Import Logs</h3>
                </div>

                <div class="table-responsive">
                    <table id="importLogsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th style="text-align: center !important;"></th>
                                <th style="text-align: center !important;">日時</th>
                                <th style="text-align: center !important;">担当者</th>
                                <th style="text-align: center !important;">データ種別</th>
                                <th style="text-align: center !important;">データ件数</th>
                                <th style="text-align: center !important;">エラー件数</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($importLogs as $log)
                            <tr>
                                <td style="text-align: center !important;">
                                    <a href="{{ route('import-logs.detail', $log->ImportLog_ID) }}"
                                        class="btn btn-sm btn-outline-dark detail-btn">
                                        詳細
                                    </a>
                                </td>
                                <td style="text-align: center !important;">{{ $log->Imported_Date->format('Y/m/d H:i:s') }}</td>
                                <td style="text-align: center !important;">{{ $log->Imported_By }}</td>
                                <td style="text-align: center !important;">
                                    @if($log->Import_Type == 1)
                                    <span class="import-type-badge import-type-master">Item Master</span>
                                    @else
                                    <span class="import-type-badge import-type-sku">SKU</span>
                                    @endif
                                </td>
                                <td style="text-align: center !important;">
                                    @if($log->Record_Count - $log->Error_Count > 0)
                                    <a href="{{ route('import-logs.records', ['id' => $log->ImportLog_ID, 'type' => 'data']) }}"
                                        class="record-link" title="View successful records">
                                        {{ $log->Record_Count - $log->Error_Count }}
                                    </a>
                                    @else
                                    0
                                    @endif
                                </td>
                                <td style="text-align: center !important;">
                                    @if($log->Error_Count > 0)
                                    <a href="{{ route('import-logs.records', ['id' => $log->ImportLog_ID, 'type' => 'errors']) }}"
                                        class="record-link" title="View error records">
                                        {{ $log->Error_Count }}
                                    </a>
                                    @else
                                    0
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Import Logs Table -->
                @if($importLogs->hasPages())
                <div class="mt-3">
                    {{ $importLogs->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Simplified tooltip initialization
        function initializeAllTooltips() {
            // Initialize Bootstrap tooltips for all truncated text
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                // Check if tooltip already exists
                if (!tooltipTriggerEl._tooltip) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        delay: {
                            "show": 100,
                            "hide": 100
                        },
                        boundary: document.body
                    });
                }
            });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit for content to render
            setTimeout(initializeAllTooltips, 300);
        });

        // Minimal tooltip initialization
        $(document).ready(function() {
            // Wait for everything to load
             $(document).ready(function() {
                    setTimeout(function() {
                        $('[data-bs-toggle="tooltip"]').tooltip({
                            trigger: 'hover',
                            container: 'body',
                            boundary: 'window',
                            customClass: 'custom-tooltip'
                        });
                    }, 300);
                });
            setTimeout(function() {
                // Initialize all tooltips
                initializeAllTooltips();

                // Handle tooltip display on hover for truncated errors

            }, 1000);
        });
    </script>
</body>

</html>