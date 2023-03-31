
    <input type="hidden" id="mycurrentuser" value="{{Auth::id()}}">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-analytics.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-messaging.js"></script> 
    <script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-database.js"></script>
    
    <script>
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
       if($("#needtoken").length){
            messaging
            .requestPermission()
            .then(function () {
                console.log("Notification permission granted.");
                return messaging.getToken()
            })
            .then(function(token) {
                var currentuser = $("#authid").val();
                if(currentuser!=""){
                    firebase.database().ref(currentuser + "/TokenList/").set({
                        web: token
                    });
                    console.log("token store");
                }
            })
            .catch(function (err) {
                console.log("Unable to get permission to notify.", err);
            }); 
       }else{
           	var currentuser = $("#authid").val();
        	firebase.database().ref(currentuser + "/TokenList/").update({
        		web: 0
        	});
       }
       
    </script>