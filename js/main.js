cat > js/main.js << 'EOF'
// Smooth scrolling para los enlaces del menú
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling
    document.querySelectorAll('nav a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Validación básica del formulario de contacto
    const contactForm = document.querySelector('.contact-form form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const mensaje = document.getElementById('mensaje').value;
            
            if (!nombre || !email || !mensaje) {
                e.preventDefault();
                alert('Por favor, completa todos los campos obligatorios.');
                return false;
            }
            
            // Validación básica de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Por favor, ingresa un email válido.');
                return false;
            }
        });
    }

    // Efecto de aparición al hacer scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Aplicar animación a las secciones
    document.querySelectorAll('section').forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(section);
    });

    // Mostrar mensajes de confirmación
    function mostrarMensajes() {
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.get('contacto') === 'exito') {
            alert('¡Mensaje enviado con éxito! Te contactaremos pronto.');
            // Limpiar parámetros URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        
        if (urlParams.get('newsletter') === 'exito') {
            alert('¡Te has suscrito exitosamente a nuestro newsletter!');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        
        if (urlParams.get('newsletter') === 'ya_registrado') {
            alert('Este email ya está registrado en nuestro newsletter.');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }

    mostrarMensajes();
});

// Navbar background on scroll
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (window.scrollY > 100) {
        header.style.backgroundColor = 'rgba(26, 58, 95, 0.95)';
        header.style.backdropFilter = 'blur(10px)';
    } else {
        header.style.backgroundColor = '#1a3a5f';
        header.style.backdropFilter = 'blur(0px)';
    }
});
EOF
