@extends('layouts.appadmin')

@section('content')
    <div class="container py-4">
        <!-- En-tête avec titre et bouton -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Messagerie</h2>
            <a href="#" class="btn btn-info text-white d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                <i class="fas fa-plus-circle me-2"></i>
                Créer une nouvelle conversation
            </a>
        </div>

        <!-- Barre de recherche -->
        <div class="search-box mb-4">
            <form action="{{ route('admin.messages') }}" method="GET">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher un message..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-info text-white">
                        Rechercher
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des conversations -->
        <div class="conversations-list">
            @forelse($messages as $message)
                <a href="{{ route('admin.conversation', $message->sender_id) }}" class="text-decoration-none">
                    <div class="card mb-3 message-card">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="position-relative">
                                <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center avatar">
                                    <span>{{ substr($message->nomcomplet_sender, 0, 1) }}</span>
                                </div>
                                <span class="position-absolute bottom-0 end-0 status-dot {{ $message->statut === 'non_lu' ? 'bg-danger' : 'bg-secondary' }}"></span>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-1 text-dark">{{ $message->nomcomplet_sender }}</h5>
                                    @php
                                        $user = Auth::user();
                                        $unreadMessagesCount = \App\Models\Message::where('statut', 'non_lu')
                                            ->where('receiver_id', $user->id)
                                            ->where('sender_id', $message->sender_id)
                                            ->count();
                                    @endphp
                                    @if($unreadMessagesCount > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $unreadMessagesCount }}</span>
                                @endif
                                </div>
                                <p class="mb-1 message-preview text-dark">
                                    <strong>Sujet:</strong> {{ $message->sujet }}<br>
                                    {{ Str::limit($message->message, 50) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ $message->email_sender }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $message->created_at->format('d/m/Y H:i') }}
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

    <!-- Modal pour nouveau message -->
    <div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newMessageModalLabel">Nouveau message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newMessageForm" action="{{ route('admin.send.message') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="messageType" id="allUsers" value="all" checked>
                                <label class="form-check-label" for="allUsers">
                                    Envoyer à tous les utilisateurs
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="messageType" id="singleUser" value="single">
                                <label class="form-check-label" for="singleUser">
                                    Envoyer à un seul utilisateur
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email de l'utilisateur</label>
                            <input type="email" class="form-control" id="userEmail" name="email" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Sujet</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-info text-white">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
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

        .search-box .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }

        .search-box .input-group-text {
            color: #6c757d;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        /* Animation pour les nouveaux messages */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .badge.bg-danger {
            animation: pulse 2s infinite;
        }
    </style>

    <!-- Ajout de Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Script pour gérer l'interaction du formulaire -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allUsersRadio = document.getElementById('allUsers');
            const singleUserRadio = document.getElementById('singleUser');
            const userEmailInput = document.getElementById('userEmail');

            function toggleEmailInput() {
                userEmailInput.disabled = allUsersRadio.checked;
                if (allUsersRadio.checked) {
                    userEmailInput.value = '';
                }
            }

            allUsersRadio.addEventListener('change', toggleEmailInput);
            singleUserRadio.addEventListener('change', toggleEmailInput);

            // Initial state
            toggleEmailInput();
        });
    </script>
@endsection



