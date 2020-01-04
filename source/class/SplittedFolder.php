<?php

namespace Phi\FileSystem;

class SplittedFolder
{

    private $sourcePath;
    private $depth;
    private $threshold;

    public function __construct($sourcePath, $depth = 4, $threshold = 2)
    {
        $this->sourcePath = $sourcePath;
        $this->depth = $depth;
        $this->threshold = $threshold;
    }


    public function createFolders($file)
    {
        $file = $this->normalizeFileName($file);
        $tree = $this->generateTree($file);

        $path = $this->sourcePath;
        foreach ($tree as $part) {
            $path .= '/' . $part;
            if(!is_dir($path)) {
                mkdir($path);
            }
        }

        return $path;

    }

    public function filePutContents($file, $content, $option = null)
    {
        $file = $this->normalizeFileName($file);
        $path = $this->createFolders($file);
        file_put_contents($path . '/' . $file, $content, $option);
        return $this;
    }


    public function getFilePath($file)
    {
        $file = $this->normalizeFileName($file);
        return $this->createFolders($file).'/'.$file;
    }


    public function fileGetContents($file)
    {
        $file = $this->normalizeFileName($file);
        $tree = $this->generateTree($file);
        $path = $this->sourcePath.'/'.implode('/', $tree);

        return file_get_contents($path.'/'.$file);
    }


    public function getFile()
    {
        $directory = new \RecursiveDirectoryIterator($this->sourcePath);
        $iterator = new \RecursiveIteratorIterator($directory);

        foreach ($iterator as $info) {
            $file = $info->getPathName();
            $fileName = basename($file);
            if($fileName != '.' && $fileName !='..' && is_file($file)) {
                yield $file;
            }
        }
    }


    public function generateTree($file)
    {

        $hash = md5($file) . sha1($file);

        $chunk = chunk_split($hash, $this->threshold, "\n");
        $parts = explode("\n", $chunk);

        $tree = [];
        for ($i = 0; $i < $this->depth; $i++) {
            $tree[] = $parts[$i];
        }

        return $tree;
    }

    protected function normalizeFileName($fileName)
    {
        $fileName = trim($fileName);
        $fileName = preg_replace('`^/+`', '', $fileName);
        $fileName = preg_replace('`/+$`', '', $fileName);
        return $fileName;
    }


}
