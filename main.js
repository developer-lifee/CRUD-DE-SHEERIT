

document.getElementById('theme').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'r') {
        document.getElementById('sell').click();
    }
});
