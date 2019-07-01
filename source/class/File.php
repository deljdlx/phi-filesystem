<?php

namespace Phi\FileSystem;


class File extends Resource
{

    private $directory;


    public function __construct($path)
    {
        parent::__construct($path);
        if(!is_file($path)) {
            throw new Exception('Path '.$path.' does no exist');
        }

    }


    public function getExtension()
    {
        return pathinfo($this->getPath(), PATHINFO_EXTENSION);
    }

    public function getDirectory()
    {
        if(!$this->directory) {
            $this->directory = new Path(dirname($this->getPath()));
        }

        return $this->directory;
    }



}


