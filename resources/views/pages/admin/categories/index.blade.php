@extends('layouts.app')

@section('front-content')
    <div class="pagetitle">
        <h1>Categories Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Categories</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">All Categories</h5>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addCategoryModal">
                                <i class="bi bi-plus-circle"></i> Add New Category
                            </button>
                        </div>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Courses Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $category->name }}</strong></td>
                                        <td>{{ Str::limit($category->description, 50) }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $category->courses_count }}</span></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $category->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin.categories.update', $category->id) }}"
                                                method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5>Edit Category</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="text" name="name" class="form-control mb-3"
                                                            value="{{ $category->name }}" required>
                                                        <textarea name="description" class="form-control">{{ $category->description }}</textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Category</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Category Name</label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="e.g. Web Development">
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
