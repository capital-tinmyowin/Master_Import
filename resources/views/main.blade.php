<!DOCTYPE html>
<html>
<head>
    <title>Main Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

@include('navbar')   <!-- Include navbar -->

<div class="container mt-4">
    <h4 class="mb-3 fw-bold">Master Data Import</h4>

    <div class="list-group">

        <a href="{{ route('mitems.index') }}" class="list-group-item list-group-item-action">
            ▶ <strong>Item Master Import</strong>
        </a>

        <a href="#" class="list-group-item list-group-item-action">
            ▶ <strong>SKU Master Import</strong>
        </a>

    </div>
</div>

</body>
</html>
