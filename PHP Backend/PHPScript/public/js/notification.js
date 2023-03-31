 var  MsgElem = document.getElementById("msg")
       var TokenElem = document.getElementById("token")
       var NotisElem = document.getElementById("notis")
       var ErrElem = document.getElementById("err")
        // Initialize Firebase
        // TODO: Replace with your project's customized code snippet
       var firebaseConfig = {
          apiKey: "AIzaSyDvUS09MDZyLFj5xjb6rIFORU-eQqmPmcI",
          authDomain: "singleclinic-11624.firebaseapp.com",
          databaseURL: "https://singleclinic-11624.firebaseio.com",
          projectId: "singleclinic-11624",
          storageBucket: "singleclinic-11624.appspot.com",
          messagingSenderId: "828519610440",
          appId: "1:828519610440:web:4d38186bcfc0470ba3807d",
          measurementId: "G-MFYS6BV52D"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function () {
                MsgElem.innerHTML = "Notification permission granted." 
                console.log("Notification permission granted.");
                 $("#webtoken").val(token);
                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) {
              console.log(token);
                 $("#webtoken").val(token);
                TokenElem.innerHTML = "token is : " + token
            })
            .catch(function (err) {
                ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
                console.log("Unable to get permission to notify.", err);
            });
      messaging.setBackgroundMessageHandler(function(payload) {
              console.log('[firebase-messaging-sw.js] Received background message ', payload);
              // Customize notification here
              const notificationTitle =payload.notification.title;
              const notificationOptions = {
                body: payload.notification.body,
              //  icon:payload.notification.image
              };
            
              return self.registration.showNotification(notificationTitle,
                notificationOptions);
            });

        messaging.onMessage(function(payload) {
            console.log("Message received. ", payload);
           console.log(payload.notification.title);
           console.log(payload.notification.body);
          //  console.log(payload.notification.image);
            NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload) 
        });