<?php

class WebServerEnvInfo
{

    private function getServerVar($serverVarName)
    {
        return empty($_SERVER[$serverVarName]) ? 'None' : $_SERVER[$serverVarName];
    }

    public function __construct()
    {

        $this->scriptFilename = $this->getServerVar('SCRIPT_FILENAME');
        $this->documentRoot = $this->getServerVar('DOCUMENT_ROOT');
        $this->httpHost = $this->getServerVar('HTTP_HOST');
        $this->adminEmail = $this->getServerVar('SERVER_ADMIN');
        $this->time = date('Y.m.d H:i:s', $this->getServerVar('REQUEST_TIME'));
        $this->serverAddr = $this->getServerVar('SERVER_ADDR');
        $this->serverSoftware = $this->getServerVar('SERVER_SOFTWARE');
        $this->serverGateway = $this->getServerVar('GATEWAY_INTERFACE');
        $this->serverSignature = $this->getServerVar('SERVER_SIGNATURE');

        $this->serverHostname = @php_uname('n');
        $this->serverPlatform = @php_uname('s') . ' ' . @php_uname('r') . ' ' . @php_uname('v');
        $this->serverArchitecture = @php_uname('m');
        $this->username = 'uid: ' . @getmyuid() . ', gid: ' . @getmygid();
        $this->pathinfo = getcwd();
        $this->phpinfo = $this->getCompactPhpInfo();
    }

