<?php

namespace Phi\FileSystem;

class SplittedFolder
{

    private $rootPath;

    public function __construct($rootPath)
    {
        $this->rootPath = $rootPath;
    }


    public function filePutContents($file, $content, $option)
    {
        $tree = $this->generateTree($file);
    }


    public function generateTree($file)
    {

    }


}
