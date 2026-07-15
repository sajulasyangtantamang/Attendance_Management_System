@extends('layouts.master')
@section('title', 'Add Department')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Add Department</h5>
        <form method="POST" action="{{ route('admin.departments.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Code</label>
                <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <button class="btn btn-primary">Save</button>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
