<?php
use Vncore\Core\Front\Models\ShopEmailTemplate;

/**
 * Function process mapping validate contact form
 */
if (!function_exists('vc_contact_mapping_validate') && !in_array('vc_contact_mapping_validate', config('helper_except', []))) {
    function vc_contact_mapping_validate():array
    {
        $validate = [
            'name' => 'required',
            'title' => 'required',
            'content' => 'required',
            'email' => 'required|email',
            'phone' => config('validation.customer.phone_required', 'required|regex:/^0[^0][0-9\-]{6,12}$/'),
        ];
        $messages = [
            'name.required'    => vc_language_render('validation.required', ['attribute' => vc_language_render('contact.name')]),
            'content.required' => vc_language_render('validation.required', ['attribute' => vc_language_render('contact.content')]),
            'title.required'   => vc_language_render('validation.required', ['attribute' => vc_language_render('contact.subject')]),
            'email.required'   => vc_language_render('validation.required', ['attribute' => vc_language_render('contact.email')]),
            'email.email'      => vc_language_render('validation.email', ['attribute' => vc_language_render('contact.email')]),
            'phone.required'   => vc_language_render('validation.required', ['attribute' => vc_language_render('contact.phone')]),
            'phone.regex'      => vc_language_render('customer.phone_regex'),
        ];
        $dataMap['validate'] = $validate;
        $dataMap['messages'] = $messages;

        return $dataMap;
    }
}


/**
 * Send email contact form
 */
if (!function_exists('vc_contact_form_sendmail') && !in_array('vc_contact_form_sendmail', config('helper_except', []))) {
    function vc_contact_form_sendmail(array $data)
    {
        if (vc_config('contact_to_admin')) {
            $checkContent = (new ShopEmailTemplate)
                ->where('group', 'contact_to_admin')
                ->where('status', 1)
                ->first();
            if ($checkContent) {
                $content = $checkContent->text;
                $dataFind = [
                    '/\{\{\$title\}\}/',
                    '/\{\{\$name\}\}/',
                    '/\{\{\$email\}\}/',
                    '/\{\{\$phone\}\}/',
                    '/\{\{\$content\}\}/',
                ];
                $dataReplace = [
                    $data['title'],
                    $data['name'],
                    $data['email'],
                    $data['phone'],
                    $data['content'],
                ];
                $content = preg_replace($dataFind, $dataReplace, $content);
                $dataView = [
                    'content' => $content,
                ];

                $config = [
                    'to' => vc_store('email'),
                    'replyTo' => $data['email'],
                    'subject' => $data['title'],
                ];
                vc_send_mail('templates.' . vc_store('template') . '.mail.contact_to_admin', $dataView, $config, []);
            }
        }
    }
}