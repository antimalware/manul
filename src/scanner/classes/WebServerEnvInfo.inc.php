<?php

class WebServerEnvInfo
{
    public $script_filename;
    public $document_root;
    public $http_host;
    public $admin_email;
    public $time;
    public $server_addr;
    public $server_software;
    public $server_gateway;
    public $server_signature;
    public $server_hostname;
    public $server_platform;
    public $server_architecture;
    public $username;
    public $pathinfo;
    public $phpinfo;

    function getServerVar($serverVarName)
    {
        return empty($_SERVER[$serverVarName]) ? 'None' : $_SERVER[$serverVarName];
    }

    public function __construct()
    {
        $this->script_filename = $this->getServerVar("SCRIPT_FILENAME");
        $this->document_root = $this->getServerVar("DOCUMENT_ROOT");
        $this->http_host = $this->getServerVar("HTTP_HOST");
        $this->admin_email = $this->getServerVar("SERVER_ADMIN");
        $this->time = date("Y.m.d H:i:s", $this->getServerVar("REQUEST_TIME"));
        $this->server_addr = $this->getServerVar("SERVER_ADDR");
        $this->server_software = $this->getServerVar("SERVER_SOFTWARE");
        $this->server_gateway = $this->getServerVar("GATEWAY_INTERFACE");
        $this->server_signature = $this->getServerVar("SERVER_SIGNATURE");

        $this->server_hostname = @php_uname('n');
        $this->server_platform = @php_uname('s') . " " . @php_uname('r') . " " . @php_uname('v');
        $this->server_architecture = @php_uname('m');
        $this->username = "uid: " . @getmyuid() . ", gid: " . @getmygid();
        $this->pathinfo = getcwd();
        $this->phpinfo = $this->getCompactPhpInfo();
    }

    public function getXMLNode()
    {
        $dom = new DOMDocument("1.0", "utf-8");
        $server_env_node = $dom->createElement("server_environment");
        $server_env_node->appendChild($dom->createElement("script_filename", $this->script_filename));
        $server_env_node->appendChild($dom->createElement("document_root", $this->document_root));
        $server_env_node->appendChild($dom->createElement("http_host", $this->http_host));
        $server_env_node->appendChild($dom->createElement("admin_email", $this->admin_email));
        $server_env_node->appendChild($dom->createElement("time", $this->time));
        $server_env_node->appendChild($dom->createElement("server_addr", $this->server_addr));
        $server_env_node->appendChild($dom->createElement("software", $this->server_software));
        $server_env_node->appendChild($dom->createElement("server_gateway", $this->server_gateway));
        $server_env_node->appendChild($dom->createElement("server_signature", $this->server_signature));
        $server_env_node->appendChild($dom->createElement("server_hostname", $this->server_hostname));
        $server_env_node->appendChild($dom->createElement("platform_name", $this->server_platform));
        $server_env_node->appendChild($dom->createElement("server_architecture", $this->server_architecture));
        $server_env_node->appendChild($dom->createElement("username", $this->username));
        $server_env_node->appendChild($dom->createElement("path", $this->pathinfo));
        $server_env_node->appendChild($dom->createElement("phpinfo", $this->phpinfo));
        $dom->appendChild($server_env_node);
        return $dom->getElementsByTagName("server_environment")->item(0);
    }

    function extractValue(&$par_Str, $par_Name)
    {
        if (preg_match('|<tr><td class="e">\s*' . $par_Name . '\s*</td><td class="v">(.+?)</td>|sm', $par_Str, $l_Result)) {
            return str_replace('no value', '', strip_tags($l_Result[1]));
        }
    }

