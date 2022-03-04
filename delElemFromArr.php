<?php
function printArray(&$arr)
{
$countArr = count($arr);
for ($i = 0; $i < $countArr; ++$i)
 echo ($arr[$i]." ") ;

}

$array = [1, 5, 6, 2, 4, 5, 6, 7, 2, 5, 6, 3];
  
while(($key = array_search(5,$array)) !== FALSE) {
    unset($array[$key]);
}

printArray($array);


    