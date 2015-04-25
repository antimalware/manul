<?php

class View
{
    private $_path;
    private $_template;
    private $_var = array();

    public function __construct($path = '')
    {
        global $projectRootDir;
        $this->_path = $projectRootDir . '/static/templates/' . $path;
    }

    public function setTemplateDirAbsolutePath($path)
    {
        $this->_path = $path;
    }

    public function set($name, $value)
    {
        $this->_var[$name] = $value;
    }

    public function __get($name)
    {
        if (isset($this->_var[$name])) return $this->_var[$name];
        return '';
    }

    public function display($template, $strip = true)
    {
        $this->_template = $this->_path . $template;
        if (!file_exists($this->_template)) die(sprintf(PS_ERR_TEMPLATE_DOESNT_EXISTS, $this->_template));

        ob_start();
        header('Content-Type: text/html; charset=utf-8');

        include($this->_template);

        // general templates
        define('PS_FOOTER', $this->meta_replacer(implode('', file($this->_path . 'footer.tpl'))));

        echo ($strip) ? $this->_strip($this->meta_replacer(ob_get_clean())) : $this->meta_replacer(ob_get_clean());
    }

    private function meta_replacer($content)
    {
        if (preg_match_all("/\{(PS_.+?)\}/smiu", $content, $entries, PREG_SET_ORDER)) {
            for ($i = 0; $i < sizeof($entries); $i++) {
                $constantName = $entries[$i][1];
                if (defined($constantName)) {
                    $content = str_replace('{' . $constantName . '}', constant($constantName), $content);
                } else {
                    $content = str_replace('{' . $constantName . '}', '', $content);
                }
            }
        }

        return $content;
    }

    private function _strip($data)
    {
        $lit = array("\\t", "\\n", "\\n\\r", "\\r\\n", '  ');
        $sp = array('', '', '', '', '');
        return str_replace($lit, $sp, $data);
    }

}
