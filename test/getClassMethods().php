<?php

require_once $_SERVER['DOCUMENT_ROOT']  ."/vendor/autoload.php";

use function Autil\_, Autil\type, Autil\pretty, Autil\isType, Autil\isMethod,  Autil\ObjectO;

//use OOPe\Classes\ObjectO;



function getClassMethods($objOrClass){
   
   # for method prinitng
   if( is_object($objOrClass) ){
      $obj = $objOrClass;
      
      // [ [$obj, 'methodName'], [], ... ]
      //
      // pretty( getClassMethods() );
      //->  pretty( [ [$obj, 'methodName'], [], ... ]   )
      $methodNames = get_class_methods($obj);
      $arr2d = [];
      
      foreach($methodNames as $methodName ){
         $arr2d[] = [$obj, $methodName];
      }
      
      return $arr2d;
      
   } else {
      $className = $objOrClass;
      
      return get_class_methods($className);
   }
}

//$arrObj = new ObjectO(); -- (1)
$arrObj = ObjectO(); // -- (2) classHelper function. no need 'use namespace\className'
$arr2d = getClassMethods($arrObj);

_( $arr2d );
pretty($arr2d);



//_( isMethod($arr2d[0]) ); // true






function getClassNamebyMethod($methodName){
   $availClasses = get_declared_classes();
   
   foreach( $availClasses as $className){
      $res = method_exists($className, $methodName);
      if( $res ){
         return ObjectO([
            "className" => $className,
            "methodName" => $methodName,
        ]);
      }
   }
}

_( getClassNamebyMethod('getProps') );

























