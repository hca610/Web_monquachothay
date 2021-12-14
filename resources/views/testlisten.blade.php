<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('a13024e4824fe0c8b79c', {
      cluster: 'ap1'
    });

    var channel = pusher.subscribe('MessageChannel.User.10');
    channel.bind('MessageUpdated', function(data) {
      alert(JSON.stringify(data));
      console.log("Update");
    });
    channel.bind('MessageCreated', function(data) {
      alert("Create"+JSON.stringify(data));
      console.log("Create");
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>