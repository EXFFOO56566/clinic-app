var activeRoom;
var previewTracks;

var roomName;

function attachTracks(tracks, container) {
  tracks.forEach(function(track) {
    container.appendChild(track.attach());
  });
}

function attachParticipantTracks(participant, container) {
  var tracks = Array.from(participant.tracks.values());
  attachTracks(tracks, container);
}

function detachTracks(tracks) {
  tracks.forEach(function(track) {
    track.detach().forEach(function(detachedElement) {
      detachedElement.remove();
    });
  });
}

function detachParticipantTracks(participant) {
  var tracks = Array.from(participant.tracks.values());
  detachTracks(tracks);
}

// Check for WebRTC
if (!navigator.webkitGetUserMedia && !navigator.mozGetUserMedia) {
  console.log('WebRTC is not available in your browser.');
}

// When we are about to transition away from this page, disconnect
// from the room, if joined.
window.addEventListener('beforeunload', leaveRoomIfJoined);

//$.getJSON($("#siteurl").val()+'/token', function(data) {
  //identity = data.identity;

 // document.getElementById('room-controls').style.display = 'block';

  // Bind button to join room
  document.getElementById('button-join').onclick = function () {
       document.getElementById("callnoti").style.display="none";
     roomName=$("#channelid").val();
   StopSound();
    if (roomName) {
      log("Joining room '" + roomName + "'...");

      var connectOptions = { name: roomName, logLevel: 'debug' };
      if (previewTracks) {
        connectOptions.tracks = previewTracks;
      }

      Twilio.Video.connect($("#usertoken").val(), connectOptions).then(roomJoined, function(error) {
        log('Could not connect to Twilio: ' + error.message);
      });
      $.ajax({
              url: $("#siteurl").val()+"/videosendnotification",
              method:"get",
              data:{ channelid:$("#channelid").val(),action:'outgoing'},
              success: function( data ) {
               console.log(data);
                 $("#videocallingchannelid").val($("#channelid").val());
              }
          });
    } else {
      alert('Please enter a room name.');
    }
  };

  // Bind button to leave room
  document.getElementById('button-leave').onclick = function () {
         log('Leaving room...');
         document.getElementById("button-leave").style.display="none";
         document.getElementById("button-join").style.display="block";
	       document.getElementById("videopart").style.display="none";
         document.getElementById("callnoti").style.display="none";
         document.getElementById("button-miss").style.display="block";
          document.getElementById("videocallmodal").style.display="none";
         StopSound();
          $.ajax({
              url: $("#siteurl").val()+"/videosendnotification",
              method:"get",
              data:{ channelid:$("#videocallingchannelid").val(),action:'reject'},
              success: function( data ) {
               console.log(data);
              }
          });
         if(activeRoom){
            activeRoom.disconnect();
         }
         
  };
 /* document.getElementById('button-cut').onclick = function () {
         log('Leaving room...');
         document.getElementById("button-leave").style.display="none";
         document.getElementById("button-join").style.display="block";
         document.getElementById("videopart").style.display="none";
         document.getElementById("callnoti").style.display="none";
         document.getElementById("button-miss").style.display="block";
          document.getElementById("videocallmodal").style.display="none";
         StopSound();
          $.ajax({
              url: $("#siteurl").val()+"/videosendnotification",
              method:"get",
              data:{ channelid:$("#videocallingchannelid").val(),action:'busycall'},
              success: function( data ) {
               console.log(data);
              }
          });
         if(activeRoom){
            activeRoom.disconnect();
         }
         
  };*/
  document.getElementById('button-miss').onclick = function () {
         log('Leaving room...');
         document.getElementById("button-leave").style.display="none";
         document.getElementById("button-miss").style.display="none";
         document.getElementById("button-join").style.display="block";
         document.getElementById("videopart").style.display="none";
         document.getElementById("callnoti").style.display="none";
          document.getElementById("videocallmodal").style.display="none";
         StopSound();
          $.ajax({
              url: $("#siteurl").val()+"/videosendnotification",
              method:"get",
              data:{ channelid:$("#videocallingchannelid").val(),action:'busycall'},
              success: function( data ) {
              window.location.reload();
              }
          });
         if(activeRoom){
            activeRoom.disconnect();
         }
         
  };