    function getCompactPhpInfo()
    {
        if (function_exists('phpinfo') && is_callable('phpinfo')) {
            ob_start();
            phpinfo();
            $PhpInfo = ob_get_contents();
            ob_end_clean();

            $PhpInfo = str_replace('border: 1px', '', $PhpInfo);
            preg_match('|<body>(.*)</body>|smiu', $PhpInfo, $PhpInfoBody);

            $PhpInfoSystem = $this->extractValue($PhpInfo, 'System');
            $PhpPHPAPI = $this->extractValue($PhpInfo, 'Server API');
            $AllowUrlFOpen = $this->extractValue($PhpInfo, 'allow_url_fopen');
            $AllowUrlInclude = $this->extractValue($PhpInfo, 'allow_url_include');
            $DisabledFunction = $this->extractValue($PhpInfo, 'disable_functions');
            $DisplayErrors = $this->extractValue($PhpInfo, 'display_errors');
            $ErrorReporting = $this->extractValue($PhpInfo, 'error_reporting');
            $ExposePHP = $this->extractValue($PhpInfo, 'expose_php');
            $LogErrors = $this->extractValue($PhpInfo, 'log_errors');
            $MQGPC = $this->extractValue($PhpInfo, 'magic_quotes_gpc');
            $MQRT = $this->extractValue($PhpInfo, 'magic_quotes_runtime');
            $OpenBaseDir = $this->extractValue($PhpInfo, 'open_basedir');
            $RegisterGlobals = $this->extractValue($PhpInfo, 'register_globals');
            $SafeMode = $this->extractValue($PhpInfo, 'safe_mode');
            $IniPath = $this->extractValue($PhpInfo, 'Loaded Configuration File');
            $FixPath = $this->extractValue($PhpInfo, 'cgi.fix_pathinfo');

            $DisabledFunction = ($DisabledFunction == '' ? '-?-' : $DisabledFunction);
            $OpenBaseDir = ($OpenBaseDir == '' ? '-?-' : $OpenBaseDir);

            $Result = 'Version: ' . phpversion() . "<br/>";
            $Result .= 'System Version: ' . $PhpInfoSystem . "<br/>";
            $Result .= 'PHP API: ' . $PhpPHPAPI . "<br/>";
            $Result .= 'allow_url_fopen: ' . $AllowUrlFOpen . "<br/>";
            $Result .= 'allow_url_include: ' . $AllowUrlInclude . "<br/>";
            $Result .= 'disable_functions: ' . str_replace('&nbsp;', ' ', $DisabledFunction) . "<br/>";
            $Result .= 'display_errors: ' . $DisplayErrors . "<br/>";
            $Result .= 'error_reporting: ' . $ErrorReporting . "<br/>";
            $Result .= 'expose_php: ' . $ExposePHP . "<br/>";
            $Result .= 'log_errors: ' . $LogErrors . "<br/>";
            $Result .= 'magic_quotes_gpc: ' . $MQGPC . "<br/>";
            $Result .= 'magic_quotes_runtime: ' . $MQRT . "<br/>";
            $Result .= 'register_globals: ' . $RegisterGlobals . "<br/>";
            $Result .= 'open_basedir: ' . $OpenBaseDir . "<br/>";
            $Result .= 'Ini Path: ' . $IniPath . "<br/>";
            $Result .= 'CGI.FixPathInfo: ' . $FixPath . "<br/>";

            $Result .= 'safe_mode: ' . $SafeMode . "<br/>";
        } else {
            $Result = 'phpinfo() is disabled' . "<br/>";
        }

        $CmdList = explode(',', 'popen,exec,ftp_exec,system,passthru,get_current_user,proc_open,shell_exec,ini_restore,getmygid,dl,symlink,chgrp,ini_set,putenv,extension_loaded,getmyuid,fsockopen,posix_setuid,posix_setsid,posix_setpgid,posix_kill,apache_child_terminate,chmod,chdir,pcntl_exec,phpinfo,virtual,proc_close,proc_get_status,proc_terminate,proc_nice,proc_getstatus,proc_close,escapeshellcmd,escapeshellarg,show_source,pclose,safe_dir,dl,ini_restore,chown,chgrp,shown_source,mysql_list_dbs,get_current_user,getmyid,leak,pfsockopen');

        $Result .= "\nList of enabled functions: ";
        foreach ($CmdList as $F) {
            if (function_exists($F) && is_callable($F)) {
                $Result .= $F . ' ';
            }
        }

        $Result .= "<br/>List of disabled functions: ";
        foreach ($CmdList as $F) {
            if (!(function_exists($F) && is_callable($F))) {
                $Result .= $F . ' ';
            }
        }

        return $Result;
    }

    public function __toString()
    {
        return implode(';', array(
            $this->script_filename,
            $this->document_root,
            $this->http_host,
            $this->admin_email,
            $this->time,
        ));
    }
}
