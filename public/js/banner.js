document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si nous sommes sur mobile
    const isMobile = window.innerWidth <= 768 || 'ontouchstart' in window;
    
    // N'exécuter les animations de particules que sur desktop
    if (!isMobile) {
        const carParticles = document.querySelector('.car-particles');
        
        function createParticle() {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            const size = Math.random() * 3 + 1;
            const posX = Math.random() * 60 + 20; // Position entre 20% et 80%
            
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${posX}%`;
            particle.style.bottom = '0';
            particle.style.position = 'absolute';
            particle.style.backgroundColor = 'rgba(57, 196, 222, 0.6)';
            particle.style.borderRadius = '50%';
            particle.style.filter = 'blur(1px)';
            
            carParticles.appendChild(particle);
            
            const animationDuration = Math.random() * 2 + 1;
            const keyframes = [
                { transform: 'translateY(0)', opacity: 0 },
                { transform: 'translateY(-10px)', opacity: 1 },
                { transform: 'translateY(-30px)', opacity: 0 }
            ];
            
            const animation = particle.animate(keyframes, {
                duration: animationDuration * 1000,
                easing: 'ease-out'
            });
            
            animation.onfinish = () => {
                particle.remove();
            };
        }
        
        setInterval(createParticle, 300);
    }
    
    // Animation des compteurs - uniquement sur desktop
    if (!isMobile) {
        const counters = document.querySelectorAll('.count');
        
        function animateCounter(counter) {
            const target = parseInt(counter.getAttribute('data-count'), 10);
            const duration = 2000; // 2 secondes
            const step = Math.max(1, Math.floor(target / (duration / 30))); // 30fps
            
            let current = 0;
            const timer = setInterval(() => {
                current += step;
                counter.textContent = current;
                
                if (current >= target) {
                    counter.textContent = target;
                    clearInterval(timer);
                }
            }, 30);
        }
        
        // Déclencher l'animation lorsque les compteurs sont visibles
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => {
            observer.observe(counter);
        });
    } else {
        // Sur mobile, juste afficher les valeurs finales sans animation
        document.querySelectorAll('.count').forEach(counter => {
            counter.textContent = counter.getAttribute('data-count');
        });
    }
    
    // Curseur personnalisé - uniquement sur desktop
    const cursor = document.querySelector('.custom-cursor');
    
    if (!isMobile) {
        cursor.style.display = 'block';
        
        document.addEventListener('mousemove', (e) => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        });
        
        // Effets d'échelle sur les éléments interactifs
        const interactiveElements = document.querySelectorAll('.btn-banner, .feature, .counter-item');
        
        interactiveElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursor.style.transform = 'translate(-50%, -50%) scale(1.5)';
                cursor.style.backgroundColor = 'rgba(57, 196, 222, 0.1)';
            });
            
            el.addEventListener('mouseleave', () => {
                cursor.style.transform = 'translate(-50%, -50%) scale(1)';
                cursor.style.backgroundColor = 'transparent';
            });
        });
    }
});