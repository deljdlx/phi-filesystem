<?php

namespace Phi\FileSystem;

class Resource
{

    private $path;
    private $normalizedPath;

    public function __construct($path)
    {
        $this->path = $path;
    }




    public function normalize()
    {
        if(!$this->normalizedPath) {
            $this->normalizedPath = str_replace('\\', '/', $this->path);

            $parts = explode('/', $this->normalizedPath);

            $normalizedParts = [];
            foreach ($parts as $part) {
                if($part === '.' || $part === '') {
                    continue;
                }

                if($part === '..') {
                    if(!empty($normalizedParts)) {
                        array_pop($normalizedParts);
                    }
                    else {
                        throw new Exception('Can not normalized path.');
                    }
                }
                else {
                    $normalizedParts[] = $part;
                }
            }


            $this->normalizedPath = implode('/', $normalizedParts);

        }
        return $this->normalizedPath;
    }

    public function __toString()
    {
        return $this->getPath();
    }

    public function getPath()
    {
        return $this->path;
    }


}