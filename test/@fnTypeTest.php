<?php


require_once $_SERVER['DOCUMENT_ROOT']  ."/vendor/autoload.php";

use function Autil\_, Autil\match, Autil\type, Autil\ObjectO;




class Foo{
   function method(){
      _("foo method called!");
   }
}

function isEven($x){
   return x % 2 === 0;
}

_( is_callable('isEven') ); // true
$foo = new Foo();

$foo->method(); // "foo method called!
_( type($foo->method) ); //[Null]

# is_callable(object method)
## x ## 
_( is_callable($foo->method) ); // false
## O ##
_( is_callable( [$foo, 'method'] ) ); // true

_( ObjectO() ); // [object ObjectO]
$obj = ObjectO();
$obj->name = "azu";
_( $obj->name ); // "azu"