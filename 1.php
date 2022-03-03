<?php
$startDir = "D:\openserver\OpenServer\domains";
explorer($startDir);

function explorer($startDir, $separator = "") {
    $dir = new DirectoryIterator($startDir);
    foreach ($dir as $item) {
        echo $separator . $item . "<br>";
        if (!$item->isDot() && $item->isDir()) {
            explorer($startDir . DIRECTORY_SEPARATOR  . $item, $separator . "----------");
        }
    }
}