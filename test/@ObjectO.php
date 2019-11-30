<?php


require_once $_SERVER['DOCUMENT_ROOT']  ."/vendor/autoload.php";

use function Autil\_, Autil\type, Autil\pretty;
use OOPe\Classes\ObjectO;



$obj2 = new ObjectO([
   "name" => "maru",
   "type" => [
      "gender" => "male",
      "blood" => "type-O",
      "invalid-prop" => "propVal",
   ],
]);


_( $obj2 );