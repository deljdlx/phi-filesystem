<?php

require(__DIR__.'/_autoload.php');

use Phi\FileSystem\SplittedFolder;

$path = new SplittedFolder(__DIR__);



$path->filePutContents('test.txt', 'hello world');

echo $path->fileGetContents('test.txt');

echo "\n";

foreach ($path->getFile() as $file) {
    echo $file;
    echo "\n";
}



//

