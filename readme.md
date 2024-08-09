<p align="center">
    <img src="https://vncore.net/logo.png?v=4" width="150">
</p>
<p align="center">Laravel admin | core backend for all systems (ecommerce, cms, pmo...)<br>
    <code><b>composer require vncore/core</b></code></p>
<p align="center">
 <a href="https://vncore.net">Installation and usage documentation</a>
</p>

<p align="center">
<a href="https://packagist.org/packages/vncore/core"><img src="https://poser.pugx.org/vncore/core/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/vncore/core"><img src="https://poser.pugx.org/vncore/core/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/vncore/core"><img src="https://poser.pugx.org/vncore/core/license.svg" alt="License"></a>
</p>

## About Vncore
Vncore is a compact source code built with Laravel, helping users quickly build a powerful admin website. Whether your system is simple or complex, Vncore will help you operate and scale it easily.

**What can Vncore do?**

- Provides a powerful and flexible role management and user group solution.
- Offers a synchronous authentication API, enhancing API security with additional layers.
- Build and manage Plugins/Templates that work in the system
- Comprehensive access log monitoring system.
- Continuously updates security vulnerabilities.
- Supports multiple languages, easy management.
- Vncore is FREE

**And more:**

- Vncore builds a large, open ecosystem (plugin, template), helping users quickly build CMS, PMO, eCommerce, etc., according to your needs.

## Website structure using Vncore

    Website-folder/
    ├── app
    │     └── Vncore
    │           ├── Helpers(+)
    │           └── Plugins(+)
    ├── public
    │     └── Vncore
    │           ├── Admin(+)
    │           ├── Templates(+)
    │           └── Plugins(+)
    ├── resources
    │     └── views
    │           └── Vncore
    │                 ├── Templates(+)
    │                 └── Plugins(+)
    ├── vendor
    │     └── vncore/core
    ├── .env
    └──...

## Support the project
Support this project :stuck_out_tongue_winking_eye: :pray:
<p align="center">
    <a href="https://www.paypal.me/LeLanh" target="_blank"><img src="https://img.shields.io/badge/Donate-PayPal-green.svg" data-origin="https://img.shields.io/badge/Donate-PayPal-green.svg" alt="PayPal Me"></a>
</p>

## Quick Installation Guide
- Step 1: Chuẩn bị source  laravel
Tham khảo `composer create-project laravel/laravel website-folder`
- Step 2: Cài đặt gói vncore/core
Di chuyển vào thư mục Laraval mới cài đặt, trong ví dụ là "website-folder"
Chạy lệnh `composer require vncore/core`
- Step 3: Kiểm tra cấu hình file .env
Chắc chắn các thông tin cấu hình databse và APP_KEY trong file .env đã đầy đủ.
Lệnh sau sẽ tạo giá trị APP_KEY neeuss chưa được thiết lập: `php artisan key:generate`
- Step 4: Khởi tạo vncore
Chạy lệnh `php artisan vncore:init`
Trường hợp lệnh khởi tạo bị lỗi, bạn có thể thực hiện copy thủ công các folder `vendor/vncore/core/src/public` tới `website-folder/public`
- Step 5: Cài đặt vncore
Truy cập url `your-domain/vncore-install.php` để tiến hành cài đặt.
Lưu ys: Khi cài đặt hoàn tất, file `website-folder/public/vncore-install.php` sẽ bị đổi tên thành `website-folder/public/vncore-install.vncore`.
Nếu quá trình đổi tên thất bại, bạn có thể đổi tên (hoặc xóa) file này thủ công.


## Quickly disable Vncore and plugins
Just add the variable `VNCORE_ACTIVE=0` to the `.env` file
