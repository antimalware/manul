<?php

ob_start();
require_once('Localization.inc.php');
require_once('View.inc.php');
require_once('Writer.inc.php');
ob_end_clean();


class Auth
{
    function __construct()
    {
        global $projectTmpDir;
        $this->passwordHashFilepath = $projectTmpDir . '/password_hash.php';
        $this->answerSentFlag = false;
    }

    function templateOutput($message, $error = '')
    {
        global $locals, $currentLang;
        $view = new View();

        define('PS_ACTIVE_' . strtoupper($currentLang), '');

        foreach ($locals as $lang) {
            define('PS_ACTIVE_' . strtoupper($lang), 'passive');
        }

        define('PS_PASS_SET', file_exists($this->passwordHashFilepath) ? '1' : '0');
        define('PS_AUTH_FORM_MESSAGE', $message);
        define('PS_AUTH_FORM_ERROR', $error);
        define('PS_LANG', $currentLang);
        define('PS_AUTH_FORM_TARGET', $_SERVER['REQUEST_URI']);

        $view->display('auth.tpl');
    }

    function checkPasswordStrength($password)
    {
        if (strlen($password) < 8) {
            return false;
        }

        $hasDigit = preg_match("/\d+/", $password) ? 1 : 0;
        $hasUppercase = preg_match("/[A-Z]+/", $password) ? 1 : 0;
        $hasLowercase = preg_match("/[a-z]+/", $password) ? 1 : 0;
        $hasSpecial = preg_match("/\W+/", $password) ? 1 : 0;

        $passwordStrength = $hasDigit + $hasUppercase + $hasLowercase + $hasSpecial;

        if ($passwordStrength < 3) {
            return false;
        }

        return true;
    }

    function tryToAuthenticate()
    {
        if (is_file($this->passwordHashFilepath)) {
            $passwordHash = file_get_contents($this->passwordHashFilepath);
            if (!empty($_COOKIE['antimalware_password_hash'])) {
                $passwordHashFromCookie = $_COOKIE['antimalware_password_hash'];
                return ($passwordHashFromCookie === $passwordHash);
            } elseif (!empty($_POST['password'])) {
                $passwordHashFromPost = hash('sha256', $_POST['password']);
                if ($passwordHashFromPost === $passwordHash) {
                    $cookieExpirationTimestamp = time() + 86400;
                    //TODO: apply cookie path to manul dir
                    $cookiePath = '/';
                    $httpOnly = true;
                    setcookie('antimalware_password_hash', $passwordHash, $cookieExpirationTimestamp,
                              $cookiePath, null, null, $httpOnly);
                    $_COOKIE['antimalware_password_hash'] = $passwordHash;
                    return true;
                }
            }
        }
        return false;
    }

    function setNewPassword($password)
    {
        $passwordHash = hash('sha256', $password);
        $cookieExpirationTimestamp = time() + 86400;
        //TODO: apply cookie path to manul dir
        $cookiePath = '/';
        $httpOnly = true;
        setcookie('antimalware_password_hash', $passwordHash, $cookieExpirationTimestamp,
                   $cookiePath, null, null, $httpOnly);
        $_COOKIE['antimalware_password_hash'] = $passwordHash;
        file_put_contents2($this->passwordHashFilepath, $passwordHash);
    }

    function auth()
    {
        $result = false;
        $isPasswordSet = is_file($this->passwordHashFilepath);
        $isPasswordSent = !empty($_POST['password']);
        if ($isPasswordSet) {
            if (!$this->tryToAuthenticate()) {
                if ($isPasswordSent) {
                    $this->templateOutput(PS_ENTER_PASSWORD, PS_ERR_INVALID_PASSWORD);
                } else {
                    $this->templateOutput(PS_ENTER_PASSWORD);
                }
            } else {
                $result = true;
            }
        } else {
            if ($isPasswordSent) {
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
