<!-- resources/views/modules/add.blade.php -->
@extends('layouts.app')

@section('content')
    <!-- Go Back Button -->
    <div class="form-group text-end mt-3">
        <a href="{{ url('/') }}" class="btn btn-secondary btn-sm">Go Back</a>
    </div>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow" style="width: 60%; padding: 20px;">
            <h2 class="text-center mb-4">Create New Module</h2>
            <form action="{{ route('modules.create') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="form-group mb-3">
                    <label for="type">Type</label>
                    <input type="text" class="form-control" id="type" name="type" required>
                </div>

                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="malfunction">Malfunction</option>
                    </select>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Save Module</button>
                </div>
            </form>

        </div>

    </div>
@endsection
