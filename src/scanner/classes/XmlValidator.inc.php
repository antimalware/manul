<?php

class XmlValidator
{
    private function libxmlDisplayError($error)
    {
        $return = "<br/>\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= sprintf(PS_ERR_XML_VALIDATION_WARNING, $error->code);
                break;
            case LIBXML_ERR_ERROR:
                $return .= sprintf(PS_ERR_XML_VALIDATION_ERROR, $error->code);
                break;
            case LIBXML_ERR_FATAL:
                $return .= sprintf(PS_ERR_XML_VALIDATION_FATAL_ERROR, $error->code);
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .= sprintf(PS_ERR_XML_FILE_SPEC, $error->file);
        }
        $return .= sprintf(PS_ERR_XML_LINE_SPEC, $error->line);
        return $return;
    }

    private function libxmlDisplayErrors()
    {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            print $this->libxmlDisplayError($error);
        }
        libxml_clear_errors();
    }

    public function validate($xmlStr, $schemaPath)
    {
        libxml_use_internal_errors(true);
        $xml = new DOMDocument();

        $xml->loadXML($xmlStr);

        if (!$xml->schemaValidate($schemaPath)) {
            print PS_ERR_XML_VALIDATION_FAILED;
            $this->libxmlDisplayErrors();
            return false;
        }
        return true;
    }
}

