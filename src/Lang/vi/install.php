<?php
return [
    'info' => [
        'about' => 'PMO system là một dự án website thương mại điện tử miễn phí cho doanh nghiệp, được xây dựng trên nền tảng Laravel framework (PHP & Mysql).',
        'about_pro' => 'Mã nguồn website thương mại điện tử doành cho doanh nghiệp!',
        'about_us' => '- Để hiểu thêm về chúng tôi, hãy ghé thăm <a target="_new" href="https://PMO system.org/">Trang chủ của PMO system</a>',
        'document' => '- Tài liệu hướng dẫn cài đặt <a target="_new" href="https://PMO system.org/docs/master/installation.html">TẠI ĐÂY</a>',
        'version' => 'Phiên bản',
        'terms' => '<span style="color:red">*</span> Vui lòng đọc điều kiện trước khi cài đặt <a target="_new" href="https://PMO system.org/license.html">Ở ĐÂY</a>.',
        'terms_pro' => '<span style="color:red">*</span> Vui lòng đọc điều kiện trước khi cài đặt <a target="_new" href="https://PMO system.org/pro.html">Ở ĐÂY</a>.',
    ],
    'env' => [
        'process' => 'Đang tạo file .env',
        'error_open' => 'Không thể mở file .env.example',
        'process_sucess' => 'Tạo file .env thành công!',
        'error' => 'Lỗi trong khi tạo file .env',
        'nofound' => 'Không tìm thấy file .env.expample!',
    ],
    'key' => [
        'process' => 'Đang tạo API key',
        'process_sucess' => 'Tạo API key thành công!',
        'error' => 'Lỗi trog khi tạo API key',
    ],
    'database' => [
        'process_sucess' => 'Cài đặt dữ liệu thành công!',
        'process_sucess_1' => 'Tạo dữ liệu admin thành công!',
        'process_sucess_2' => 'Tạo dữ liệu cửa hàng thành công!',
        'process_sucess_3' => 'Thêm dữ liệu mặc định thành công!',
        'process_sucess_4' => 'Thêm dữ liệu địa phương thành công!',
        'process_sucess_5' => 'Thêm dữ liệu mẫu thành công!',
        'file_notfound' => 'Không tìm thấy file .sql',
        'error' => 'Lỗi trong khi cài đặt dữ liệu',
        'error_1' => 'Lỗi trong khi tạo bảng admin',
        'error_2' => 'Lỗi trong khi tạo bảng shop',
        'error_3' => 'Lỗi trong khi thêm dữ liệu mặc định',
        'error_4' => 'Lỗi trong khi thêm dữ liệu địa phương',
        'error_5' => 'Lỗi trong khi thêm dữ liệu mẫu',

    ],
    'permission' => [
        'process' => 'Thiết lập quyền các thư mục',
        'process_sucess' => 'Thiết lập quyền thành công!',
        'error' => 'Lỗi trong khi thiết lập quyền các thư mục',
    ],
    'complete' => [
        'process' => 'Chuẩn bị để kết thúc',
        'process_success' => 'Hoàn tất!',
        'error' => 'Có lỗi trong khi kết thúc công việc',
    ],

    'validate' => [
        'database_port_number' => 'Port của dữ liệu là số',
        'database_port_required' => 'Port dữ liệu là bắt buộc',
        'database_host_required' => 'Địa chỉ máy chủ dữ liệu là bbawts buộc',
        'database_name_required' => 'Tên dữ liệu là bắt buộc',
        'database_user_required' => 'Tài khoản kết nối dữ liệu bắt buộc',
        'admin_url_required' => 'Đường dẫn tới admin bắt buộc',
        'admin_user_required' => 'Người dùng quản trị bắt buộc',
        'admin_password_required' => 'Mật khẩu quản trị bắt buộc',
        'admin_email_required' => 'Email quản trị bắt buộc',
        'timezone_default_required' => 'Múi giờ bắt buộc',
        'language_default_required' => 'Ngôn ngữ mặc định bắt buộc',
    ],
    'installing_button' => 'Đang cài đặt PMO system',
    'database_host' => 'Máy chủ dữ liệu',
    'database_port' => 'Port dữ liệu',
    'database_name' => 'Tên dữ liệu',
    'database_user' => 'Tài khoản dữ liệu',
    'database_password' => 'Mật khẩu dữ liệu',
    'database_prefix' => 'Tiền tố bảng dữ liệu',
    'database_prefix_help' => 'Ví dụ: sc_, abc_',
    'admin_url' => 'Link admin',
    'admin_user' => 'Tài khoản admin',
    'admin_password' => 'Mật khẩu admin',
    'admin_email' => 'Email admin',
    'language_default' => 'Ngôn ngữ',
    'timezone_default' => 'Múi giờ',
    'title' => 'Cài đặt PMO system',
    'installing' => 'Bắt đầu cài đặt',
    'rename_error' => 'Không thể đổi tên tập tin install.php. Vui lòng xóa hoặc đổi tên nó thủ công!',
    'terms' => '<span style="color:red">*</span> Đồng ý với quy định cài đặt',
    'requirement_check' => 'Kiểm tra điều kiện',
    'check_extension' => 'Kiểm tra extension',
    'check_writable' => 'Kiểm tra quyền ghi',
    'check_failed' => 'Thất bại',
    'check_ok' => 'OK',
    'drop_db' => 'Xóa bảng dữ liệu nếu có sẵn',
    'exclude_sample' => 'Không bao gồm dữ liệu mẫu',
    'website_title' => 'Tên website',
    'website_title_place' => 'Tên website của bạn',
];
