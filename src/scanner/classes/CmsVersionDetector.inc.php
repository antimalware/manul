<?php
/*
Example of usage:

<?php
include 'CmsVersionDetector.inc.php';

$detector = new CmsVersionDetector($argv[1]);
var_dump($detector->getCmsList());
var_dump($detector->getCmsVersions());
?>

*/

define('CMS_BITRIX', 'Bitrix');
define('CMS_WORDPRESS', 'Wordpress');
define('CMS_JOOMLA', 'Joomla');
define('CMS_DLE', 'Data Life Engine');
define('CMS_IPB', 'Invision Power Board');
define('CMS_WEBASYST', 'WebAsyst');
define('CMS_OSCOMMERCE', 'OsCommerce');
define('CMS_DRUPAL', 'Drupal');
define('CMS_MODX', 'MODX');
define('CMS_INSTANTCMS', 'Instant CMS');
define('CMS_PHPBB', 'PhpBB');
define('CMS_VBULLETIN', 'vBulletin');
define('CMS_SHOPSCRIPT', 'PHP ShopScript Premium');

define('CMS_VERSION_UNDEFINED', '0.0');

class CmsVersionDetector
{
    private $rootPath;
    private $versions;
    private $types;

    public function __construct($rootPath = '.')
    {
        $this->rootPath = $rootPath;
        $this->versions = array();
        $this->types = array();

        $version = '';

        if ($this->checkBitrix($version)) {
            $this->addCms(CMS_BITRIX, $version);
        }

        if ($this->checkWordpress($version)) {
            $this->addCms(CMS_WORDPRESS, $version);
        }

        if ($this->checkJoomla($version)) {
            $this->addCms(CMS_JOOMLA, $version);
        }

        if ($this->checkDle($version)) {
            $this->addCms(CMS_DLE, $version);
        }

        if ($this->checkIpb($version)) {
            $this->addCms(CMS_IPB, $version);
        }

        if ($this->checkWebAsyst($version)) {
            $this->addCms(CMS_WEBASYST, $version);
        }

        if ($this->checkOsCommerce($version)) {
            $this->addCms(CMS_OSCOMMERCE, $version);
        }

        if ($this->checkDrupal($version)) {
            $this->addCms(CMS_DRUPAL, $version);
        }

        if ($this->checkMODX($version)) {
            $this->addCms(CMS_MODX, $version);
        }

        if ($this->checkInstantCms($version)) {
            $this->addCms(CMS_INSTANTCMS, $version);
        }

        if ($this->checkPhpBb($version)) {
            $this->addCms(CMS_PHPBB, $version);
        }

        if ($this->checkVBulletin($version)) {
            $this->addCms(CMS_VBULLETIN, $version);
        }

        if ($this->checkPhpShopScript($version)) {
            $this->addCms(CMS_SHOPSCRIPT, $version);
        }

    }

    function getCmsList()
    {
        return $this->types;
    }

    function getCmsVersions()
    {
        return $this->versions;
    }

    function getCmsNumber()
    {
        return count($this->types);
    }

    function getCmsName($index = 0)
    {
        return $this->types[$index];
    }

    function getCmsVersion($index = 0)
    {
        return $this->versions[$index];
    }

    private function addCms($type, $version)
    {
        $this->types[] = $type;
        $this->versions[] = $version;
    }

