<?php

namespace Phi\FileSystem;

class Path
{

    private $path;
    private $normalizedPath;

    public function __construct($path)
    {
        if(!is_dir($path)) {
            throw new Exception('Path '.$path.' does no exist');
        }
        $this->path = $path;


        $this->normalizedPath = str_replace('\\', '/', $this->path);

        //$matches = preg_match_all('`/\.\.`', $this->normalizedPath);

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


    public function normalize()
    {
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





    public function doOnSubdirectories(callable $callback, $recursive = false, $path = null)
    {

        $currentDir = getcwd();

        if($path === null) {
            $path = $this->normalize();
        }
        $path = realpath($path);

        chdir($path);

        $dir = opendir($path);
        while($entry = readdir($dir)) {
            if($entry != '.' && $entry != '..' && is_dir($path.'/'.$entry)) {
                if(is_dir($path.'/'.$entry)) {
                    chdir($path.'/'.$entry);

                    $returnValue = call_user_func_array($callback, array($path.'/'.$entry));

                    if(!$returnValue) {
                        return;
                    }

                    if($recursive) {
                        $this->doOnSubdirectories($callback, $recursive, $path.'/'.$entry);
                    }
                }
            }
        }
        chdir($currentDir);
    }




    public function delete($src = null)
    {
        if($src === null) {
            $src = $this->path;
        }

        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    $this->delete($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }








    public function rglob($pattern, $flags = 0)
    {
        $temp =  glob($this->normalizedPath.'/'.$pattern, $flags);

        $files = [];
        foreach ($temp as $path) {
            $files[] = str_replace('\\', '/', $path);
        }


        //foreach (glob(dirname($pattern).'/*', GLOB_NOSORT ) as $dir) {
        foreach (glob(dirname($pattern).'/*', 0 ) as $dir) {
            $files = array_merge($files, static::rglob($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }


}


