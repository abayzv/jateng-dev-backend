const startTime = Date.now();
window.addEventListener('beforeunload', function (e) {
    const endTime = Date.now();
    const timeSpent = (endTime - startTime) / 1000;
    window.fetch(`/user-leave?timeSpent=${timeSpent}`, {
        method: 'GET',
    });
});