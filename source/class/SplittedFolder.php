<?php

namespace Phi\FileSystem;

class SplittedFolder
{

    private $rootPath;
    private $depth;

    public function __construct($rootPath, $depth = 4)
    {
        $this->rootPath = $rootPath;
        $this->depth =$depth;
    }


    public function filePutContents($file, $content, $option = null)
    {
        $tree = $this->generateTree($file);

        $path = $this->rootPath;
        foreach ($tree as $part) {
            $path .= '/'.$part;
            mkdir($path);
        }


    }


    public function generateTree($file)
    {
        $hash = md5($file);

        $tree =[];
        for($i = 0 ; $i<$this->depth; $i++) {
            $tree[] = $hash{$i};
        }

        return $tree;
    }


}