    public function getXMLNode()
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $serverEnvNode = $dom->createElement('server_environment');
        $serverEnvNode->appendChild($dom->createElement('script_filename', $this->scriptFilename));
        $serverEnvNode->appendChild($dom->createElement('document_root', $this->documentRoot));
        $serverEnvNode->appendChild($dom->createElement('http_host', $this->httpHost));
        $serverEnvNode->appendChild($dom->createElement('admin_email', $this->adminEmail));
        $serverEnvNode->appendChild($dom->createElement('time', $this->time));
        $serverEnvNode->appendChild($dom->createElement('server_addr', $this->serverAddr));
        $serverEnvNode->appendChild($dom->createElement('software', $this->serverSoftware));
        $serverEnvNode->appendChild($dom->createElement('server_gateway', $this->serverGateway));
        $serverEnvNode->appendChild($dom->createElement('server_signature', $this->serverSignature));
        $serverEnvNode->appendChild($dom->createElement('server_hostname', $this->serverHostname));
        $serverEnvNode->appendChild($dom->createElement('platform_name', $this->serverPlatform));
        $serverEnvNode->appendChild($dom->createElement('server_architecture', $this->serverArchitecture));
        $serverEnvNode->appendChild($dom->createElement('username', $this->username));
        $serverEnvNode->appendChild($dom->createElement('path', $this->pathinfo));
        $serverEnvNode->appendChild($dom->createElement('phpinfo', $this->phpinfo));
        $dom->appendChild($serverEnvNode);
        return $dom->getElementsByTagName('server_environment')->item(0);
    }

    private function extractValue(&$parStr, $parName)
    {
        if (preg_match('|<tr><td class="e">\s*' . $parName . '\s*</td><td class="v">(.+?)</td>|sm', $parStr, $lResult)) {
            return str_replace('no value', '', strip_tags($lResult[1]));
        }
    }

    private function getCompactPhpInfo()
    {
        if (function_exists('phpinfo') && is_callable('phpinfo')) {
            ob_start();
            phpinfo();
            $phpInfo = ob_get_contents();
            ob_end_clean();

            $phpInfo = str_replace('border: 1px', '', $phpInfo);
            preg_match('|<body>(.*)</body>|smiu', $phpInfo, $PhpInfoBody);

            $phpInfoSystem = $this->extractValue($phpInfo, 'System');
            $phpPHPAPI = $this->extractValue($phpInfo, 'Server API');
            $allowUrlFOpen = $this->extractValue($phpInfo, 'allow_url_fopen');
            $allowUrlInclude = $this->extractValue($phpInfo, 'allow_url_include');
            $disabledFunction = $this->extractValue($phpInfo, 'disable_functions');
            $displayErrors = $this->extractValue($phpInfo, 'display_errors');
            $errorReporting = $this->extractValue($phpInfo, 'error_reporting');
            $exposePHP = $this->extractValue($phpInfo, 'expose_php');
            $logErrors = $this->extractValue($phpInfo, 'log_errors');
            $MQGPC = $this->extractValue($phpInfo, 'magic_quotes_gpc');
            $MQRT = $this->extractValue($phpInfo, 'magic_quotes_runtime');
            $openBaseDir = $this->extractValue($phpInfo, 'open_basedir');
            $registerGlobals = $this->extractValue($phpInfo, 'register_globals');
            $safeMode = $this->extractValue($phpInfo, 'safe_mode');
            $iniPath = $this->extractValue($phpInfo, 'Loaded Configuration File');
            $fixPath = $this->extractValue($phpInfo, 'cgi.fix_pathinfo');

            $disabledFunction = ($disabledFunction == '' ? '-?-' : $disabledFunction);
            $openBaseDir = ($openBaseDir == '' ? '-?-' : $openBaseDir);

            $result = 'Version: ' . phpversion() . '<br/>';
            $result .= 'System Version: ' . $phpInfoSystem . '<br/>';
            $result .= 'PHP API: ' . $phpPHPAPI . '<br/>';
            $result .= 'allow_url_fopen: ' . $allowUrlFOpen . '<br/>';
            $result .= 'allow_url_include: ' . $allowUrlInclude . '<br/>';
            $result .= 'disable_functions: ' . str_replace('&nbsp;', ' ', $disabledFunction) . '<br/>';
            $result .= 'display_errors: ' . $displayErrors . '<br/>';
            $result .= 'error_reporting: ' . $errorReporting . '<br/>';
            $result .= 'expose_php: ' . $exposePHP . '<br/>';
            $result .= 'log_errors: ' . $logErrors . '<br/>';
            $result .= 'magic_quotes_gpc: ' . $MQGPC . '<br/>';
            $result .= 'magic_quotes_runtime: ' . $MQRT . '<br/>';
            $result .= 'register_globals: ' . $registerGlobals . '<br/>';
            $result .= 'open_basedir: ' . $openBaseDir . '<br/>';
            $result .= 'Ini Path: ' . $iniPath . '<br/>';
            $result .= 'CGI.FixPathInfo: ' . $fixPath . '<br/>';

            $result .= 'safe_mode: ' . $safeMode . '<br/>';
        } else {
            $result = 'phpinfo() is disabled' . '<br/>';
        }

        $cmdList = explode(',', 'popen,exec,ftp_exec,system,passthru,get_current_user,proc_open,shell_exec,ini_restore,getmygid,dl,symlink,chgrp,ini_set,putenv,extension_loaded,getmyuid,fsockopen,posix_setuid,posix_setsid,posix_setpgid,posix_kill,apache_child_terminate,chmod,chdir,pcntl_exec,phpinfo,virtual,proc_close,proc_get_status,proc_terminate,proc_nice,proc_getstatus,proc_close,escapeshellcmd,escapeshellarg,show_source,pclose,safe_dir,dl,ini_restore,chown,chgrp,shown_source,mysql_list_dbs,get_current_user,getmyid,leak,pfsockopen');

        $result .= "\nList of enabled functions: ";
        foreach ($cmdList as $F) {
            if (function_exists($F) && is_callable($F)) {
                $result .= $F . ' ';
            }
        }

        $result .= '<br/>List of disabled functions: ';
        foreach ($cmdList as $F) {
            if (!(function_exists($F) && is_callable($F))) {
                $result .= $F . ' ';
            }
        }

        return $result;
    }

    public function __toString()
    {
        $data = array($this->scriptFilename, $this->documentRoot, $this->httpHost, $this->adminEmail, $this->time);
        return implode(';', $data);
    }
}
