<!DOCTYPE html>
<html>
<head>
    <title>Book List</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Book List</h1>

    <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Buttons -->
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('books.create') }}" class="btn btn-primary">Add New Book</a>
        <!-- Import button opens modal -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
            Import from Excel/CSV
        </button>
    </div>

    <!-- Book table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Published Date</th>
                    <th>Genre</th>
                    <th>Copies</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                <tr>
                    <td>{{ $book->BookId }}</td>
                    <td>{{ $book->Title }}</td>
                    <td>{{ $book->Author }}</td>
                    <td>{{ $book->ISBN }}</td>
                    <td>{{ \Carbon\Carbon::parse($book->PublishedDate)->format('Y-m-d') }}</td>
                    <td>{{ $book->Genre }}</td>
                    <td>{{ $book->NumberOfCopies }}</td>
                    <td>
                        @if($book->IsAvailable)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('books.edit', $book->BookId) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('books.destroy', $book->BookId) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Books from Excel/CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select File</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".csv, .xlsx" required>
                        <small class="text-muted">Accepted formats: .csv, .xlsx</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
