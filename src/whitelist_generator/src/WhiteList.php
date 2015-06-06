<?php

namespace Manul\WhiteList;

use ErrorException;
use InvalidArgumentException;
use Symfony\Component\Finder\Finder as Finder;
use Symfony\Component\Finder\SplFileInfo as SplFileInfo;

/**
 * Class WhiteList
 */
class WhiteList
{
    /**
     * @var string
     */
    private $cmsName;

    /**
     * @var string
     */
    private $cmsVersion;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $formatVersion = '0.1';

    /**
     * @var array
     */
    private $files = [];

    /**
     * @var string
     */
    private $fileExtension = 'json';

    /**
     * @var bool
     */
    private $minify = true;

    /**
     * @return WhiteList
     */
    public static function create()
    {
        return new WhiteList();
    }

    /**
     * @return string
     */
    public function getMinify()
    {
        return $this->minify;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMinify($value)
    {
        $this->minify = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCmsName()
    {
        return $this->cmsName;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCmsName($value)
    {
        $this->cmsName = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCmsVersion()
    {
        return $this->cmsVersion;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCmsVersion($value)
    {
        $this->cmsVersion = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setPath($value)
    {
        $this->path = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormatVersion()
    {
        return $this->formatVersion;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setFormatVersion($value)
    {
        $this->formatVersion = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return [
            'name'                => $this->getCmsName(),
            'version'             => $this->getCmsVersion(),
            'file_format_version' => $this->getFormatVersion(),
        ];
    }

    /**
     * @return array
     * @throws ErrorException
     */
    public function getFiles()
    {
        $path = $this->getPath();

        try {
            $finder = new Finder();
            $finder->files()->in($path);
        } catch (InvalidArgumentException $e) {
            throw new ErrorException($e->getMessage());
        }

        foreach ($finder as $file) {
            $this->files[] = $this->getFileMeta($file);
        }

        return $this->files;
    }

    /**
     * @param SplFileInfo $file
     * @return array
     */
    protected function getFileMeta(SplFileInfo $file)
    {
        $path         = $file->getPath();
        $pathname     = $file->getPathname();
        $relativePath = pathinfo($path, PATHINFO_BASENAME);

        return [
            'path' => '.' . DIRECTORY_SEPARATOR . $relativePath . DIRECTORY_SEPARATOR . $file->getRelativePathname(),
            'md5'  => $this->getFileHash($pathname),
            'size' => $file->getSize(),
        ];
    }

    /**
     * @param string $pathname
     * @return string
     */
    protected function getFileHash($pathname)
    {
        return md5_file($pathname);
    }

    /**
     * @return string
     */
    protected function getJsonFileName()
    {
        $parts = [
            $this->getCmsVersion(),
            $this->getMinify() ? 'min' : '',
            $this->fileExtension
        ];

        $parts = array_filter($parts);
        $path  = __DIR__ . DIRECTORY_SEPARATOR . implode('.', $parts);

        return $path;
    }

    /**
     * @return int
     */
    protected function getJsonOptions()
    {
       return $this->getMinify() ? JSON_UNESCAPED_SLASHES : (JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param array $data
     * @return string
     * @throws ErrorException
     */
    protected function encode(array $data)
    {
        $options = $this->getJsonOptions();
        $data = json_encode($data, $options);

        if ($data === false) {
            throw new ErrorException('Failed to encode data.');
        }

        return $data;
    }

    /**
     * @throws ErrorException
     */
    public function build()
    {
        $fileName = $this->getJsonFileName();

        $data = [
            'whitelist' => [
                'meta'  => $this->getMeta(),
                'files' => $this->getFiles()
            ]
        ];

        $json = $this->encode($data);
        $write = @file_put_contents($fileName, $json);

        if ($write === false) {
            throw new ErrorException('Unable to save data to a file.');
        }
    }
}