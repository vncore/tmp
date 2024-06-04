<?php
if (!function_exists('vc_notice_add')) {
    /**
     * [vc_notice_add description]
     *
     * @param   string  $type    [$type description]
     * @param   string  $typeId  [$typeId description]
     *
     * @return  [type]           [return description]
     */
    function vc_notice_add(string $type, string $typeId)
    {
        $modelNotice = new Vncore\Core\Admin\Models\AdminNotice;
        $content = '';
        $admins = [];
        switch ($type) {
            case 'vc_customer_created':
                $admins = vc_admin_notice_get_admin($type);
                $content = "admin_notice.customer.new";
                break;
            case 'vc_order_created':
                $admins = vc_admin_notice_get_admin($type);
                $content = "admin_notice.order.new";
                break;
            case 'vc_order_success':
                $admins = vc_admin_notice_get_admin($type);
                $content = "admin_notice.order.success";
                break;
            case 'vc_order_update_status':
                $admins = vc_admin_notice_get_admin($type);
                $content = "admin_notice.order.update_status";
                break;
            
            default:
                $admins = vc_admin_notice_get_admin($type);
                $content = $type;
                break;
        }
        if (count($admins)) {
            foreach ($admins as $key => $admin) {
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
    if (!function_exists('vc_admin_notice_get_admin')) {
        function vc_admin_notice_get_admin(string $type = "")
        {
            if (function_exists('vc_admin_notice_pro_get_admin')) {
                return vc_admin_notice_pro_get_admin($type);
            }

            return (new \Vncore\Core\Admin\Models\AdminUser)
            ->selectRaw('distinct '. SC_DB_PREFIX.'admin_user.id')
            ->join(SC_DB_PREFIX . 'admin_role_user', SC_DB_PREFIX . 'admin_role_user.user_id', SC_DB_PREFIX . 'admin_user.id')
            ->join(SC_DB_PREFIX . 'admin_role', SC_DB_PREFIX . 'admin_role.id', SC_DB_PREFIX . 'admin_role_user.role_id')
            ->whereIn(SC_DB_PREFIX . 'admin_role.slug', ['administrator','view.all', 'manager'])
            ->pluck('id')
            ->toArray();
        }
    }

}
