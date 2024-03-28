    document.querySelectorAll('.details-btn').forEach(button => {
    button.addEventListener('click', function() {
        const details = this.parentElement.nextElementSibling;
        details.style.display = details.style.display === 'none' ? 'block' : 'none';
    });
});
