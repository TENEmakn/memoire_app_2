<footer class="bg-info bg-opacity-25 py-3">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo and Social Media -->
            <div class="col-md-3 mb-3 mb-md-0">
                <h5 class="text-info fw-bold mb-2" style="font-size: 1.5rem;">CGV MOTORS</h5>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-dark rounded-circle p-2 social-icon" style="width: 40px; height: 40px; transition: all 0.3s ease;">
                        <i class="fab fa-telegram-plane" style="font-size: 1.2rem;"></i>
                    </a>
                    <a href="#" class="btn btn-dark rounded-circle p-2 social-icon" style="width: 40px; height: 40px; transition: all 0.3s ease;">
                        <i class="fab fa-twitter" style="font-size: 1.2rem;"></i>
                    </a>
                    <a href="#" class="btn btn-dark rounded-circle p-2 social-icon" style="width: 40px; height: 40px; transition: all 0.3s ease;">
                        <i class="fab fa-youtube" style="font-size: 1.2rem;"></i>
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="d-flex flex-column">
                    <h6 class="text-info fw-bold mb-2" style="font-size: 0.9rem;">Liens Rapides</h6>
                    <div class="d-flex flex-column">
                        <a href="{{ route('contact.show') }}" class="text-dark text-decoration-none mb-2 footer-link" style="font-size: 0.9rem; transition: all 0.3s ease;">Service client</a>
                        <a href="{{ route('about') }}" class="text-dark text-decoration-none mb-2 footer-link" style="font-size: 0.9rem; transition: all 0.3s ease;">À Propos</a>
                        <a href="{{ route('contrat.location') }}" class="text-dark text-decoration-none mb-2 footer-link" style="font-size: 0.9rem; transition: all 0.3s ease;">Contrat de location</a>
                        <a href="{{ route('contrat.vente') }}" class="text-dark text-decoration-none mb-2 footer-link" style="font-size: 0.9rem; transition: all 0.3s ease;">Contrat de vente</a>
                    </div>
                </div>
            </div>

            <!-- Footer Image -->
            <div class="col-md-3">
                <img src="{{ asset('images/car-footer.png') }}" alt="Car illustration" class="img-fluid" style="max-width: min(900px, 90%); width: 90%; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
            </div>
        </div>

        <!-- Copyright -->
        <div class="row mt-3">
            <div class="col-12 text-center">
                <small class="text-muted" style="font-size: 0.8rem;">© 2025 tous droits réservés. CGV Motors Developed by Kone Tenemakan</small>
            </div>
        </div>
    </div>
</footer>

<!-- Back to top button -->
<a href="#" class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
</a>

<style>
    .social-icon {
        background-color: #0dcaf0 !important;
        border: none !important;
        color: white !important;
    }
    
    .social-icon:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 6px 12px rgba(13, 202, 240, 0.3);
        background-color: #0b5ed7 !important;
    }
    
    .footer-link:hover {
        color: #0dcaf0 !important;
        transform: translateX(5px);
    }

    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background-color: #0dcaf0;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        opacity: 0;
        visibility: hidden;
        z-index: 1000;
    }

    .back-to-top:hover {
        background-color: #0b5ed7;
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(13, 202, 240, 0.3);
    }

    .back-to-top.visible {
        opacity: 1;
        visibility: visible;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });

        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
