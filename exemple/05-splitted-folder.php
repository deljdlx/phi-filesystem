<?php

require(__DIR__.'/_autoload.php');

use Phi\FileSystem\SplittedFolder;

$path = new SplittedFolder(__DIR__);
$path->filePutContents('test.php', 'hello world');

