# Cách cài đặt server Backend 
 Đầu tiên clone repo về máy 
``` sh
git clone https://github.com/hca610/Web_monquachothay.git
```
Trong folder của project, tạo file ```.env``` với nội dung copy từ file ``` .env.example  ``` và sửa đổi một số trường như sau :
```
APP_NAME=JobWarehouse
APP_ENV=local
APP_KEY=base64:5sblHEjEpHAd5XmVChY/20en5J7b9BFUgHy2Q8HqLSg=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Web_monquachothay
DB_USERNAME=root
DB_PASSWORD=[Nhập pass của database nếu có]  
```

Mở terminal, trỏ vào folder project rồi nhập:
``` sh
git checkout dev
composer install
``` 

Sinh khóa 
```
php artisan jwt:secret
```
Tạo 1 schema trong database với tên ```Web_monquachothay```

Quay lại terminal, nhập lệnh sau để tạo bảng và sinh data:  
``` sh
php artisan migrate:fresh --seed 
```
Pull code về trước mỗi lần chạy
```
git pull
```
Bật server:
``` sh
php artisan serve
```

# De su dung Websocket
## Doi voi Back-end
Sua doi them noi dung trong file .env.example:
```
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=1315519
PUSHER_APP_KEY=a13024e4824fe0c8b79c
PUSHER_APP_SECRET=549b46ce78e711c563cf
PUSHER_APP_CLUSTER=ap1
```
Cai dat them pusher:
```
composer require pusher/pusher-php-server
```
## Doi voi Front-end
Truy cap link {ten mien}/test/listen de chay code mau
Code mau:
```html
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
      authEndpoint: "{ten mien}/broadcasting/auth",
      auth: {
          headers: {
            Authorization: 'Bearer ' + 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzOTIyOTgxNywiZXhwIjoxNjM5ODM0NjE3LCJuYmYiOjE2MzkyMjk4MTcsImp0aSI6IlZGcDRUUlJFaGhOWGFjdTAiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.qRA0AwUGmW1xMEn-_JtXxmnbZ8Ox6fqeJfiRc17YO14'
          }, // Day la token cua user 1 (Chay tren may cua Long)
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
```