<?php


require_once $_SERVER['DOCUMENT_ROOT']  ."/vendor/autoload.php";

use function Autil\_, Autil\match, Autil\type, Autil\ObjectO;




_( [1,2,3, "4", "hi"] );
// (1, 2, 3, "4", "hi")


$assoc = [
   "name" =>  "azu",
   "id" => "001",
   "type" => [
      "gender" => "male",
   ],
];
_( $assoc );
/****  output  *****
["name"]: "azu"
["id"]: "001"
["type"]:
   ["gender"]: "male"
/****************/


$arr2d = [
   [1,2,"3","a"],
   [4,5,"6","b"],
];
_( $arr2d );
/****  output  *****
(
   (1, 2, "3", "a"), 
   (4, 5, "6", "b")
)
/****************/


class Foo{}
$obj = new Foo();
$arr = [$obj, $obj];
_( $arr ); // ({}, {})


$assoc = [
   "id" => $obj,
   "name" => $obj,
];
_( $assoc );
/****  output  *****
["id"]: {}
["name"]: {}
/****************/




$txt = <<<DOC
{
   
   "customers": [
      {
         "name": "Mike Davis",
         "age": 25
      },
      {
         "name": "Sala Jordan",
         "age": 17
      }
   ]
}
DOC;

$jsonObj = json_decode($txt); // [object stdClass]
/*---- json_decode  ----*
json_decode:: Str -> Obj
   Str: string of which format is json
   Obj: [object stdClass]
   
/**/

_( $jsonObj );
/****  output  *****
{
   "customers": (
      {
         "name": "Mike Davis"
         "age": 25
      }, 
      {
         "name": "Sala Jordan"
         "age": 17
      }
   )
}
/****************/


_( type($jsonObj) ); // [object stdClass]


_( get_object_vars($jsonObj) );
/****  output  *****
["customers"]:(
   {
      "name": "Mike Davis"
      "age": 25
   }, 
   {
      "name": "Sala Jordan"
      "age": 17
   }
)
/****************/



























