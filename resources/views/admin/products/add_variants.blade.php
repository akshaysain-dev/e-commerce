@extends('layouts.backend')

@section('title', 'Add Variants')

@section('content')

<div class="container py-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">Add New Attribute</div>
                <div class="card-body">
                    <form action="{{ route('admin.attributes.store') }}" method="POST">
                        @csrf
                        <input type="text" name="name" class="form-control mb-2" placeholder="e.g. Color or Size">
                        <button type="submit" class="btn btn-primary w-100">Save Attribute</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">Add Value to Existing Attribute</div>
                <div class="card-body">
                    <form action="{{ route('admin.values.store') }}" method="POST">
                        @csrf
                        <select name="attribute_id" class="form-select mb-2">
                            @foreach($attributes as $attr)
                                <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="value" class="form-control mb-2" placeholder="e.g. Red or XL">
                        <button type="submit" class="btn btn-success w-100">Add Value</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <table class="table table-bordered bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>Attribute</th>
                        <th>Values</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attributes as $attr)
                    <tr>
                        <td><strong>{{ $attr->name }}</strong></td>
                        <td>
                            @foreach($attr->values as $val)
                                <span class="badge bg-secondary">{{ $val->value }}</span>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection