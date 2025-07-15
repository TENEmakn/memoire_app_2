<footer class="bg-info bg-opacity-25 py-5">
    <div class="container">
        <!-- Newsletter Section -->
        <div class="row mb-5">
            <div class="col-md-8 mx-auto text-center">
                <h5 class="text-info fw-bold mb-3">Restez informé</h5>
                <p class="text-muted mb-3">Inscrivez-vous à notre newsletter pour recevoir nos dernières offres et actualités</p>
                <form class="d-flex gap-2 justify-content-center">
                    <input type="email" class="form-control" style="max-width: 300px;" placeholder="Votre email">
                    <button type="submit" class="btn btn-info text-white">S'inscrire</button>
                </form>
            </div>
        </div>

        <div class="row align-items-start">
            <!-- Logo and Social Media -->
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="mb-3">
                    <img src="{{ asset('images/cgvmotors-logo.png') }}" alt="CGV Motors" class="footer-logo" style="max-height: 40px;">
                </div>
                <p class="text-muted mb-3" style="font-size: 0.9rem;">Votre partenaire de confiance pour l'achat et la location de véhicules de qualité.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="#" class="btn btn-dark rounded-circle p-2 social-icon" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="btn btn-dark rounded-circle p-2 social-icon" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-dark rounded-circle p-2 social-icon" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-dark rounded-circle p-2 social-icon" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="btn btn-dark rounded-circle p-2 social-icon" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Services -->
            <div class="col-md-3 mb-4 mb-md-0">
                <h6 class="text-info fw-bold mb-3">Nos Services</h6>
                <div class="d-flex flex-column">
                    <a href="#" class="text-dark text-decoration-none mb-2 footer-link">Véhicules disponibles</a>
                    <a href="#" class="text-dark text-decoration-none mb-2 footer-link">Location de véhicules</a>
                    <a href="#" class="text-dark text-decoration-none mb-2 footer-link">Vente de véhicules</a>
                    <a href="#" class="text-dark text-decoration-none mb-2 footer-link">Service maintenance</a>
                </div>
            </div>

            <!-- Information -->
            <div class="col-md-3 mb-4 mb-md-0">
                <h6 class="text-info fw-bold mb-3">Informations</h6>
                <div class="d-flex flex-column">
                    <a href="#" class="text-dark text-decoration-none mb-2 footer-link">À Propos</a>
                    <a href="{{ route('contact.show') }}" class="text-dark text-decoration-none mb-2 footer-link">Contact</a>
                    <a href="#" class="text-dark text-decoration-none mb-2 footer-link">FAQ</a>
                    <a href="#" class="text-dark text-decoration-none mb-2 footer-link">Blog</a>
                </div>
            </div>

            <!-- Legal -->
            <div class="col-md-3">
                <h6 class="text-info fw-bold mb-3">Légal</h6>
                <div class="d-flex flex-column">
                    <a href="{{ route('contrat.location') }}" class="text-dark text-decoration-none mb-2 footer-link">Contrat de location</a>
                    <a href="{{ route('contrat.vente') }}" class="text-dark text-decoration-none mb-2 footer-link">Contrat de vente</a>
                    <a href="#" class="text-dark text-decoration-none mb-2 footer-link">Politique de confidentialité</a>
                    <a href="#" class="text-dark text-decoration-none mb-2 footer-link">Conditions d'utilisation</a>
                </div>
            </div>
        </div>

        <!-- Separator -->
        <hr class="my-4 border-info opacity-25">

        <!-- Copyright -->
        <div class="row">
            <div class="col-12 text-center">
                <small class="text-muted">© 2025 CGV Motors. Tous droits réservés. Developed by Kone Tenemakan</small>
            </div>
        </div>
    </div>
</footer>

<!-- Chatbot for information -->
<!-- <div class="chat-bot-container" id="chatBotContainer">
    <div class="chat-bot-toggle" id="chatBotToggle">
        <i class="fas fa-comments"></i>
    </div>
    <div class="chat-bot-box" id="chatBotBox">
        <div class="chat-bot-header">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/cgvmotors-logo.png') }}" alt="CGV Motors" class="chatbot-logo me-2">
                <h6 class="m-0">Assistant</h6>
            </div>
            <button class="chat-close-btn" id="chatCloseBtn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-bot-messages" id="chatBotMessages">
            <div class="message bot-message">
                <p>Bonjour ! Comment puis-je vous aider aujourd'hui ?</p>
            </div>
        </div>
        <div class="chat-bot-input">
            <input type="text" id="chatBotInput" placeholder="Tapez votre message ici...">
            <button id="chatBotSend">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div> -->

<style>
    .social-icon {
        background-color: #0dcaf0 !important;
        border: none !important;
        color: white !important;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .social-icon:hover {
        transform: translateY(-5px);
        background-color: #0b5ed7 !important;
        box-shadow: 0 5px 15px rgba(13, 202, 240, 0.4);
    }
    
    .footer-link {
        font-size: 0.9rem;
        position: relative;
        transition: all 0.3s ease;
        padding-left: 0;
    }

    .footer-link:hover {
        color: #0dcaf0 !important;
        padding-left: 10px;
    }

    .footer-link::before {
        content: '';
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 0;
        height: 2px;
        background-color: #0dcaf0;
        transition: all 0.3s ease;
    }

    .footer-link:hover::before {
        width: 100%;
    }

    /* Chatbot Styles */
    .chat-bot-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
    }

    .chat-bot-toggle {
        width: 60px;
        height: 60px;
        background-color: #0dcaf0;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 1.5rem;
    }

    .chat-bot-toggle:hover {
        background-color: #0b5ed7;
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(13, 202, 240, 0.4);
    }

    .chat-bot-box {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 350px;
        height: 450px;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        display: none;
        flex-direction: column;
        overflow: hidden;
    }

    .chat-bot-header {
        background-color: #0dcaf0;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-close-btn {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 1.2rem;
    }

    .chat-bot-messages {
        flex-grow: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .message {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 5px;
    }

    .message p {
        margin: 0;
    }

    .bot-message {
        background-color: #f1f1f1;
        align-self: flex-start;
        border-bottom-left-radius: 5px;
    }

    .user-message {
        background-color: #0dcaf0;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 5px;
    }

    .chat-bot-input {
        display: flex;
        padding: 10px 15px;
        border-top: 1px solid #eee;
    }

    .chat-bot-input input {
        flex-grow: 1;
        border: none;
        padding: 10px;
        border-radius: 20px;
        background-color: #f5f5f5;
    }

    .chat-bot-input input:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(13, 202, 240, 0.3);
    }

    .chat-bot-input button {
        background-color: #0dcaf0;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-left: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .chat-bot-input button:hover {
        background-color: #0b5ed7;
    }

    @media (max-width: 768px) {
        .chat-bot-toggle {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }

        .chat-bot-box {
            width: 300px;
            height: 400px;
            bottom: 70px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatBotToggle = document.getElementById('chatBotToggle');
        const chatBotBox = document.getElementById('chatBotBox');
        const chatCloseBtn = document.getElementById('chatCloseBtn');
        const chatBotInput = document.getElementById('chatBotInput');
        const chatBotSend = document.getElementById('chatBotSend');
        const chatBotMessages = document.getElementById('chatBotMessages');
        
        // Toggle chat box
        chatBotToggle.addEventListener('click', function() {
            chatBotBox.style.display = 'flex';
            chatBotToggle.style.display = 'none';
        });
        
        // Close chat box
        chatCloseBtn.addEventListener('click', function() {
            chatBotBox.style.display = 'none';
            chatBotToggle.style.display = 'flex';
        });
        
        // Send message function
        function sendMessage() {
            const message = chatBotInput.value.trim();
            if (message === '') return;
            
            // Add user message
            addMessage(message, 'user');
            chatBotInput.value = '';
            
            // Process the message and get bot response
            setTimeout(() => {
                processMessage(message);
            }, 500);
        }
        
        // Send message on button click
        chatBotSend.addEventListener('click', sendMessage);
        
        // Send message on Enter key
        chatBotInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
        
        // Add message to chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.classList.add(sender + '-message');
            
            const messagePara = document.createElement('p');
            messagePara.textContent = text;
            
            messageDiv.appendChild(messagePara);
            chatBotMessages.appendChild(messageDiv);
            
            // Scroll to bottom
            chatBotMessages.scrollTop = chatBotMessages.scrollHeight;
        }
        
        // Process message and generate response
        function processMessage(message) {
            let response = '';
            
            // Simple response logic based on keywords
            const lowerMessage = message.toLowerCase();
            
            if (lowerMessage.includes('bonjour') || lowerMessage.includes('salut') || lowerMessage.includes('hello')) {
                response = 'Bonjour ! Comment puis-je vous aider aujourd\'hui ?';
            } 
            else if (lowerMessage.includes('horaire') || lowerMessage.includes('heure') || lowerMessage.includes('ouvert')) {
                response = 'Nous sommes ouverts du lundi au vendredi de 8h à 18h et le samedi de 9h à 16h.';
            }
            else if (lowerMessage.includes('contact') || lowerMessage.includes('téléphone') || lowerMessage.includes('appeler')) {
                response = 'Vous pouvez nous contacter au 01 23 45 67 89 ou par email à contact@cgvmotors.fr';
            }
            else if (lowerMessage.includes('adresse') || lowerMessage.includes('où') || lowerMessage.includes('localisation')) {
                response = 'Nous sommes situés au 123 Avenue des Véhicules, 75000 Paris.';
            }
            else if (lowerMessage.includes('voiture') || lowerMessage.includes('véhicule') || lowerMessage.includes('auto')) {
                response = 'Nous proposons une large gamme de véhicules neufs et d\'occasion. Souhaitez-vous des informations sur un modèle particulier ?';
            }
            else if (lowerMessage.includes('prix') || lowerMessage.includes('tarif') || lowerMessage.includes('coût')) {
                response = 'Nos prix varient selon les modèles et options. Pouvez-vous préciser quel véhicule vous intéresse ?';
            }
            else if (lowerMessage.includes('location') || lowerMessage.includes('louer')) {
                response = 'Nous proposons des services de location courte et longue durée. Pour plus d\'informations, vous pouvez consulter notre page de location ou nous contacter directement.';
            }
            else if (lowerMessage.includes('merci')) {
                response = 'Je vous en prie ! N\'hésitez pas si vous avez d\'autres questions.';
            }
            else if (lowerMessage.includes('au revoir') || lowerMessage.includes('bye')) {
                response = 'Au revoir ! Merci d\'avoir discuté avec nous. À bientôt !';
            }
            else {
                response = 'Je n\'ai pas bien compris votre demande. Pouvez-vous reformuler ou demander des informations sur nos véhicules, nos horaires, ou nos services ?';
            }
            
            // Add bot response
            addMessage(response, 'bot');
        }
    });
</script>
