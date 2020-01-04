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


    public function filePutContents($file, $content, $option)
    {
        $tree = $this->generateTree($file);


        print_r($tree);
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
