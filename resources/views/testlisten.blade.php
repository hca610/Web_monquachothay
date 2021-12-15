<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('a13024e4824fe0c8b79c', {
      cluster: 'ap1',
      forceTLS: true,
      authEndpoint: "/broadcasting/auth",
      auth: {
          headers: {
            Authorization: 'Bearer ' + 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzOTIyOTgxNywiZXhwIjoxNjM5ODM0NjE3LCJuYmYiOjE2MzkyMjk4MTcsImp0aSI6IlZGcDRUUlJFaGhOWGFjdTAiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.qRA0AwUGmW1xMEn-_JtXxmnbZ8Ox6fqeJfiRc17YO14'
          }, // Access Token cua User co user_id = 1
      },
    });

    var channel1 = pusher.subscribe('MessageChannel.User.1');
    channel1.bind('MessageUpdated', function(data) {
      alert(JSON.stringify(data));
      console.log("User 1 get Update");
    });
    channel1.bind('MessageCreated', function(data) {
      alert("Create"+JSON.stringify(data));
      console.log("User 1 get Create");
    });

    var channel2 = pusher.subscribe('private-MessageChannel.User.1');
    channel2.bind('MessageUpdated', function(data) {
      alert(JSON.stringify(data));
      console.log("User 1 get PRIVATE Update");
    });
    channel2.bind('MessageCreated', function(data) {
      alert("Create"+JSON.stringify(data));
      console.log("User 1 get PRIVATE Create");
    });

    var channel3 = pusher.subscribe('MessageChannel.User.2');
    channel3.bind('MessageUpdated', function(data) {
      alert(JSON.stringify(data));
      console.log("User 2 get Update");
    });
    channel3.bind('MessageCreated', function(data) {
      alert("Create"+JSON.stringify(data));
      console.log("User 2 get Create");
    });

    var channel4 = pusher.subscribe('private-MessageChannel.User.2');
    channel4.bind('MessageUpdated', function(data) {
      alert(JSON.stringify(data));
      console.log("User 2 get PRIVATE Update");
    });
    channel4.bind('MessageCreated', function(data) {
      alert("Create"+JSON.stringify(data));
      console.log("User 2 get PRIVATE Create");
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