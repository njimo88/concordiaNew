@extends('layouts.app')

@section('content')

<div class="container">
    <h1>{{ isset($spectacle) ? 'Edit' : 'Create' }} Spectacle</h1>
    <form action="{{ isset($spectacle) ? route('spectacles.update', $spectacle->id_spectacle) : route('spectacles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($spectacle))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $spectacle->name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description', $spectacle->description ?? '') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="date">Date and Time:</label>
            <input type="datetime-local" name="date" class="form-control" 
                value="{{ old('date', \Carbon\Carbon::parse($spectacle->date)->format('Y-m-d\TH:i')) }}" required>
        </div>
        <div class="mb-3">
            <label for="total_seats" class="form-label">Total Seats</label>
            <input type="number" name="total_seats" id="total_seats" class="form-control" value="{{ old('total_seats', $spectacle->total_seats ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="state" class="form-label">State</label>
            <select name="state" id="state" class="form-select" required>
                <option value="active" {{ old('state', $spectacle->state ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('state', $spectacle->state ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" class="form-control">
            @if (isset($spectacle) && $spectacle->image)
                <img src="{{ asset('storage/' . $spectacle->image) }}" alt="Current Image" class="mt-3" style="max-width: 200px;">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($spectacle) ? 'Update' : 'Create' }}</button>
    </form>
</div>
   
@endsection

@section('test')
<h1>test content </h1>
@endsection