<?php


require_once $_SERVER['DOCUMENT_ROOT']  ."/azumap.net/public_html/vendor/autoload.php";

use function Autil\_, Autil\match, Autil\type;



_( [1,2,3] );
// (1, 2, 3)


$assoc = [
   "name" =>  "azu",
   "id" => "001",
   "type" => [
      "gender" => "male",
   ],
];
_( $assoc );
/****  output  *****
[name]: "azu"
[id]: "001"
[type]:
   [gender]: "male"
/****************/


$arr2d = [
   [1,2,3],
   [4,5,6],
];
_( $arr2d );
/****  output  *****
(
    (1, 2, 3), 
    (4, 5, 6)
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
[id]: {}
[name]: {}
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
   customers: (
      {
         name: Mike Davis
         age: 25
      }, 
      {
         name: Sala Jordan
         age: 17
      }
   )
}
/****************/


_( type($jsonObj) ); // [object stdClass]


_( get_object_vars($jsonObj) );
/****  output  *****
[customers]: (
      {
         name: Mike Davis
         age: 25
      }, 
      {
         name: Sala Jordan
         age: 17
      }
   )
/****************/



























