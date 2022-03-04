<?php
function LinearSearch ($myArray, $num, &$countSteps) {
    $count = count($myArray);
    
    for ($i=0; $i < $count; $i++) {
        $countSteps++;
     if ($myArray[$i] == $num){
         return $i;
     } 
     elseif ($myArray[$i] > $num){
         return null;
     } 
    }
    return null;
}

function binarySearch($myArray, $num, &$countSteps) {

    //определяем границы массива
    $left = 0;
    $right = count($myArray) - 1;
        
    while ($left <= $right) {
        $countSteps++;
        
    //находим центральный элемент с округлением индекса в меньшую сторону
    $middle = floor(($right + $left)/2);
    //если центральный элемент и есть искомый   
    if ($myArray[$middle] == $num) {
        return $middle;
    }
        
    elseif ($myArray[$middle] > $num) {
    //сдвигаем границы массива до диапазона от left до middle-1
    $right = $middle - 1;
    }
    elseif ($myArray[$middle] < $num) {
          $left = $middle + 1;
    }
    }
    return null;
}

function InterpolationSearch($myArray, $num, &$countSteps)
{
$start = 0;
$last = count($myArray) - 1;

while (($start <= $last) && ($num >= $myArray[$start]) 
&& ($num <= $myArray[$last])) {
    $countSteps++;

 $pos = floor($start + (
   (($last - $start) / ($myArray[$last] - $myArray[$start]))
   * ($num - $myArray[$start])
  ));
 if ($myArray[$pos] == $num) {
  return $pos;
 }

 if ($myArray[$pos] < $num) {
  $start = $pos + 1;
 }

 else {
  $last = $pos - 1;
 }
}
return null;
}

function bubbleSort(&$array) {
    for($i=0; $i<count($array); $i++) {
        $count = count($array);
        for($j=$i+1; $j<$count; $j++) {
           if($array[$i]>$array[$j]) {
               $temp = $array[$j];
               $array[$j] = $array[$i];
               $array[$i] = $temp;
           }
        }         
   }
   return $array;
}

//////// Тестирование
$arr = array();
for ($i = 0; $i < 10000; $i++) {
    $arr[]=rand(1,100);
}
bubbleSort($arr);
$rand_keys = array_rand($arr, 1);
$num = $arr[$rand_keys];

$countSteps=0;
LinearSearch($arr, $num, $countSteps);
echo "LinearSearch steps count:" . $countSteps . "<br>";

$countSteps=0;
binarySearch($arr, $num, $countSteps);
echo "LinearSearch steps count:" . $countSteps . "<br>";

$countSteps=0;
InterpolationSearch($arr, $num, $countSteps);
echo "LinearSearch steps count:" . $countSteps . "<br>";
