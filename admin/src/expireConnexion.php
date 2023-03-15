<script>
    // Temps avant rafraîchissement en secondes
    var refresh_time = 300;

    function resetTimer() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(redirectToLogout, refresh_time * 1000);
    }

    function redirectToLogout() {
        localStorage.setItem('errorSession', 'Votre session à expiré.')
        window.location.href = "src/deconnexion.php";
    }

    var timeoutId = setTimeout(redirectToLogout, refresh_time * 1000);

    // Réinitialise le timer à chaque action de l'utilisateur
    $(document).on('click mousemove keypress', resetTimer);
</script>