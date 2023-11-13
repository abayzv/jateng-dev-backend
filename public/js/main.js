window.addEventListener('beforeunload', function (e) {
    window.fetch(`/user-leave`, {
        method: 'GET',
    });
});