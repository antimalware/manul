<?php

ob_start();
require_once("Localization.inc.php");
require_once("View.inc.php");
require_once("Writer.inc.php");
ob_end_clean();


class Auth
{
     function __construct() {      
         global $project_tmp_dir;  
         $this->configPath = $project_tmp_dir . '/config.php';         
         $this->answerSentFlag = false;
     }


     function templateOutput($message) {
         global $locals, $current_lang;
         $view = new View();

         define('PS_ACTIVE_' . strtoupper($current_lang), '');

         foreach ($locals as $lang) {
            define('PS_ACTIVE_' . strtoupper($lang), 'passive');
         }


         define('PS_PASS_SET', file_exists($this->configPath) ? '1' : '0');
         define('PS_AUTH_FORM_MESSAGE', $message);
         define('PS_LANG', $current_lang);
         define('PS_AUTH_FORM_TARGET', $_SERVER['REQUEST_URI']);
         $view->display("auth.tpl");
     }

	 function checkPasswordStrength($password) {

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

	function setPassword($password) {

        $password_hash = hash('sha256', $password);
		$config_file_content = '<?php $password_hash = \'' . $password_hash . '\';';

		setcookie("antimalware_password_hash", $password_hash);

		file_put_contents2($this->configPath , $config_file_content);
	}

	function showWeakPasswordView() {
		$this->templateOutput(PS_ERR_SHORT_PASSWORD);
        $this->answerSentFlag = true;
    }

	function showWrongPasswordView() {
		$this->templateOutput(PS_ERR_INVALID_PASSWORD);
        $this->answerSentFlag = true;
    }

	function showAuthView() {
		$this->templateOutput(PS_ENTER_PASSWORD);
        $this->answerSentFlag = true;
    }

	function showSetPasswordView() {
		$this->templateOutput(PS_CHOOSE_STRONG_PASS);
        $this->answerSentFlag = true;
    }


	function trySetPassword() {

        if (empty($_POST['password']) || is_file($this->configPath)) {
             return false;
        }
	    
 
        $password = $_POST['password'];

		if ($this->checkPasswordStrength($password)) {     
        	$this->setPassword($password);
            return true;
        } else {
            $this->showWeakPasswordView();
        }
	    
        return false;
    }

      
	function tryValidatePasswordFromForm() {


        if (empty($_POST['password']) || !is_file($this->configPath))
            return false;

        require($this->configPath);
        
        $password_hash_from_form = hash('sha256', $_POST['password']);

        if ($password_hash_from_form == $password_hash) { 
			setcookie("antimalware_password_hash", $password_hash);
            return true;        
        } else {
            $this->showWrongPasswordView();
        }
                
        return false;
	}

	function tryValidatePasswordFromCookie() {
        if (empty($_COOKIE['antimalware_password_hash']) || !is_file($this->configPath))
            return false;

        require($this->configPath);

        $password_hash_from_cookie = $_COOKIE['antimalware_password_hash'];
        if ($password_hash_from_cookie == $password_hash) { 
            return true;        
		} else {
            $this->showWrongPasswordView();
        }
        
        return false;

	}
    
	function auth() {


        if($this->trySetPassword())
            return true;


        if ($this->tryValidatePasswordFromForm() || $this->tryValidatePasswordFromCookie()) 
            return true;
        
        #If password was not specified
        if ($this->answerSentFlag)
			return false;

        if (is_file($this->configPath)) {
            $this->showAuthView();
        } else {
            $this->showSetPasswordView();            
        }

        return false;

	}

}
