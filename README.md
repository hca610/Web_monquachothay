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
Cai dat
```
npm install pusher-js @react-native-community/netinfo
```
Code mau:
```js
import Pusher from 'pusher-js/react-native';

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher('a13024e4824fe0c8b79c', {
  cluster: 'ap1',
//   forceTLS: true
});

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function(data) {
  alert(JSON.stringify(data));
});
```