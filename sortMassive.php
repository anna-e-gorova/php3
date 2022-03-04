<?php
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


function shakerSort (&$array) {
    $n = count($array);
    $left = 0;
    $right = $n - 1;
    do {
        for ($i = $left; $i < $right; $i++) {
            if ($array[$i] > $array[$i + 1]) {
                list($array[$i], $array[$i + 1]) = array($array[$i + 1], $array[$i]);
            }
        }
    $right -= 1;
    for ($i = $right; $i > $left; $i--) {
        if ($array[$i] < $array[$i - 1]) {
            list($array[$i], $array[$i - 1]) = array($array[$i - 1], $array[$i]);
        }
    }
    $left += 1;
    } while ($left <= $right);
}


function quickSort(&$arr, $low, $high) {
    $i = $low;                
    $j = $high;
    $middle = $arr[ ( $low + $high ) / 2 ];   // middle – опорный элемент; в нашей реализации он находится посередине между low и high
    do {
        while($arr[$i] < $middle) ++$i;  // Ищем элементы для правой части
         while($arr[$j] > $middle) --$j;   // Ищем элементы для левой части
            if($i <= $j){           
// Перебрасываем элементы
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
// Следующая итерация
            $i++; $j--;
        }
    }
    while($i < $j);
    
    if($low < $j){
// Рекурсивно вызываем сортировку для левой части
      quickSort($arr, $low, $j);
    }

    if($i < $high){
// Рекурсивно вызываем сортировку для правой части
      quickSort($arr, $i, $high);
    }
}


// Процедура для преобразования в двоичную кучу поддерева с корневым узлом $i, что является индексом в $arr[]. $countArr — размер кучи

function heapify(&$arr, $countArr, $i) {
$largest = $i; // Инициализируем наибольший элемент как корень
$left = 2*$i + 1; // левый = 2*i + 1
$right = 2*$i + 2; // правый = 2*i + 2

// Если левый дочерний элемент больше корня
if ($left < $countArr && $arr[$left] > $arr[$largest])
 $largest = $left;

//Если правый дочерний элемент больше, чем самый большой элемент на данный момент
if ($right < $countArr && $arr[$right] > $arr[$largest])
 $largest = $right;

// Если самый большой элемент не корень
if ($largest != $i) {
 $swap = $arr[$i];
 $arr[$i] = $arr[$largest];
 $arr[$largest] = $swap;

 // Рекурсивно преобразуем в двоичную кучу затронутое поддерево
 heapify($arr, $countArr, $largest);
}
}

//Основная функция, выполняющая пирамидальную сортировку
function heapSort(&$arr)
{
$countArr = count($arr);
// Построение кучи (перегруппируем массив)
for ($i = $countArr / 2 - 1; $i >= 0; $i--)
 heapify($arr, $countArr, $i);

 //Один за другим извлекаем элементы из кучи
for ($i = $countArr-1; $i >= 0; $i--)
{
 // Перемещаем текущий корень в конец
 $temp = $arr[0];
 $arr[0] = $arr[$i];
 $arr[$i] = $temp;

 // вызываем процедуру heapify на уменьшенной куче
 heapify($arr, $i, 0);
}
}

/* Вспомогательная функция для вывода на экран массива размера n */
function printArray(&$arr)
{
$countArr = count($arr);
for ($i = 0; $i < $countArr; ++$i)
 echo ($arr[$i]." ") ;

}


//////// Тестирование
$arr = array();
for ($i = 0; $i < 10000; $i++) {
    $arr[]=rand(1,1000);
}
//echo 'Не отсортированный массив: ' . PHP_EOL;
//printArray($arr);
$arr1=$arr2=$arr3=$arr4=$arr;
//echo "<br>";

//echo 'Отсортированный массив bubbleSort: ' . PHP_EOL;
$start_time=microtime(true);
bubbleSort($arr1);
$end_time=microtime(true);
//printArray($arr1);
echo 'Время bubbleSort: ' . PHP_EOL;
echo $end_time-$start_time;
echo "<br>";


//echo 'Отсортированный массив shakerSort: ' . PHP_EOL;
$start_time=microtime(true);
shakerSort($arr2);
$end_time=microtime(true);
//printArray($arr2);
echo 'Время shakerSort: ' . PHP_EOL;
echo $end_time-$start_time;
echo "<br>";


//echo 'Отсортированный массив quickSort: ' . PHP_EOL;
$start_time=microtime(true);
quickSort($arr3, min($arr3), max($arr3)); //Пусть считает вместе с нахождением минимума и максимума
$end_time=microtime(true);
//printArray($arr3);
echo 'Время quickSort: ' . PHP_EOL;
echo $end_time-$start_time;
echo "<br>";

//echo 'Отсортированный массив heapSort: ' . PHP_EOL;
$start_time=microtime(true);
heapSort($arr4);
$end_time=microtime(true);
//printArray($arr4);
echo 'Время heapSort: ' . PHP_EOL;
echo $end_time-$start_time;