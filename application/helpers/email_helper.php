<?php
function sentmail($to = null, $subject = null, $mail_body = null)
{
    $CI = &get_instance();
    $config = array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.hostinger.com',
        'smtp_port' => 465, //465
        'smtp_user' => 'demo@techner.tech',
        'smtp_pass' => 'Qwerty_123',
        'mailtype' => 'html',
        'charset' => 'iso-8859-1',
    );
    $CI->load->library('email', $config);
    $CI->email->set_newline("\r\n");
    $CI->email->initialize($config);
    $CI->email->from($config['smtp_user']);
    $CI->email->to($to);
    $CI->email->subject($subject);
    $CI->email->message($mail_body);
    $res = $CI->email->send();
    //  echo $CI->email->print_debugger();
    //  die;
    if ($res) {
        return true;
    } else {
        return false;
    }
}
