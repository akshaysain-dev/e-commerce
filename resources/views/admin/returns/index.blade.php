@extends('layouts.backend')

@section('title', 'Return Requests')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Return & Refund Requests</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $return)
                    <tr>
                        <td>#{{ $return->order_id }}</td>
                        <td>{{ $return->account_holder_name }}</td>
                        <td>
                            <span class="badge {{ $return->status == 'refunded' ? 'bg-success' : 'bg-warning' }}">
                                {{ strtoupper($return->status) }}
                            </span>
                        </td>
                        <td>{{ $return->created_at->format('d M, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.returns.show', $return->id) }}" class="btn btn-sm btn-primary">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $returns->links() }}
        </div>
    </div>
</div>
@endsection
