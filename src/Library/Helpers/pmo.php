<?php

if (!function_exists('vncore_session') && !in_array('vncore_session', config('helper_except', []))) {
    /**
     * [vncore_session description]
     *
     * @param   string  $str  [$str description]
     *
     * @return  [type]        [return description]
     */
    function vncore_session(string $str, $default = null)
    {
        switch ($str) {
            case 'partnerId':
                if ($default) {
                    return session($str, $default);
                } else {
                    return session($str, config('s-pmo.partnerId'));
                }
                break;
            case 'memberId':
                if ($default) {
                    return session($str, $default);
                } else {
                    return session($str, config('s-pmo.partnerId'));
                }
                break;
            
            default:
                return session($str, $default);
                break;
        }
    }
}


if (!function_exists('vncore_sync_user_mapping') && !in_array('vncore_sync_user_mapping', config('helper_except', []))) {
    /**
     * [vncore_sync_user_mapping description]
     *
     * @param   string  $str  [$str description]
     *
     * @return  [type]        [return description]
     */
    function vncore_sync_user_mapping(\Vncore\Core\Admin\Models\AdminUser $user, $action = "add")
    {
        switch ($action) {
            case 'add':
                $dataMapping = [
                    'original_id' => $user->id,
                    'partner_id' => config('s-pmo.partnerId'),
                    'nickname' => $user->username,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                ];
                return (new \App\CoreApp\Pmo\Models\PmoMember)->create($dataMapping);
                break;

            case 'delete':
                return (new \App\CoreApp\Pmo\Models\PmoMember)->where('partner_id', config('s-pmo.partnerId'))->where('original_id', $user->id)->delete();
                break;
            
            default:
                # code...
                break;
        }
    }
}


if (!function_exists('vncore_set_session_member_id') && !in_array('vncore_set_session_member_id', config('helper_except', []))) {
    /**
     * [vncore_set_session_member_id description]
     *
     * @param   string  $str  [$str description]
     *
     * @return  [type]        [return description]
     */
    function vncore_set_session_member_id(\Vncore\Core\Admin\Models\AdminUser $user)
    {
        $userMap =  (new \App\CoreApp\Pmo\Models\PmoMember)
            ->where('original_id', $user->id)
            ->where('partner_id', config('s-pmo.partnerId'))
            ->first();
        if ($userMap) {
            session(['memberId' => $userMap->id]);
            session(['partnerId' => config('s-pmo.partnerId')]);
        }
    }
}

if (!function_exists('vncore_check_permisson_comment') && !in_array('vncore_check_permisson_comment', config('helper_except', []))) {
    /**
     * [vncore_check_permisson_comment description]
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function vncore_check_permisson_comment(string $commendId, string $type = "edit")
    {
        $comment = (new \App\CoreApp\Pmo\Models\PmoTaskComment)
            ->where('id', $commendId)
            ->first();
        if ($comment->member_id == session('memberId')) {
            return true;
        }
    }
}

if (!function_exists('vncore_pmo_timesheet_check_permisson') && !in_array('vncore_pmo_timesheet_check_permisson', config('helper_except', []))) {
    /**
     * [vncore_pmo_timesheet_check_permisson description]
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function vncore_pmo_timesheet_check_permisson(string $timesheetId, string $type = "edit")
    {
        $comment = (new \App\CoreApp\Pmo\Models\PmoTaskTimesheet)
            ->where('id', $timesheetId)
            ->first();
        if ($comment->member_id == session('memberId')) {
            return true;
        }
    }
}

if (!function_exists('vncore_pmo_timesheet_can_log') && !in_array('vncore_pmo_timesheet_can_log', config('helper_except', []))) {
    /**
     * [vncore_pmo_timesheet_can_log description]
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function vncore_pmo_timesheet_can_log(string $taskId)
    {
        return (new \App\CoreApp\Pmo\Models\PmoTask)
            ->where('id', $taskId)
            ->first()
            ->assignes()
            ->pluck('member_id')
            ->contains(session('memberId'));
    }
}

if (!function_exists('vncore_pmo_task_status_finish') && !in_array('vncore_pmo_task_status_finish', config('helper_except', []))) {
    /**
     * [vncore_pmo_task_status_finish description]
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function vncore_pmo_task_status_finish()
    {
        return 8;
    }
}

if (!function_exists('vncore_pmo_task_status_protected') && !in_array('vncore_pmo_task_status_protected', config('helper_except', []))) {
    /**
     * [vncore_pmo_task_status_protected description]
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function vncore_pmo_task_status_protected()
    {
        return [1,2,3,4,5,6,7,8];
    }
}


if (!function_exists('vncore_pmo_project_status_finish') && !in_array('vncore_pmo_project_status_finish', config('helper_except', []))) {
    /**
     * [vncore_pmo_project_status_finish description]
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function vncore_pmo_project_status_finish()
    {
        return 3;
    }
}

if (!function_exists('vncore_pmo_project_status_protected') && !in_array('vncore_pmo_project_status_protected', config('helper_except', []))) {
    /**
     * [vncore_pmo_project_status_protected description]
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function vncore_pmo_project_status_protected()
    {
        return [1,2,3,4,5];
    }
}

if (!function_exists('vncore_pmo_task_activity_protected') && !in_array('vncore_pmo_task_activity_protected', config('helper_except', []))) {
    /**
     * [vncore_pmo_task_activity_protected description]
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function vncore_pmo_task_activity_protected()
    {
        return [1,2,3,4,5,6,7,8,9,10,11,12];
    }
}

if (!function_exists('vncore_pmo_event_task_change_status') && !in_array('vncore_pmo_event_task_change_status', config('helper_except', []))) {
    /**
     * [vncore_pmo_event_task_change_status description]
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function vncore_pmo_event_task_change_status($task, $newStatus)
    {
        //
    }
}