<?php
global $wpdb;
class WPMail {
    public static function sendEmail($to, $subject, $template, $template_variables) {
        $message = self::prepareMessage($template, $template_variables);
        if (!wp_mail($to, $subject, $message)) {
            return false;
        }
        return true;
    }
    private static function prepareMessage($template, $template_variables) {
        $message = self::getMailTemplate($template);
        return self::replaceMailVariables($message, $template_variables);
    }
    private static function getMailTemplate($template) {
        ob_start();
        include(__DIR__."/MailTemplates/TestMail.html");
        $message = ob_get_contents();
        ob_end_clean();
        return $message;
    }
    private static function replaceMailVariables($message, $template_variables) {
        $site_name = 'SiteName';
        if(function_exists('get_bloginfo')) {
            $site_name = get_bloginfo('name');
        }
        $default = array('year' => date("Y"), 'site_name' => $site_name);
        $template_variables = array_merge($template_variables, $default);
        foreach($template_variables AS $key=>$val) {
            $search = '{{'.$key.'}}';
            $message = str_replace($search, $val, $message);
        }
        return $message;
    }
}
/*$template_variables = array();
$template_variables = ['user_name' => 'Ivan'];
echo WPMail::sendEmail('sample', 'subject', 'TestMail.html', $template_variables);*/
?>