//});

document.getElementById('button-connect').onclick = function () {
         roomName=$("#videocallingchannelid").val();
        console.log(roomName);
        StopSound();
    if (roomName) {
      log("Joining room '" + roomName + "'...");

      var connectOptions = { name: roomName, logLevel: 'debug' };
      if (previewTracks) {
        connectOptions.tracks = previewTracks;
      }

      Twilio.Video.connect($("#usertoken").val(), connectOptions).then(roomJoined, function(error) {
        log('Could not connect to Twilio: ' + error.message);
      });
    } else {
      alert('Please enter a room name.');
    }
         
  };
// Successfully connected!
function roomJoined(room) {
  activeRoom = room;

 // log("Joined as '" + identity + "'");
  document.getElementById('button-leave').style.display = 'inline';
    document.getElementById("button-join").style.display="none";
    document.getElementById("button-miss").style.display="block";
  document.getElementById("videopart").style.display="block";
  document.getElementById("videocallmodal").style.display="block";
   document.getElementById("callnoti").style.display="none";
    document.getElementById("button-connect").style.display="none";
    roomName = document.getElementById('channelid').value;
   
  
  // Draw local video, if not already previewing
  var previewContainer = document.getElementById('local-media');
  if (!previewContainer.querySelector('video')) {
    attachParticipantTracks(room.localParticipant, previewContainer);
  }

  room.participants.forEach(function(participant) {
    log("Already in Room: '" + participant.identity + "'");
    var previewContainer = document.getElementById('remote-media');
    attachParticipantTracks(participant, previewContainer);
  });

  // When a participant joins, draw their video on screen
  room.on('participantConnected', function(participant) {
    log("Joining: '" + participant.identity + "'");
  });

  room.on('trackAdded', function(track, participant) {
    log(participant.identity + " added track: " + track.kind);
    var previewContainer = document.getElementById('remote-media');
    attachTracks([track], previewContainer);
  });

  room.on('trackRemoved', function(track, participant) {
    log(participant.identity + " removed track: " + track.kind);
    detachTracks([track]);
  });

  // When a participant disconnects, note in log
  room.on('participantDisconnected', function(participant) {
    log("Participant '" + participant.identity + "' left the room");
    detachParticipantTracks(participant);
  });

  // When we are disconnected, stop capturing local video
  // Also remove media for all remote participants
  room.on('disconnected', function() {
    log('Left');
    detachParticipantTracks(room.localParticipant);
    room.participants.forEach(detachParticipantTracks);
    activeRoom = null;
    document.getElementById("button-leave").style.display="none";
         document.getElementById("button-miss").style.display="none";
         document.getElementById("button-join").style.display="block";
         document.getElementById("videopart").style.display="none";
         document.getElementById("callnoti").style.display="none";
          document.getElementById("videocallmodal").style.display="none";
  });

  var localTracksPromise = previewTracks
  ? Promise.resolve(previewTracks)
  : Twilio.Video.createLocalTracks();

  localTracksPromise.then(function(tracks) {
    previewTracks = tracks;
    var previewContainer = document.getElementById('local-media');
    if (!previewContainer.querySelector('video')) {
      attachTracks(tracks, previewContainer);
    }
  }, function(error) {
    console.error('Unable to access local media', error);
    log('Unable to access Camera and Microphone');
  });
}


function log(message) {
  var logDiv = document.getElementById('log');
  logDiv.innerHTML += '<p>&gt;&nbsp;' + message + '</p>';
  logDiv.scrollTop = logDiv.scrollHeight;
}

function leaveRoomIfJoined() {
  if (activeRoom) {
    activeRoom.disconnect();
  }
}