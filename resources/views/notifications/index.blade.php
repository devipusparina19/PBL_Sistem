@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-2">Notifikasi</h2>
        <p class="text-muted fs-5">Semua notifikasi milestone Anda</p>
        <hr class="header-line mx-auto">
    </div>

    {{-- Tombol Mark All as Read --}}
    @if($notifications->where('is_read', false)->count() > 0)
        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-check-all"></i> Tandai Semua Dibaca
                </button>
            </form>
        </div>
    @endif

    {{-- Daftar Notifikasi --}}
    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            @forelse($notifications as $notification)
                <div class="notification-card {{ $notification->is_read ? 'read' : 'unread' }} mb-3 p-3 rounded-3">
                    <div class="d-flex gap-3 align-items-start">
                        {{-- Icon --}}
                        <div class="notification-icon-lg flex-shrink-0">
                            @if($notification->type === 'milestone_approved')
                                <i class="bi bi-check-circle-fill text-success fs-2"></i>
                            @elseif($notification->type === 'milestone_rejected')
                                <i class="bi bi-x-circle-fill text-danger fs-2"></i>
                            @elseif($notification->type === 'milestone_submitted')
                                <i class="bi bi-bell-fill text-primary fs-2"></i>
                            @else
                                <i class="bi bi-info-circle-fill text-info fs-2"></i>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0">
                                    @if($notification->type === 'milestone_approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($notification->type === 'milestone_rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @elseif($notification->type === 'milestone_submitted')
                                        <span class="badge bg-primary">Milestone Baru</span>
                                    @else
                                        <span class="badge bg-info">Notifikasi</span>
                                    @endif
                                </h6>
                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            
                            <p class="mb-2">{{ $notification->message }}</p>
                            
                            @if($notification->milestone)
                                <div class="d-flex gap-2 align-items-center">
                                    <a href="{{ auth()->user()->role === 'dosen' ? route('milestone.validasi') : route('milestone.view') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Lihat Milestone
                                    </a>
                                    
                                    @if(!$notification->is_read)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-check"></i> Tandai Dibaca
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="fs-5">Belum ada notifikasi</p>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if($notifications->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.header-line {
    width: 80px;
    height: 3px;
    background-color: #0d6efd;
    border-radius: 2px;
    margin-top: 10px;
}

.notification-card {
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.notification-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.notification-card.unread {
    background-color: #e7f3ff;
    border-left: 4px solid #0d6efd;
}

.notification-card.read {
    background-color: #ffffff;
}
</style>
@endsection
