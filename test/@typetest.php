<?php


require_once $_SERVER['DOCUMENT_ROOT']  ."/azumap.net/public_html/vendor/autoload.php";

use function Autil\_, Autil\match, Autil\type;


_( gettype(true) ); // boolean


$arr = [1,2,3];
$assoc = [
   "name" => "azu"
];
_( is_array($arr) ); // true
_( is_array($assoc) ); // true

_( type($arr) ); // [Array]
_( type($assoc) ); // [AssocArray]

_( 1 + "3" ); // 4

_( is_numeric(3) ); // true
_( is_numeric("3") ); // true