self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    if (event.data) {
        const message = event.data.json();

        event.waitUntil(
            self.registration.showNotification(message.title, {
                body: message.body,
                icon: message.icon || '/img/logo_notifier_fallback.png',
                data: message.data,
                actions: message.actions
            })
        );
    }
});


self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data?.url || '/')
    );
});
