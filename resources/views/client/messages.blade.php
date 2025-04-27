@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- En-tête avec titre -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Mes Messages</h2>
    </div>

    <!-- Liste des conversations -->
    <div class="conversations-list">
        @forelse($conversations as $conversation)
            <a href="{{ route('client.conversation', $conversation->sujet) }}" class="text-decoration-none">
                <div class="card mb-3 message-card">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="position-relative">
                            <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center avatar">
                                <span>CGV</span>
                            </div>
                            <span class="position-absolute bottom-0 end-0 status-dot {{ $conversation->statut === 'non_lu' ? 'bg-danger' : 'bg-secondary' }}"></span>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-1 text-dark">CGV Motors</h5>
                                @php
                                        $user = Auth::user();
                                        $unreadMessagesCount = \App\Models\Message::where('sujet', $conversation->sujet)
                                            ->where('statut', 'non_lu')
                                            ->where('receiver_id', $user->id)
                                            ->count();
                                    @endphp
                                    @if($unreadMessagesCount > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $unreadMessagesCount }}</span>
                                @endif
                            </div>
                            <p class="mb-1 message-preview text-dark">
                                <strong>Sujet:</strong> {{ $conversation->sujet }}<br>
                                {{ Str::limit($conversation->message, 50) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $conversation->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-4">
                <p class="text-muted">Aucun message reçu</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    .card {
        border-radius: 15px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .message-card {
        cursor: pointer;
    }
    
    .message-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .avatar {
        width: 50px;
        height: 50px;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .message-preview {
        color: #666;
        font-size: 0.95rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .badge.bg-danger {
        animation: pulse 2s infinite;
    }
</style>
@endsection 