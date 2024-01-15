importScripts("https://www.gstatic.com/firebasejs/8.2.3/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.2.3/firebase-messaging.js");

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.

firebase.initializeApp({
    apiKey: "AIzaSyDMrvjgQdKhbHSRa5ExNv_ciAP9tnuYNQE",
    authDomain: "gangos-cambodia.firebaseapp.com",
    databaseURL: "https://gangos-cambodia.firebaseio.com",
    projectId: "gangos-cambodia",
    storageBucket: "gangos-cambodia.appspot.com",
    messagingSenderId: "145187966800",
    appId: "1:145187966800:web:10d604db2393588f",
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload
    );
    // Customize notification here
    let notificationTitle = payload.data.title;
    let notificationOptions = {
        body: "Please View Order.",
        icon: "/firebase-logo.png",
    };

    self.addEventListener("notificationclick", function (event) {
        // Close notification.
        event.notification.close();
        // Example: Open window after 3 seconds.
        // (doing so is a terrible user experience by the way, because
        //  the user is left wondering what happens for 3 seconds.)
        var promise = new Promise(function (resolve) {
            setTimeout(resolve, 1000);
        }).then(function () {
            // return the promise returned by openWindow, just in case.
            // Opening any origin only works in Chrome 43+.
            return clients.openWindow(
                self.location.origin + payload.data.click_action
            );
        });

        // Now wait for the promise to keep the permission alive.
        event.waitUntil(promise);
    });

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});

// messaging.setBackgroundMessageHandler(function (payload) {
//     console.log(
//         "[firebase-messaging-sw.js] Received background message ",
//         payload
//     );

//     // Customize notification here
//     let notificationTitle = payload.data.title;
//     let notificationOptions = {
//         body: "Please View Order.",
//         icon: "/firebase-logo.png",
//     };

//     self.addEventListener("notificationclick", function (event) {
//         // Close notification.
//         event.notification.close();
//         // Example: Open window after 3 seconds.
//         // (doing so is a terrible user experience by the way, because
//         //  the user is left wondering what happens for 3 seconds.)
//         var promise = new Promise(function (resolve) {
//             setTimeout(resolve, 1000);
//         }).then(function () {
//             // return the promise returned by openWindow, just in case.
//             // Opening any origin only works in Chrome 43+.
//             return clients.openWindow(
//                 `${self.location.origin}/orders/${payload.data.order_id}`
//             );
//         });

//         // Now wait for the promise to keep the permission alive.
//         event.waitUntil(promise);
//     });

//     return self.registration.showNotification(
//         notificationTitle,
//         notificationOptions
//     );
// });
