<?php

// $key = '18|';// 没有选项
// var_dump(explode('|', $key));
// $key = '18|10-1,11-12';// 有选项
// var_dump(explode('|', $key));

$item = '10-1';
list($k, $v) = explode('-', $item);//[10, 1]
var_dump($k, $v);