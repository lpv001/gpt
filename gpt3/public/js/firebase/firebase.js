if ("serviceWorker" in navigator) {
    navigator.serviceWorker
        .register("/firebase-messaging-sw.js", { scope: "sw-test" })
        .then(function (registration) {
            // registration worked
            console.log("Registration succeeded.");
        })
        .catch(function (error) {
            // registration failed
            console.log("Registration failed with " + error);
        });
}

var firebaseConfig = {
    apiKey: "AIzaSyDMrvjgQdKhbHSRa5ExNv_ciAP9tnuYNQE",
    authDomain: "gangos-cambodia.firebaseapp.com",
    databaseURL: "https://gangos-cambodia.firebaseio.com",
    projectId: "gangos-cambodia",
    storageBucket: "gangos-cambodia.appspot.com",
    messagingSenderId: "145187966800",
    appId: "1:145187966800:web:10d604db2393588f",
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

function IntitalizeFireBaseMessaging() {
    messaging
        .requestPermission()
        .then(function () {
            console.log("Notification Permission");
            return messaging.getToken();
        })
        .then(function (token) {
            console.log("Token : " + token);
            document.getElementById("fcmToken").value = token;
        })
        .catch(function (reason) {
            console.log(reason);
            return;
            let message = reason.message.split(":");
            if (message.lenght == 0) return;

            message = message[1].split(".")[0];
            Swal.fire({
                position: "top-start",
                width: 400,
                text: message,
                html: `
                    <div class="row">
                        <div class="col-2">
                            <i class="fas fa-bell h1"></i>
                        </div>

                        <div class="col-10">
                            <span>${message}.</span>
                        </div>
                    </div>
                `,
                allowOutsideClick: false,
                // icon: "success",
                // title: "Notificaiotn",
                showConfirmButton: true,
                confirmButtonText: "Allow",
                confirmButton: "btn btn-small btn-primary",

                // timer: 1500,
            }).then(function (result) {
                messaging.requestPermission();
            });
            // console.log("firebase :", message);
        });
}

messaging.onMessage(function (payload) {
    console.log(payload);
    let notificationTitle = payload.data.title;
    let notificationOptions = {
        body: "Please View Order.",
        icon: "",
    };

    if (Notification.permission === "granted") {
        $(".notification").text(1);
        $("#a-notification").attr(
            "href",
            `/my-account/customer-order/${payload.data.order_id}`
        );
        var notification = new Notification(
            notificationTitle,
            notificationOptions
        );

        notification.onclick = function (ev) {
            ev.preventDefault();
            window.open(payload.notification.click_action, "_blank");
            notification.close();
        };
    }
});

messaging.onTokenRefresh(function () {
    messaging
        .getToken()
        .then(function (newtoken) {
            console.log("New Token : " + newtoken);
            sendTokenToServer(newtoken);
        })
        .catch(function (reason) {
            console.log(reason);
        });
});
IntitalizeFireBaseMessaging();
// end firebase