    private function checkBitrix(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/bitrix')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/bitrix/modules/main/classes/general/version.php'));
            if (preg_match('|define\("SM_VERSION","(.+?)"\)|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }

        }

        return $res;
    }

    private function checkWordpress(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;


        if (file_exists($this->rootPath . '/wp-admin')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/wp-includes/version.php'));
            if (preg_match('|\$wp_version\s*=\s*\'(.+?)\'|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }
        }
        return $res;
    }

    private function checkJoomla(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/libraries/joomla')) {
            $res = true;

            // for 1.5.x
            $tmpContent = @implode('', @file($this->rootPath . '/libraries/joomla/version.php'));
            if (preg_match('|var\s+\$RELEASE\s*=\s*\'(.+?)\'|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];

                if (preg_match('|var\s+\$DEV_LEVEL\s*=\s*\'(.+?)\'|smi', $tmpContent, $tmpVer)) {
                    $version .= '.' . $tmpVer[1];
                }
            }

            // for 1.7.x
            $tmpContent = @implode('', @file($this->rootPath . '/includes/version.php'));
            if (preg_match('|public\s+\$RELEASE\s*=\s*\'(.+?)\'|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];

                if (preg_match('|public\s+\$DEV_LEVEL\s*=\s*\'(.+?)\'|smi', $tmpContent, $tmpVer)) {
                    $version .= '.' . $tmpVer[1];
                }
            }

            // for 2.5.x and 3.x
            $tmpContent = @implode('', @file($this->rootPath . '/libraries/cms/version/version.php'));
            if (preg_match('|public\s+\$RELEASE\s*=\s*\'(.+?)\'|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];

                if (preg_match('|public\s+\$DEV_LEVEL\s*=\s*\'(.+?)\'|smi', $tmpContent, $tmpVer)) {
                    $version .= '.' . $tmpVer[1];
                }
            }
        }

        return $res;
    }

    private function checkDle(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/engine/engine.php')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/engine/data/config.php'));
            if (preg_match('|\'version_id\'\s*=>\s*"(.+?)"|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }

            $tmpContent = @implode('', @file($this->rootPath . '/install.php'));
            if (preg_match('|\'version_id\'\s*=>\s*"(.+?)"|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }
        }

        return $res;
    }

    private function checkIpb(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/ips_kernel')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/ips_kernel/class_xml.php'));
            if (preg_match('|IP.Board\s+v([0-9\.]+)|si', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }
        }

        return $res;
    }

    private function checkWebAsyst(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/wbs/installer')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/license.txt'));
            if (preg_match('|v([0-9\.]+)|si', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }
        }

        return $res;
    }

    private function checkOsCommerce(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/includes/version.php')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/includes/version.php'));
            if (preg_match('|([0-9\.]+)|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }
        }

        return $res;
    }

    private function checkDrupal(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/sites/all')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/CHANGELOG.txt'));
            if (preg_match('|Drupal\s+([0-9\.]+)|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }
        }

        return $res;
    }

    private function checkMODX(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/manager/assets')) {
            $res = true;
            // no way to pick up version
        }

        return $res;
    }

    private function checkInstantCms(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/plugins/p_usertab')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/index.php'));
            if (preg_match('|InstantCMS\s+v([0-9\.]+)|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }
        }

        return $res;
    }

    private function checkPhpBb(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/includes/acp')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/config.php'));
            if (preg_match('|phpBB\s+([0-9\.x]+)|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }
        }

        return $res;
    }

    private function checkVBulletin(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/core/admincp')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/core/api.php'));
            if (preg_match('|vBulletin\s+([0-9\.x]+)|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }
        }

        return $res;
    }

    private function checkPhpShopScript(&$version)
    {
        $version = CMS_VERSION_UNDEFINED;
        $res = false;

        if (file_exists($this->rootPath . '/install/consts.php')) {
            $res = true;

            $tmpContent = @implode('', @file($this->rootPath . '/install/consts.php'));
            if (preg_match('|STRING_VERSION\',\s*\'(.+?)\'|smi', $tmpContent, $tmpVer)) {
                $version = $tmpVer[1];
            }
        }

        return $res;
    }

    public function getXMLNode()
    {
        $cmsNames = $this->getCmsList();
        $cmsVersions = $this->getCmsVersions();

        $dom = new DOMDocument('1.0', 'utf-8');
        $cmsListNode = $dom->createElement('cms_list');

        for ($i = 0; $i < count($cmsNames); ++$i) {
            $cmsNode = $dom->createElement('cms');
            $cmsNode->setAttribute('name', $cmsNames[$i]);
            $cmsNode->setAttribute('version', $cmsVersions[$i]);
            $cmsListNode->appendChild($cmsNode);
        }

        $dom->appendChild($cmsListNode);
        return $dom->getElementsByTagName('cms_list')->item(0);
    }
}

