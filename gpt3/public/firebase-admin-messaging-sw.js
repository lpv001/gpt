importScripts("https://www.gstatic.com/firebasejs/8.2.3/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.2.3/firebase-messaging.js");

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.

firebase.initializeApp({
    apiKey: "AIzaSyDRN12_tFT-1vKBmlyG3RHTr4fflyHY2jg",
    authDomain: "gangostesting.firebaseapp.com",
    databaseURL: "https://gangostesting.firebaseio.com",
    projectId: "gangostesting",
    storageBucket: "gangostesting.appspot.com",
    messagingSenderId: "882033418489",
    appId: "1:882033418489:web:b70c188302a2ed35ac8228",
    // measurementId: "G-measurement-id",
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    console.log(
        "[firebase-,KKKKKKmessaging-sw.js] Received background message ",
        payload
    );

    // Customize notification here
    let notificationTitle = payload.data.title;
    let notificationOptions = {
        body: "Please View Order.",
        tag: "renotify",
        requireInteraction: true,
        icon: "/img/logo.png",
    };

    self.addEventListener("notificationclick", function (event) {
        if (!event.action) {
            // Was a normal notification click
            console.log("Notification Click.");
            // console.log(payload.data);
            console.log(event);

            event.notification.close();
            event.waitUntil(
                clients.matchAll({ type: "window" }).then((windowClients) => {
                    console.log(payload.data);
                    let url =
                        self.location.origin +
                        "/orders/" +
                        payload.data.order_id +
                        "/edit";

                    // Check if there is already a window/tab open with the target URL
                    for (var i = 0; i < windowClients.length; i++) {
                        var client = windowClients[i];
                        // If so, just focus it.
                        if (client.url === url && "focus" in client) {
                            return client.focus();
                        }
                    }
                    // If not, then open the target URL in a new window/tab.
                    if (clients.openWindow) {
                        return clients.openWindow(url);
                    }
                })
            );
            return;
        }
    });

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});
