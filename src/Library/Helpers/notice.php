<?php
if (!function_exists('vncore_notice_add')) {
    /**
     * [vncore_notice_add description]
     *
     * @param   string  $type    [$type description]
     * @param   string  $typeId  [$typeId description]
     *
     * @return  [type]           [return description]
     */
    function vncore_notice_add(string $type, string $typeId)
    {
        $modelNotice = new Vncore\Core\Admin\Models\AdminNotice;
        $content = '';
        $listAdmin = [];
        switch ($type) {
            case 'vncore_customer_created':
                $listAdmin = vncore_admin_notice_get_admin($type);
                $content = "admin_notice.customer.new";
                break;
            case 'vncore_order_created':
                $listAdmin = vncore_admin_notice_get_admin($type);
                $content = "admin_notice.order.new";
                break;
            case 'vncore_order_success':
                $listAdmin = vncore_admin_notice_get_admin($type);
                $content = "admin_notice.order.success";
                break;
            case 'vncore_order_update_status':
                $listAdmin = vncore_admin_notice_get_admin($type);
                $content = "admin_notice.order.update_status";
                break;
            
            default:
                $listAdmin = vncore_admin_notice_get_admin($type);
                $content = $type;
                break;
        }
        if (count($listAdmin)) {
            foreach ($listAdmin as $key => $admin) {
                $modelNotice->create(
                    [
                        'type' => $type,
                        'type_id' => $typeId,
                        'admin_id' => $admin,
                        'content' => $content
                    ]
                );
            }
        }

    }

    /**
     * Get list id admin can get notice
     */
    if (!function_exists('vncore_admin_notice_get_admin')) {
        function vncore_admin_notice_get_admin(string $type = "")
        {
            if (function_exists('vncore_admin_notice_pro_get_admin')) {
                return vncore_admin_notice_pro_get_admin($type);
            }

            return (new \Vncore\Core\Admin\Models\AdminUser)
            ->selectRaw('distinct '. VNCORE_DB_PREFIX.'admin_user.id')
            ->join(VNCORE_DB_PREFIX . 'admin_role_user', VNCORE_DB_PREFIX . 'admin_role_user.user_id', VNCORE_DB_PREFIX . 'admin_user.id')
            ->join(VNCORE_DB_PREFIX . 'admin_role', VNCORE_DB_PREFIX . 'admin_role.id', VNCORE_DB_PREFIX . 'admin_role_user.role_id')
            ->whereIn(VNCORE_DB_PREFIX . 'admin_role.slug', ['administrator','view.all', 'manager'])
            ->pluck('id')
            ->toArray();
        }
    }

}
