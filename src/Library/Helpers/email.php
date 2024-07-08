<?php
use Vncore\Core\Mail\SendMail;
use Vncore\Core\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Mail;

/**
 * Function send mail
 * Mail queue to run need setting crontab for php artisan schedule:run
 *
 * @param   [string]  $view            Path to view
 * @param   array     $dataView        Content send to view
 * @param   array     $emailConfig     to, cc, bbc, subject..
 * @param   array     $attach      Attach file
 *
 * @return  mixed
 */
if (!function_exists('vncore_send_mail') && !in_array('vncore_send_mail', config('helper_except', []))) {
    function vncore_send_mail($view, array $dataView = [], array $emailConfig = [], array $attach = [])
    {
        if (!empty(vncore_config('email_action_mode'))) {
            if (!empty(vncore_config('email_action_queue'))) {
                dispatch(new SendEmailJob($view, $dataView, $emailConfig, $attach));
            } else {
                vncore_process_send_mail($view, $dataView, $emailConfig, $attach);
            }
        } else {
            return false;
        }
    }
}
/**
 * Process send mail
 *
 * @param   [type]  $view         [$view description]
 * @param   array   $dataView     [$dataView description]
 * @param   array   $emailConfig  [$emailConfig description]
 * @param   array   $attach       [$attach description]
 *
 * @return  [][][]                [return description]
 */
if (!function_exists('vncore_process_send_mail') && !in_array('vncore_process_send_mail', config('helper_except', []))) {
    function vncore_process_send_mail($view, array $dataView = [], array $emailConfig = [], array $attach = [])
    {
        try {
            Mail::send(new SendMail($view, $dataView, $emailConfig, $attach));
        } catch (\Throwable $e) {
            vncore_report("Sendmail view:" . $view . PHP_EOL . $e->getMessage());
        }
    }
}
