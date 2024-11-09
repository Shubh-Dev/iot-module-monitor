@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create New Module</h2>

        <form action="{{ route('modules.create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" class="form-control" id="type" name="type" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="malfunction">Malfunction</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Module</button>
        </form>
    </div>
@endsection
