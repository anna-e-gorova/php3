<?php
/*3. Определить сложность следующих алгоритмов. Сколько произойдет итераций?

1) O(n)*O(log(n)+1) => O(nlog(n))
*/

$n = 100;
$array[]= [];

for ($i = 0; $i < $n; $i++) {
  for ($j = 1; $j < $n; $j *= 2) {
     $array[$i][$j]= true;
  } 
}

/*2) O((n^2)/2^2)
*/
$n = 100;
$array[] = [];

for ($i = 0; $i < $n; $i += 2) {
  for ($j = $i; $j < $n; $j++) {
     $array[$i][$j]= true;
} }

