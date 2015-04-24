<?php

class Template
{
    private $_name, $_path;

    public function __construct($name = '')
    {
        global $projectRootDir;
        $this->_path = $projectRootDir . '/static/templates/';
        $this->_name = $name;
        if (!file_exists($this->_path . $this->_name)) die(sprintf(PS_ERR_TEMPLATE_DOESNT_EXISTS, $this->_template));

        $this->content = implode('', file($this->_path . $this->_name));
        $this->orig = $this->content;

        if (preg_match_all('/\{(PS_.+?)\}/smiu', $this->content, $entries, PREG_SET_ORDER)) {
            for ($i = 0; $i < sizeof($entries); $i++) {
                $this->content = str_replace('{' . $entries[$i][1] . '}', constant($entries[$i][1]), $this->content);
            }
        }
    }

    public function get()
    {
        return $this->content;
    }

    public function prepare()
    {
        $this->content = $this->orig;
    }

    public function set($meta, $value)
    {
        $this->content = preg_replace('|@@' . $meta . '@@|smiu', $value, $this->content);
    }

}