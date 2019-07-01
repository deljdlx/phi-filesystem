<?php

namespace Phi\FileSystem;

class Path extends Resource
{




    public function __construct($path)
    {
        parent::__construct($path);
        if(!is_dir($path)) {
            throw new Exception('Path '.$path.' does no exist');
        }
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
            $src = $this->getPath();
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
        $temp =  glob($this->normalize().'/'.$pattern, $flags);

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



    public function rcopy($source = null, $dest, $createDir = false, $customValidator = null, $copySymlink = true, $doOnSymLink = null)
    {

        if($source === null) {
            $source = $this->normalize();
        }

        // recursive function to copy
        // all subdirectories and contents:
        if(is_dir($source)) {

            if(is_link($source)) {
                if(is_callable($doOnSymLink)) {
                    call_user_func_array(
                        $doOnSymLink,
                        array($source)
                    );
                }
                if(!$copySymlink) {
                    return;
                }
            }

            $dir_handle=opendir($source);
            $sourcefolder = basename($source);

            if($createDir) {
                mkdir($dest."/".$sourcefolder);
                $destinationPath = $dest."/".$sourcefolder;
            }
            else {
                $destinationPath = $dest;
            }

            while($file=readdir($dir_handle)){
                if($file!="." && $file!="..") {

                    if(is_callable($customValidator)) {
                        $validate = call_user_func_array($customValidator, array($source."/".$file));
                        if(!$validate) {
                            continue;
                        }
                    }



                    if(is_link($source."/".$file)) {
                        if(is_callable($doOnSymLink) || is_array($doOnSymLink)) {
                            call_user_func_array(
                                $doOnSymLink,
                                array($source."/".$file)
                            );
                        }
                        if(!$copySymlink) {
                            continue;
                        }
                    }


                    if(is_dir($source."/".$file)){
                        static::rcopy($source."/".$file, $destinationPath, true, $customValidator, $copySymlink, $doOnSymLink);
                    } else {

                        echo $source."/".$file."\t => \t".$destinationPath."/".$file;
                        echo "\n";

                        copy($source."/".$file, $destinationPath."/".$file);
                    }
                }
            }
            closedir($dir_handle);
        } else {
            // can also handle simple copy commands
            copy($source, $dest);
        }
    }




}


