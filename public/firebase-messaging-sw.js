importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyC3tzyv__3udwxPWI5swk12qoGZ4J5sb1c",
    authDomain: "test-notification-3f882.firebaseapp.com",
    projectId: "test-notification-3f882",
    storageBucket: "test-notification-3f882.firebasestorage.app",
    messagingSenderId: "786873585382",
    appId: "1:786873585382:web:fe4db5ece2173b8eaaf527",
    measurementId: "G-31WKBC1QJW"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
    console.log('Received background message ', payload);
    
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon || '/favicon.ico'
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
