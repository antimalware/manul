<?php

ob_start();
require_once("Localization.inc.php");
require_once("View.inc.php");
require_once("Writer.inc.php");
ob_end_clean();

class Auth
{
    function __construct()
    {
        global $project_tmp_dir;
        $this->configPath = $project_tmp_dir . '/config.php';
        $this->answerSentFlag = false;
    }

    function templateOutput($message, $error = '')
    {
        global $locals, $current_lang;
        $view = new View();

        define('PS_ACTIVE_' . strtoupper($current_lang), '');

        foreach ($locals as $lang) {
            define('PS_ACTIVE_' . strtoupper($lang), 'passive');
        }

        define('PS_PASS_SET', file_exists($this->configPath) ? '1' : '0');
        define('PS_AUTH_FORM_MESSAGE', $message);
        define('PS_AUTH_FORM_ERROR', $error);
        define('PS_LANG', $current_lang);
        define('PS_AUTH_FORM_TARGET', $_SERVER['REQUEST_URI']);

        $view->display("auth.tpl");
    }

    function checkPasswordStrength($password)
    {

        if (strlen($password) < 8) {
            return false;
        }

        $has_digit = preg_match("/\d+/", $password) ? 1 : 0;
        $has_uppercase = preg_match("/[A-Z]+/", $password) ? 1 : 0;
        $has_lowercase = preg_match("/[a-z]+/", $password) ? 1 : 0;
        $has_special = preg_match("/\W+/", $password) ? 1 : 0;

        $password_strength = $has_digit + $has_uppercase + $has_lowercase + $has_special;

        if ($password_strength < 3) {
            return false;
        }

        return true;

    }

    function tryToAuthenticate()
    {
        if (is_file($this->configPath)) {
            $password_hash = ''; // will be overriden in require
            require($this->configPath);

            if (!empty($_COOKIE['antimalware_password_hash'])) {
                $password_hash_from_cookie = $_COOKIE['antimalware_password_hash'];
                return ($password_hash_from_cookie == $password_hash);
            } elseif (!empty($_POST['password'])) {
                $password_hash_from_post = hash('sha256', $_POST['password']);
                if ($password_hash_from_post == $password_hash) {
                    setcookie('antimalware_password_hash', $password_hash_from_post);
                    $_COOKIE['antimalware_password_hash'] = $password_hash_from_post;
                    return true;
                }
            }
        }

        return false;
    }

    function setNewPassword($password)
    {
        $password_hash = hash('sha256', $password);
        $config_file_content = '<?php $password_hash = \'' . $password_hash . '\';';
        setcookie('antimalware_password_hash', $password_hash);
        $_COOKIE['antimalware_password_hash'] = $password_hash;
        file_put_contents2($this->configPath, $config_file_content);
    }

    function auth()
    {
        $result = false;
        $password_set = is_file($this->configPath);
        $password_sent = !empty($_POST['password']);
        if ($password_set) {
            if (!$this->tryToAuthenticate()) {
                if ($password_sent) {
                    $this->templateOutput(PS_ENTER_PASSWORD, PS_ERR_INVALID_PASSWORD);
                } else {
                    $this->templateOutput(PS_ENTER_PASSWORD);
                }
            } else {
                $result = true;
            }
        } else {
            if ($password_sent) {
                if ($this->checkPasswordStrength($_POST['password'])) {
                    $this->setNewPassword($_POST['password']);
                    $result = true;
                } else {
                    $this->templateOutput(PS_CHOOSE_STRONG_PASS, PS_ERR_SHORT_PASSWORD);
                }
            } else {
                $this->templateOutput(PS_CHOOSE_STRONG_PASS, '');
            }
        }

        return $result;
    }
}
