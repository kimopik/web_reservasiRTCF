// script.js
document.addEventListener('DOMContentLoaded', function() {
    // Efek fade-in untuk section
    const sections = document.querySelectorAll('section');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 1s ease-in';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
    
    // Validasi formulir reservasi
    const form = document.querySelector('.reservation form');
    if (form) {
        form.addEventListener('submit', function(event) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const date = document.getElementById('date').value;
            const time = document.getElementById('time').value;
            
            if (name === '') {
                alert('Please enter your name.');
                event.preventDefault();
            } else if (email === '') {
                alert('Please enter your email.');
                event.preventDefault();
            } else if (!date) {
                alert('Please select a date.');
                event.preventDefault();
            } else if (!time) {
                alert('Please select a time.');
                event.preventDefault();
            }
        });
    }
});