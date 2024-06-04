<?php

if (!function_exists('vc_check_view') && !in_array('vc_check_view', config('helper_except', []))) {
    /**
     * Check view exist
     *
     * @param   [string]  $view path view
     *
     * @return  [string]         [$domain]
     */
    function vc_check_view($view)
    {
        if (!view()->exists($view)) {
            vc_report('View not found '.$view);
            echo  vc_language_render('front.view_not_exist', ['view' => $view]);
            exit();
        }
    }
}


if (!function_exists('vc_clean') && !in_array('vc_clean', config('helper_except', []))) {
    /**
     * Clear data
     */
    function vc_clean($data = null, $exclude = [], $level_high = false)
    {
        if (is_array($data)) {
            array_walk($data, function (&$v, $k) use ($exclude, $level_high) {
                if (is_array($v)) {
                    $v = vc_clean($v, $exclude, $level_high);
                } 
                if (is_string($v)) {
                    if (in_array($k, $exclude)) {
                        $v = $v;
                    } else {
                        if ($level_high) {
                            $v = strip_tags($v);
                        }
                        $v = htmlspecialchars_decode($v);
                        $v = htmlspecialchars($v, ENT_COMPAT, 'UTF-8');
                    }
                }
            });
        }
        if (is_string($data)) {
            if ($level_high) {
                $data = strip_tags($data);
            }
            $data = htmlspecialchars_decode($data);
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }
        return $data;
    }
}
