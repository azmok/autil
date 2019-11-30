<?php


require_once $_SERVER['DOCUMENT_ROOT']  ."/azumap.net/public_html/vendor/autoload.php";

use function Autil\_, Autil\__, 
Autil\match, Autil\type;



__( [1,2,3] );
// (1, 2, 3)


$assoc = [
   "name" =>  "azu",
   "id" => "001",
   "type" => [
      "gender" => "male",
   ],
   1
];
__( $assoc );
/****  output  *****
["name"]: "azu"
["id"]: "001"
["type"]:
   ["gender"]: "male"
[0]: 1
/****************/


$arr2d = [
   [1,2,"3","a"],
   [4,5,"6","b"],
];
__( $arr2d );
/****  output  *****
(
   (1, 2, "3", "a"), 
   (4, 5, "6", "b")
)
/****************/


class Foo{}
$obj = new Foo();
$arr = [$obj, $obj];
__( $arr ); // ({}, {})


$assoc = [
   "id" => $obj,
   "name" => $obj,
];
__( $assoc );
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

__( $jsonObj );
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


__( type($jsonObj) ); // [object stdClass]


__( get_object_vars($jsonObj) );
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



























