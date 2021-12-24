## Mục lục
* [Cài đặt Backend-sever](#backend-sever)
* [Tài liệu api hệ thống](#api)
* [Tài liệu sử dụng web-socket](#web-socket)
* [Thiết kế cơ sở dữ liệu](#database)

## Tài liệu api hệ thống <a name="api"></a>
[https://documenter.getpostman.com/view/18333728/UVRDF5dA](https://documenter.getpostman.com/view/18333728/UVRDF5dA)

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

## Bản quyền
Do sử dụng framework mã nguồn mở Laravel nên bản quyền sẽ theo [MIT license](https://opensource.org/licenses/MIT)
