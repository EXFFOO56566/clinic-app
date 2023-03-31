

$(function() {
  // Get handle to the chat div
  var $chatWindow = $('#messages');

  // Our interface to the Chat service
  var chatClient;

  // A handle to the "general" chat channel - the one and only channel we
  // will have in this sample app
  var generalChannel;

  // The server will assign the client a random username - store that value
  // here
  var username;

  // Helper function to print info messages to the chat window
  function print(infoMessage, asHtml) {
    var $msg = $('<div class="info">');
    if (asHtml) {
      $msg.html(infoMessage);
    } else {
      $msg.text(infoMessage);
    }
    $chatWindow.append($msg);
  }

  // Helper function to print chat message to the chat window
  function printMessage(fromUser, message) {
    var $user = $('<span class="username">').text(fromUser + ':');
    if (fromUser === username) {
      $user.addClass('me');
    }
    var str=message.split('#');
    if(str[0]=='image'){
       var $message = '<img src="'+str[1]+'"  style="width:250px;height:250px"/>';
    }else if(str[0]=='video'){
        var $message = '<video width="320" height="240" controls><source src="'+str[1]+'" type="video/mp4"></video>';
    }else{
        var $message = $('<span class="message">').text(str[1]);
    }
    
    var $container = $('<div class="message-container">');
    $container.append($user).append($message);
    $chatWindow.append($container);
    $chatWindow.scrollTop($chatWindow[0].scrollHeight);
  }

  // Alert the user they have been assigned a random username
  print('Logging in...');

  // Get an access token for the current user, passing a username (identity)
  // and a device ID - for browser-based apps, we'll always just use the
  // value "browser"
 
     $.getJSON($("#doctor_url").val()+'/token/', {
        device: 'browser'
      }, function(data) {
        // Initialize the Chat client
        Twilio.Chat.Client.create(data.token).then(client => {
          console.log('Created chat client');
          chatClient = client;
          chatClient.getSubscribedChannels().then(createOrJoinGeneralChannel);
           
         //Alert the user they have been assigned a random username
        username = data.identity;
        print('You have been assigned a random username of: '
        + '<span class="me">' + username + '</span>', true);

        }).catch(error => {
             console.error(error);
             print('There was an error creating the chat client:<br/>' + error, true);
             print('Please check your .env file.', false);
        });
      });
    

  function createOrJoinGeneralChannel() {
    // Get the general chat channel, which is where all the messages are
    // sent in this simple application
    print('Attempting to join "general" chat channel...');
    chatClient.getChannelByUniqueName($("#channelid").val())
    .then(function(channel) {
      generalChannel = channel;
      console.log('Found general channel:');
      console.log(generalChannel);
      setupChannel();
    }).catch(function() {
      // If it doesn't exist, let's create it
      console.log('Creating general channel');
      chatClient.createChannel({
        uniqueName: $("#channelid").val(),
        friendlyName: 'General Chat Channel'
      }).then(function(channel) {
        console.log('Created general channel:');
        console.log(channel);
        generalChannel = channel;
        setupChannel();
      }).catch(function(channel) {
        console.log('Channel could not be created:');
        console.log(channel);
      });
    });
  }

  // Set up channel after it has been found
  function setupChannel() {
    // Join the general channel
    generalChannel.join().then(function(channel) {
      print('Joined channel as '
      + '<span class="me">' + username + '</span>.', true);
    });

    // Listen for new messages sent to the channel
    generalChannel.on('messageAdded', function(message) {
      printMessage(message.author, message.body);
    });
    generalChannel.on('memberJoined', function(member) {
      console.log(member.identity + 'has joined the channel.');
    });
    // Listen for members user info changing
    generalChannel.on('memberInfoUpdated', function(member) {
      console.log(member.identity + 'updated their info.');
    });
    // Listen for members leaving a channel
    generalChannel.on('memberLeft', function(member) {
      console.log(member.identity + 'has left the channel.');
    });
    // Listen for members typing
    generalChannel.on('typingStarted', function(member) {
      console.log(member.identity + 'is currently typing.');
    });
    // Listen for members typing
    generalChannel.on('typingEnded', function(member) {
      console.log(member.identity + 'has stopped typing.');
    });
  }
  
  // Listen for members joining a channel


  // Send a new message to the general channel
  var $input = $('#chat-input');
  $input.on('keydown', function(e) {
   //  console.log(generalChannel);
    if (e.keyCode == 13) {
      if (generalChannel === undefined) {
       // print('The Chat Service is not configured. Please check your .env file.', false);
        //return;
      }
      generalChannel.sendMessage("text#"+$input.val())
      $input.val('');
    }
    else{
         generalChannel.typing();
    }
  });
  $("#file").on('change', function(e) {
         var fd = new FormData();
         var files = $('#file')[0].files[0];
         fd.append('file',files);
         $.ajax({
            url: 'storeimage.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
               var msg=response.split("#");
               $.ajax({
                        url: 'receiveimage.php?media='+msg[1],
                        type: 'get',
                        contentType: false,
                        processData: false,
                        success: function(response){
                          generalChannel.sendMessage(msg[0]+"#"+response);
                          
                        },
                    });
            },
        });
  });
  
   
   
});
