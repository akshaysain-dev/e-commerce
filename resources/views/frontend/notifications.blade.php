@extends('layouts.frontend')

@section('title', 'Notifications')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between mb-4">
        <h4>Notifications</h4>
        @if($notifications_main->count() > 0)
            <form action="{{ route('notifications.clearAll') }}" method="POST">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">Clear All</button>
            </form>
        @endif
    </div>

    @forelse($notifications_main as $notif)
        <div class="alert alert-light border shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $notif->title }}</strong><br>
                <small class="text-muted">{{ $notif->message }}</small>
            </div>
            <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST">
                @csrf @method('DELETE')
                <button class="btn-close" style="font-size: 0.8rem;"></button>
            </form>
        </div>
    @empty
        <p class="text-center">No notifications found.</p>
    @endforelse
</div>
@endsection