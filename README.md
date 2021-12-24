## Mục lục
* [Cài đặt Backend-sever](#backend-sever)
* [Tài liệu api hệ thống](#api)
* [Tài liệu sử dụng web-socket](#web-socket)
* [Thiết kế cơ sở dữ liệu](#database)

## Cài đặt Backend-sever <a name="backend-sever"></a>
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

BROADCAST_DRIVER=pusher

PUSHER_APP_ID=1315519
PUSHER_APP_KEY=a13024e4824fe0c8b79c
PUSHER_APP_SECRET=549b46ce78e711c563cf
PUSHER_APP_CLUSTER=ap1
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
Trong trường hợp có lỗi khi gọi api, thử xóa cache:
``` sh
php artisan optimize:clear
```

## Tài liệu api hệ thống <a name="api"></a>
https://documenter.getpostman.com/view/18333728/UVRDF5dA

## Tài liệu sử dụng web-socket <a name="web-socket"></a>
Sever sử dụng web-socket thông qua nền tảng [Pusher](https://pusher.com/)

Hiện tại back-end sever tiến hành broadcast trên 2 loại channel là private-MessageChannel.User.{user_id} và private-NotificationChannel.User.{user_id}. Các channel này được bảo mật cho từng người dùng thông qua acess token tương y hệt như acess token dùng cho api. Mỗi access token chỉ tương ứng với đúng một người dùng, vì vậy dựa vào access token ta có thể biết được người dùng kết nối với channel có hợp lệ không.

### private-MessageChannel.User.{user_id} Hỗ trợ chat realtime
Có thể bắt được các event sau:
- MessageCreated: Khi có một tin nhắn mới được tạo ra với receiver_id = user_id, tức tin nhắn mới được tạo ra và gửi tới người dùng có id là user_id.
- MessageUpdated: Khi có cấp nhật tin nhắn (bắt buộc cập nhật phải có thay đổi giá trị của ít nhất một thuộc tính bên trong tin nhắn, nếu có gọi api update nhứng không có dữ liệu gì bị thay đổi thì sẽ không có event) với tin nhắn có sender_id = user_id hoặc receiver_id = user_id, tức tin nhắn gửi từ hoặc gửi tới người dùng có id là user_id.

### private-NotificationChannel.User.{user_id} Hỗ trợ nhận thông báo realtime
Có thể bắt dược các event sau:
- NotificationCreated: Khi có một thông báo mơi được tạo ra với receiver_id = user_id, tức thông báo mới được tạo ra và gửi tới người dùng có id là user_id.
- NotificationUpdated: Khi có cập nhật thông báo (bắt buộc cập nhật phải có thay đổi giá trị của ít nhất một thuộc tính bên trong thông báo, nếu có gọi api update nhứng không có dữ liệu gì bị thay đổi thì sẽ không có event) với thông báo có receiver_id = user_id, tức tin nhắn gửi tới người dùng có id là user_id.

### Dùng thử từ chính backend-sever
1. Khởi động backend-sever:
``` sh
php artisan serve
```
2. Truy cập link http://127.0.0.1/test/listen để trải nghiệm [Pusher](https://pusher.com/) (Mở console log để biết được những gì đang xảy ra)

### Hướng dẫn sử dụng cụ cho người dùng React js
1. Cài đặt thư viện [Pusher](https://pusher.com/)
```sh
npm install pusher-js
```
2. Khi sử dụng cần khai báo thư viện
```js
import Pusher from 'pusher-js';
```
3. Tạo object pusher:
```js
var pusher = new Pusher('a13024e4824fe0c8b79c', {
  cluster: 'ap1',
  forceTLS: true,
  authEndpoint: "<tên miền của backend sever>/broadcasting/auth",
  auth: {
      headers: {
        Authorization: 'Bearer ' + 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzOTIyOTgxNywiZXhwIjoxNjM5ODM0NjE3LCJuYmYiOjE2MzkyMjk4MTcsImp0aSI6IlZGcDRUUlJFaGhOWGFjdTAiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.qRA0AwUGmW1xMEn-_JtXxmnbZ8Ox6fqeJfiRc17YO14' // Đây là access token
      },
  },
});
 ```
 4. Kết nối với các channel và nghe event thông quá object pusher vừa tạo
 ```js
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
 ```
    Code này đang nghe 2 channel:
    - MessageChannel.User.1
    - private-MessageChannel.User.1

## Thiết kế cơ sở dữ liệu <a name="database"></a>

![Sơ đồ thiết kế cơ sở dữ liệu](https://i.imgur.com/GSf4iHe.png)

## Bản quyền
Do sử dụng framework mã nguồn mở Laravel nên bản quyền sẽ theo [MIT license](https://opensource.org/licenses/MIT)
