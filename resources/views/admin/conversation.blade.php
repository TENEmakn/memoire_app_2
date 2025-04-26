@extends('layouts.appadmin')

@section('content')
<div class="container py-4">
    <!-- En-tête avec informations de l'utilisateur -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.messages') }}" class="btn btn-outline-secondary me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="position-relative">
                <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center avatar">
                    <span>{{ substr($user->name, 0, 1) }}</span>
                </div>
                <span class="position-absolute bottom-0 end-0 status-dot bg-success"></span>
            </div>
            <div class="ms-3">
                <h4 class="mb-0">{{ $user->name }}</h4>
                <small class="text-muted">{{ $user->email }}</small>
            </div>
        </div>
    </div>

    <!-- Zone des messages -->
    <div class="messages-container mb-4" style="height: 60vh; overflow-y: auto;">
        @foreach($messages as $message)
            <div class="message {{ $message->sender_id === auth()->id() ? 'message-sent' : 'message-received' }}">
                <div class="message-content">
                    <div class="message-header">
                        <strong>{{ $message->sender_id === auth()->id() ? 'Vous' : $message->nomcomplet_sender }}</strong>
                        <small class="text-muted ms-2">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <div class="message-body">
                        <p class="mb-1"><strong>Sujet:</strong> {{ $message->sujet }}</p>
                        <p class="mb-0">{{ $message->message }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Formulaire de réponse -->
    <div class="reply-form">
        <form action="{{ route('admin.reply', $user->id) }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="sujet" class="form-label">Sujet</label>
                        <input type="text" class="form-control" id="sujet" name="sujet" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .messages-container {
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 15px;
    }

    .message {
        margin-bottom: 1rem;
        max-width: 80%;
    }

    .message-sent {
        margin-left: auto;
    }

    .message-received {
        margin-right: auto;
    }

    .message-content {
        padding: 1rem;
        border-radius: 15px;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .message-sent .message-content {
        background-color: #007bff;
        color: white;
    }

    .message-sent .message-content .text-muted {
        color: rgba(255,255,255,0.8) !important;
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

    .reply-form {
        position: sticky;
        bottom: 0;
        background-color: white;
        padding-top: 1rem;
        border-top: 1px solid #dee2e6;
    }
</style>
@endsection 