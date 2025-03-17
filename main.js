document.getElementById('theme').addEventListener('click', function() {
    document.body.classList.toggle('dark');
    this.textContent = document.body.classList.contains('dark') ? '🌞' : '🌙';
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'r') {
        document.getElementById('sell').click();
    }
});
