var firebaseConfig = {
    apiKey: "AIzaSyAzSB8qi5FnEoRbKM9a85zhj5qHEJYy5oc",
    authDomain: "single-clinic.firebaseapp.com",
    databaseURL: "https://single-clinic-default-rtdb.firebaseio.com",
    projectId: "single-clinic",
    storageBucket: "single-clinic.appspot.com",
    messagingSenderId: "255024754213",
    appId: "1:255024754213:web:4ea7547499ad72f39a99cc",
    measurementId: "G-71F4ZYZ0PV"
  };
  // Initialize Firebase
firebase.initializeApp(firebaseConfig);
firebase.analytics();
var db = firebase.firestore();
var messaging = firebase.messaging();
var database = firebase.database();