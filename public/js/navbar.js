document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
            navbar.classList.add('fixed');
        } else {
            navbar.classList.remove('scrolled');
            navbar.classList.remove('fixed');
        }
    });
});

