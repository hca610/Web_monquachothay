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
Tạo 1 schema trong database với tên ```Web_monquachothay```

Quay lại terminal, nhập lệnh sau để sinh data:  
``` sh
php artisan migrate && php artisan db:seed
```
Bật server:
``` sh
php artisan serve
```
