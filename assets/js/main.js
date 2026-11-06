function togglePaymentMethod(method) {
    const stkBox = document.getElementById('stkMethodBox');
    const paybillBox = document.getElementById('paybillMethodBox');
    if (stkBox && paybillBox) {
        if (method === 'stk') {
            stkBox.style.display = 'block';
            paybillBox.style.display = 'none';
        } else {
            stkBox.style.display = 'none';
            paybillBox.style.display = 'block';
        }
    }
}
document.addEventListener('DOMContentLoaded', () => {
    const hamburgerToggle = document.getElementById('hamburgerToggle');
    const navMenu = document.querySelector('.nav-menu');

    if (hamburgerToggle && navMenu) {
        hamburgerToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            
            // Optional: Animate hamburger lines into an 'X'
            hamburgerToggle.classList.toggle('open');
        });

        // Close menu automatically when clicking any nav link
        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                hamburgerToggle.classList.remove('open');
            });
        });
    }
});