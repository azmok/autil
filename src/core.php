<?php

namespace Autil;

use \OOPe\Classes\RegExpO;
use \OOPe\Classes\AssocArrayO;




#---------------------------------------------------------#
#------------------   self-independent   -----------------#
#---------------------------------------------------------#
const TABCHAR = "&ensp;&ensp;&ensp;";



function exho(...$args){
   foreach( $args as $arg ){
      echo "$arg ";
   }
   echo "<br>";
}


function splitNamespaces($str){

   $regex = '/\\\?[\w\d_]+\\\?/'; # two|tree-backslashes is used as escape-sequence in regex
   preg_match_all($regex, $str, $matches);
   
   return $matches[0];
}

function toArray($str){
   return str_split($str);
}


function br($num=2){
   for($i = 0; $i < $num; $i++){
      echo "<br>";
   }
}


function isAssoc($val){
   if( gettype($val) === "array"  &&  !is_callable($val) ){
      $keys = array_keys($val);
      $StringKeyArray = array_filter($keys, function($val){
         return gettype($val) === "string";
      });
      return !empty($StringKeyArray) ? true : false;
   } else {
      return false;
   }
}


function isMethod($arr){
   return is_array($arr) && is_callable($arr);
}


function splitWith($separator="", $str){

   if( $separator === ""){
      return str_split($str);
   } else {
      return explode($separator, $str);
   }
}


function isNill($v){
   if( $v === null ||
       $v === "" ||
       $v === [] ){
      
      return true;
   } else {
      return false;
   }
}


function prependTab($str, $count=1, $tabChar=TABCHAR){
   $tab = "";
   
   for($i = 0; $i < $count; $i++){
      $tab .= $tabChar;
   }
   $tabbed = $tab  .  $str;
   
   return $tabbed;
}


function appendTab($str, $count=1, $tabChar=TABCHAR){
   $tab = "";
   
   for($i = 0; $i < $count; $i++){
      $tab .= $tabChar;
   }
   $tabbed = $str  .  $tab;
   
   return $tabbed;
}

##### right alignment #####
function formatR($val, $tabNum=15){
   # "r"(right): $alignType
   $str = "$val";
   if( $tabNum - length($str) <= 0 ){
      $formatted = $str;
   } else {
      $formatted = prependTab($str, $tabNum - length($str));
   }
   //_( $formatted );
   return $formatted;
}


function toUpperCase($str){
   return strtoupper($str);
}


function toLowerCase($str){
   return strtolower($str);
}


function head($arr){
   return $arr[0];
}


function last($arr){
   return $arr[ length($arr) -1 ];
}


function initial($arr){
   $lastIndex = count($arr) - 1;
   $newArr = [];
   
   foreach($arr as $index=>$val){
      if( $index !== $lastIndex ){
         $newArr[] = $val;
      }
   }
   return $newArr;
}


function rest($arr){
   $filterFn = function($curr, $indx){
      return $indx !== 0;
   };
   $newArr = array_filter( $filterFn, $arr);
   
   return $newArr;
}


function indexOf($val, $arr){
   $res = array_search($val, $arr);
   
   return $res !== False ? $res : Null;
}



function isOneDAssoc( $val ){
   # keyCond: at least one key is string
   # valDond: all val is not assoc/arr
   
   $flag_keyCond = some(function($currKey, $currVal, $i){
      return is_string($currKey);
   }, $val);
   $flag_valCond = every(function($currKey, $currVal, $i){
      return (
         !isAssoc($currVal)  &&
         !isArray($currVal)
      );
   }, $val);
   
   return $flag_valCond && $flag_keyCond;
}
function isOneDArray( $val ){
   return isOneDAssoc($val);
}
function isOneDimensional( $val ){
   
}

#-------------------------------------------------------#
#------------------   self dependant   -----------------#
#-------------------------------------------------------#
function isArray($val) {
   return (
      gettype($val) === "array"  &&
      !isAssoc($val) &&
      !is_callable($val)
   );
}


function type($val){
   # Function, method
   if ( is_callable($val)  &&
        !is_object($val) ) {
      return "[Function]";
      
   # Number
   } elseif ( gettype($val) !== ('string')   &&   is_numeric($val) ) {
      return "[Number]";
      
   # Null
   } elseif ( gettype($val) === "NULL" ) {
      return "[Null]";
      
   # AssocArray
   } elseif ( gettype($val) === "array" &&  
               isAssoc($val) ) {
      return "[AssocArray]";
      
   # Regex
   } elseif ( gettype($val) === "string"  && 
              preg_match('~^([^\w\d\s]).*(\1)[gimsxeADSUXJu]*$~', $val) ) {
      return "[Regex]";
   
   # Object
   } elseif ( gettype($val) === "object" ) {
      $arr = splitNamespaces( get_class($val) );
      //exho( "res::", $res );
      
      $className = $arr[ count($arr)-1 ];
      
      return "[object {$className}]";
   } elseif ( gettype($val) === "resource" ) {
      $resourceType = get_resource_type($val);
      
      return "[". ucfirst($resourceType) ." ". ucfirst(gettype($val)) ."]";
   
   } else {
      return "[".  ucfirst( gettype($val) )  ."]";
   }
}


function isType($type, $val){
   if( type($val) === $type ){
      return true;
   } else {
      return false;
   }
}


function includes($searchVal, $str){
   $pos = strpos($str, $searchVal);
   
   return isType('[Number]', $pos) ? true : false;
}


function length($arg){
   ##  $arg :: [Function]
   if( type($arg) === "[object Closure]" ||
       type($arg) === "[Function]" ){
      # normal fn
      if( gettype($arg) !== "array" ){
         $ref = new \ReflectionFunction($arg);
         
         return $ref->getNumberOfParameters();
         
      # method:: [$obj, 'methodName']
      } else {
         $obj = $arg[0];
         $methodName = $arg[1];
         $className = get_class($obj);
         $ref = new \ReflectionMethod($className, $methodName);
         
         return $ref->getNumberOfParameters();
      }
      
      
         
   ##  $arg :: [Object]
   } elseif ( strpos("object", type($arg)) ){
      return null;
      
   ##  $arg :: [Array]
   } elseif ( type($arg) === "[Array]" || 
              type($arg) === "[AssocArray]" ){
      return count( $arg );
   
   ##  $arg :: [String]
   } elseif ( type($arg) === "[String]" ){
      return mb_strlen( $arg );
   
   ##  arg :: [Number], [Null]
   } else {
      throw new \Exception("invalid type of Arguments");
   }
}


function _forEach($fn, $arr){
   ## assocArr
   if( isAssoc($arr) ){
      $indx = 0;
      
      foreach($arr as $key=>$val){
         $fn($key, $val, $indx, $arr);
         $indx++;
      }
   
   ## arr
   } else {
      foreach($arr as $indx=>$val){
         $fn($val, $indx, $arr);
      }
   }
}


function object2String($obj, $depth=0, $caller=null){
   $str = "";
   $BASE = 1;
   $tabNum = $BASE * (1 + $depth); // $BASE + $BASE*$depth;
   
   # get properties
   $arr = get_object_vars( $obj );
   
   # no prop
   if( empty($arr) ){
      $str = "{}";
      
      return $str;
      
   # has props
   } else {
      $indx = 0;
      $lastIndex = count($arr) - 1;
   
      #####  '{'  ####
      # type($caller) === Array
      if( isType('[Array]', $caller) ){
         $str .= "<br>".  prependTab("{<br>", $depth);
      } else {
         $str .= '{<br>';
      }
      
      foreach($arr as $key=>$val){
      
         # type(val) === object
         $key = toString($key);
         
         if ( is_object($val) ) {
            $str .= prependTab("{$key}", $tabNum)  .": ".  object2String($val, $depth+1, $obj);
         
         # type($val) !== object
         } else {
            $str .= prependTab("{$key}", $tabNum)  .": ".  toString($val, $depth+1, $obj);
            
            # '<br>' except $lastIndex
            if( $indx !== $lastIndex ){
               $str .= "<br>";
            }
         }
         $indx++;
      }
   
      # '}'
      $str .= "<br>";
      $str .= prependTab("}", $depth);
   
      return $str;
   }
}


function prettify($arr, $depth=0, $caller=null, $format_assoc=False){
   
   $BASE = 1;
   
    ## AssocArr || (Array  && $format_assoc: true )
   if( isAssoc($arr) ||
       $format_assoc === true ){
      
      $str = "";
      $indx = 0;
      
      foreach($arr as $key=>$val){
         $lastIndex = length($arr) - 1;
         
         
         ###### (1) add <br>, but not in last element  ######
         if( $indx > 0  ||  $depth > 0 ){
            $str .= "<br>";
         }
         
         ###### (2) add tab
         $key = toString($key);
         $str .= prependTab("[{$key}]:", $depth);
         
         ###### (3) add "[$key]: $val"  ######
         # $curr :: [AssocArray]
         if( isType("[AssocArray]", $val) ){
            $str .= prettify($val, $depth+1, $arr, $format_assoc);
         
         # $curr :: [Array]
         } elseif( isType("[Array]", $val) ){
            $str .= prettify($val, $depth, $arr, $format_assoc);
         
         # $curr :: ![Array] && ![AssocArray]
         } else {
            # inject $curr
            $str .= " ".  toString($val, $depth+1, $arr);
         }
         $indx++;
      }
      
      return $str;

   ## Array, $format_assoc: false 
   } elseif( isArray($arr)  &&  $format_assoc === false ){
      
      $str = "";
         
      if( empty($arr) ){
         $str .= "". prependTab("()", $depth*$BASE);
      } else {
         _forEach(function($curr, $indx, $arr) use (&$str, $depth, $format_assoc, $BASE, $caller){
            $lastIndex = length($arr) - 1;
            
            ######  (1) add `(`  ######
            if ( $indx === 0  ){
               # not nested arr
               if( $depth === 0 ){
                  $str .= "(";

               # nested arr
               } else {
                  # type($caller) === Array 
                  if( isType("[Array]", $caller) ){
                     $str .= prependTab("(", $depth);
                     
                  # type($caller) !== Array
                  } else {
                     $str .= " (";
                  }
               }
            }
            
            ######  (2) add $curr  ######
            # $curr :: [Array]
            if( isType("[Array]", $curr) ){
               $str .= "<br>".  prettify($curr, $depth+1, $arr, $format_assoc);
            
            # $curr :: [AssocArray]
            } elseif( isType("[AssocArray]", $curr) ){
               $str .= "<br>".  prettify($curr, $depth+1, $arr, $format_assoc);
            
            # $curr :: ![Array] && ![AssocArray]
            } else {
               # inject $curr
               $str2 = toString($curr, $depth+1, $arr);
               
               # type($curr) === object
               if( preg_match("~\[object ~", $str2) ){
                  # not nested arr
                  if( $depth === 0 ){
                     $str .= "<br>".  prependTab($str2, $BASE);
                  } else {
                     $str .= "<br>".  prependTab($str2, $depth*$BASE);
                  }
               
               # type($curr) !== object
               } else {
                  $str .= $str2;
               }
            }
         
            ######  (3) add `,` | `)`  ######
            # lastIndex
            if ( $indx === $lastIndex ){
            
               # type($curr) ===  Array|Assoc
               if( isArray($curr)  ||  isAssoc($curr) ){
                  $str .= "<br>".  prependTab(")", $depth*$BASE);
               
               # type($curr) === object
               } elseif ( is_object($curr) ){
                  if( $caller ){
                     $str .= "<br>".  prependTab(")", $depth);
                  } else {
                     $str .= ")";
                  }
               } else {
                  $str .= ")";
               }
            # not lastIndex
            } else {
               $str .= ", ";
               
               if( isType("[AssocArray]", $curr) ){
                  $str .= "<br>";
               }
            }
         }, $arr);
      }
      
      return $str;
   }
}


function toString($arg, $depth=0, $caller=Null){
   ## String, Regex
   if( isType("[String]", $arg)  ||  isType("[Regex]", $arg) ){
      # typeRepresentation
      if( preg_match('~\[.*\]~', $arg) ){
         return $arg;
      } else {
         return "\"{$arg}\"";
      }
      
   ## Number
   } elseif( isType("[Number]", $arg) ){
      //exho('[Number]:: ', $arg);
      return "{$arg}";
   
   ## Boolean
   } elseif ( isType("[Boolean]", $arg) ){
      return $arg === true ? "true" : "false";
   
   ## Array, AssocArray
   } elseif ( isAssoc($arg)  ||
              isArray($arg) ){
      return prettify($arg, $depth, $caller);
   
   ## Object
   } elseif ( is_object($arg) ){

      ### Closure class
      if( $arg instanceof \Closure ){
         return "[object Closure]";
         
      ### exist '__toString()' method
      } elseif( method_exists($arg, '__toString') ){
         return $arg->__toString();
      
      } else {
         return object2String($arg, $depth, $caller);
      }
   
   ## Function
   } elseif ( isType("[Function]", $arg) ){
      # method
      if( isMethod($arg) ){
         // $obj = $arg[0];
         $methodName = $arg[1];
         // $className = get_class($obj);
         // $ref = new \ReflectionMethod($className, $methodName);
         return "{$methodName}()";
         
      # normal fn
      } else {
         $ref = new \ReflectionFunction($arg);
         $fnName = $ref->getName();
      
         return "{$fnName}()";
      }
      
   ## Null,
   } else {
      return type($arg);
   }
}


function joinWith($jointer, ...$rest){
   ### jointer :: [String]
   if( isType("[String]", $jointer) ){
   
      ### length( rest ) : 1
      if( length($rest) === 1 ){
      
         ## (1-1) rest[0] :: AssocArray
         if( isAssoc($rest[0]) ){
            $assocArr = $rest[0];
            $newArr = [];
            
            foreach($assocArr as $key=>$val){
               $str = "";
               $str = "{$key}{$jointer}{$val}";
         
               $newArr[] = $str;
            }
            return $newArr;
            
         ## (1-2) rest[0] :: Array
         } elseif ( isArray($rest[0]) ){
            
            $arr = $rest[0];
            $str = "";
            
            _forEach(function($curr, $indx) use (&$str, $jointer){
               if( $indx === 0 ){
                  $str .= $curr;
               } else {
                  $str .= "{$jointer}{$curr}";
               }
            }, $arr);
            return $str;
         }
      

      ### length( rest) >= 2
      ##
      ##      ...$rest :: ...String
      ##      joinWith( jointer, ...str )
      ##      joinWith( jointer, "a", "b", "c", ... )
      } elseif ( length($rest) >= 2 ){
         $str = "";
         $filtered = filter(function($curr){
            return (
               isType("[String]", $curr) ||
               isType("[Number]", $curr)
            );
         }, $rest);
         
         foreach($filtered as $indx=>$curr){
            if( $indx === 0 ){
               $str .= "{$curr}";
            } else {
               $str .= "{$jointer}{$curr}";
            }
         }
         return $str;
      }
   
   ### jointer :: fn
   } else {
      $jointFn = $jointer;
      
      ### length( rest ) : 1
      if( length($rest) === 1 ){
      
         ## (1) rest[0] :: AssocArray
         if( isAssoc($rest[0]) ){
            $assocArr = $rest[0];
            $newArr = [];
            
            _forEach(function($key, $val, $indx, $arr) use (&$newArr, $jointFn){
               $newArr[] = $jointFn($key, $val, $indx, $arr);
            }, $assocArr);
            
            return $newArr;
            
          ## (1) rest[0] :: Array
         } elseif ( isArray($rest[0]) ){
            $arr = $rest[0];
            $str = "";
            
            _forEach(function($curr, $indx, $arr) use (&$str, $jointFn){
               $str .= $jointFn($curr, $indx, $arr);
            }, $arr);
            
            return $str;
         }
      
      ### ...$rest :: ...String
      ## (2) joinWith( jointer, str1, str2 )
      ##     joinWith( jointer, ...str )
      } elseif ( length($rest) >= 2 ){
         ## filter $rest that is type [String]
         $filtered = filter(function($curr){
            return isType("[String]", $curr);
         }, $rest);
         
         $str = "";
            
         _forEach(function($curr, $indx, $arr) use (&$str, $jointFn){
            $str .= $jointFn($curr, $indx, $arr);
         }, $filtered);
         
         return $str;
      }
      
   }
}

function toAttr($assocArr){
   if( isAssoc($assocArr) ){
      $str = "";
      $flatArr = joinWith(function($key, $val, $indx){
         
         ### style attribute
         if( isAssoc($val) ){
            $val_arr = joinWith(": ", $val);
            $val_str = joinWith( "; ", $val_arr )  .";";
            
            return "{$key}='{$val_str}'";
         } else {
            return "{$key}='{$val}'";
         }
      }, $assocArr);
   
      foreach($flatArr as $indx => $val){
         $str .= ($indx === 0 ? $val : " $val");
      }
      //exho( $str );
      return $str;
      
   }
}


function inject($val, $tagName="div", $assocArr=[]){
   if( empty($assocArr) ){
      $assocArr = [
         "style" => [
            "padding" => "0 0.5rem",
         ],
      ];
   }
   
   $str = toAttr($assocArr);
   echo "<{$tagName} {$str}>".  $val  ."</{$tagName}>";
}


function _ (...$args){
   $style = [
      "style" => [
         "background" => "#faf8f6",
         "padding" => "1rem",
         "margin-top" => "3px",
         "font" => "400 1rem/1.2 Inconsolata, Courier, monospace",
         "letter-spacing" => "-0.3px",
         "box-shadow" => "0 3px 9px 0px rgba(0,0,0,0.2)",
         "border-radius" => "5px",
         //"border-bottom" => "1px solid #e9e9e9",
      ],
   ];
   #### args.length <= 1
   if( count($args) <= 1 ){
      inject( toString($args[0]), "div", $style);
      
   } else {
   #### args.length >= 2
      $str = "";
      
      foreach($args as $arg){
         if( type($arg) === "[Array]" ){
            $str .= toString($arg);
         } else {
            $str .= toString($arg)  ." ";
         }
      }
      inject( $str, "DIV", $style );
   }
}


function pretty($arr, $format_assoc=true){   
   _( prettify($arr, 0, null, $format_assoc) );
}


function append($val, &$data){
   # Array(mutable)
   if( isType('[Array]', $data) ){
      $data[] = $val;
   
      return $data;
   }
}


function prepend($val, &$data){
   if( isType('[Array]', $data) ){
      $newArr = [];
      for($i=0; $i < count($data)+1; $i++){
         if( $i === 0 ){
            $newArr[0] = $val;
         } else {
            $newArr[$i] = $data[$i-1];
         }
      }
      $data = $newArr;
      
      return $newArr;
   }
}


function find($item, $arr){
   $flag = false;
   
   if( isArray($arr) ){
      foreach( $arr as $index=>$val){
         ## $val :: Array
         if( type($val) === "[Array]" ){
            find($item, $val) ? $flag = find($item, $val) : false;
            
         ## 
         } else {
            if( $item === $val ){
               $flag = true;
            }
         }
      }
   } elseif ( isAssoc($arr) ){
   
   }
   return $flag;
}


function some($fn, $arr){
   $flag = false;
   
   #### $arr :: AssocArray
   if( isAssoc($arr) ){
      _forEach(function($key, $val, $indx, $arr) use ($fn, &$flag){
         if ( $fn($key, $val, $indx, $arr) === true ){
            $flag = true;
         }
      }, $arr);
   
   #### $arr :: Array
   } else {
      _forEach(function($curr, $indx, $arr) use ($fn, &$flag){
         if ( $fn($curr, $indx, $arr) === true  ){
            $flag = true;
         }
      }, $arr);
   }
   return $flag;
}


function every($fn, $arr){
   $flag = true;
   
   #### $arr :: AssocArray
   if( isAssoc($arr) ){
      _("Assoc in every()");
      _forEach(function($key, $val, $indx, $arr) use ($fn, &$flag){
         if ( $fn($key, $val, $indx, $arr) === false ){
            $flag = false;
         }
      }, $arr);
   
   #### $arr :: Array
   } else {
      _("Array in every()");
      _forEach(function($curr, $indx, $arr) use ($fn, &$flag){
         if ( $fn($curr, $indx, $arr) === false ){
            $flag = false;
         }
      }, $arr);
   }
   return $flag;
}


function isInRange($num, $ranges){
   return find($num, $ranges);
}


function takeAt($startIndex, $num, $arr){
   $endIndex = $startIndex + $num - 1;
      
   return filter(function($curr, $indx) use($startIndex, $endIndex){
      return ($startIndex <= $indx  &&  $indx <= $endIndex);
   }, $arr);
}






function map($fn, $arr){
   $newArr = [];
   
   #### $arr :: AssocArray
   if( isAssoc($arr) ){
      _forEach(function($key, $val, $indx, $arr) use ($fn, &$newArr){
         $newArr[] = $fn($key, $val, $indx, $arr);
      }, $arr);
   
   #### $arr :: Array
   } else {
      _forEach(function($curr, $indx, $arr) use ($fn, &$newArr){
         $newArr[] = $fn($curr, $indx, $arr);
      }, $arr);
   }
   
   return $newArr;
}


function toCamelCase($str){
   $str = strtolower($str);
   ## detect 'snake_case', 'space'
   $regex = "/\s+(.)|_(.)/";
   $replaced = replace( $regex, function($matches){
      $match = $matches[0];
      $p1 = $matches[1];
      $p2 = $matches[2]; 
      
      
      return toUpperCase( $p1  .  $p2);
      
   }, $str);
   
   return $replaced;
}


function toSnakeCase($str){
   return "snaked!";
}


function filter($fn, $arr){
   $newArr = [];
   
   #### $arr :: AssocArry
   if( isAssoc($arr) ){
      _forEach(function($key, $val, $indx, $arr) use ($fn, &$newArr){
         $fn($key, $val, $indx, $arr) ? $newArr[$key] = $val : false;
      }, $arr);
   
   #### $arr :: Array
   } else {
      _forEach(function($curr, $indx, $arr) use ($fn, &$newArr){
         $fn($curr, $indx, $arr) ? $newArr[] = $curr : false;
      }, $arr);
   }
   
   return $newArr;
};

function take($num, $arr){
   return filter(function($curr, $indx) use($num){
      return $indx < $num;
   }, $arr);
}


function concat(...$args){
   $length = length($args);
   
   if( $length <= 1 ){
      //$arg = $args[0];
      return $args;
   }
   ////////
   $isArray = function($curr){
      return isType("[Array]", $curr);
   };
   $isString = function($curr){
      return isType("[String]", $curr);
   };
   ///////
   
   ## at least one arg is [Array]
    # (array concat)
   if( some($isArray, $args) ){
      $newArr = [];
      
      foreach($args as $arg){
         if( isType("[Array]", $arg) ){
            foreach($arg as $ar){
               $newArr[] = $ar;
            }
         } else {
            $newArr[] = $arg;
         }
      }
      return $newArr;

   ## no Array at all
   } else {
      # (string concat)
      ## at least one elements is [String]
      $str = "";
         
      foreach($args as $arg){
         $str .= $arg;
      }
      return $str;
   }
}


function reduce($fn, $initVal, $arr){
   $acc = $initVal;
   
   #### $arr :: AssocArry
   if( isAssoc($arr) ){
      _forEach(function($key, $val, $indx, $arr) use ($fn, &$acc){
         $acc = $fn($acc, $key, $val, $indx, $arr);
      }, $arr);
   
   #### $arr :: Array
   } else {
      _forEach(function($curr, $indx, $arr) use ($fn, &$acc){
         $acc = $fn($acc, $curr, $indx, $arr);
      }, $arr);
   }
   
   return $acc;
};

function flatten($arr, $recursive=false, $depth=0){
   ### Array
   if( isArray($arr) ){
       ### Array of AssocArray
      if( isAssoc($arr[0]) ){
         return flatten($arr[0], $recursive, $depth+1);
      
      ### Array of primitive Value
      } else {
         $acc = reduce(function($acc, $curr){
         //_( $curr );
            return concat($acc, $curr);
         }, [], $arr);
         $noArrayExists = some(function($curr){
         return type($curr) !== "[Array]";
         }, $acc);
   
         if( $recursive ){
            if( $noArrayExists ){
               return $acc;
            } else {
               return flatten($acc, true, $depth+1);
            }
         } else {
            return $acc;
         }
      }
   ### AssocArray
   } else {
      return $arr;
   }
}


function flattenDeep($arr, $depth=0){
   ### Array
   if( isArray($arr) ){
      ### Array of AssocArray
      if( isAssoc($arr[0]) ){
         return flattenDeep($arr[0], $depth+1);
      
      ### Array of Primitive Value
      } else {
         ###  1-level latten 
         $acc = reduce(function($acc, $curr){
            return concat($acc, $curr);
         }, [], $arr);
         
         $arrayExists = some(function($curr){

         
            return type($curr) === "[Array]";
         }, $acc);
   
         if( $arrayExists ){
            return flattenDeep($acc, $depth+1);
         } else {
            return $acc;
         }
      }
   ### AssocArr
   } else {
      return $arr;
   }
}


function filterFlags($regexStr, $flag){
   $regObj = new RegExpO( $regexStr );
   $newRegObj = $regObj->filterFlags($flag);
  // _( $newRegObj->value() );
   return $newRegObj->valueOf();
}


function pushTo($val, $index, $arr){
   $lastIndex = length($arr) - 1;
   $before = take($index, $arr);
   $after = takeAt($index, $lastIndex, $arr);
   //_($before, $after);
   $newBefore = concat($before, $val);
   $newArr = concat($newBefore, $after);
   
   return $newArr;
}



function match($searchPat, $str, $offset=false){
   ## $searchPat :: [Regex]
   if( isType("[Regex]", $searchPat) ){
      $flags = RegExpO::getFlags($searchPat);
      $pattern = RegExpO::getRegex($searchPat);
      
      ## global match
      if( $flags  &&  in_array("g", $flags) ){
         $notGFlags = filter(function($curr, $indx){
            return $curr !== "g";
         }, $flags);
         $pattern .= join($notGFlags);
         
         if( $offset ){
            preg_match_all($pattern, $str, $matches);
            
            return $matches;
         } else {
            preg_match_all($pattern, $str, $matches);
            
            return $matches[0];
         }
         
      ## single match
      } else {
         if( $offset ){
            preg_match($searchPat, $str, $matches);
            
            return $matches;
         } else {
            preg_match($searchPat, $str, $matches);
            
            return $matches[0];
         }
      }
   
   ## $searchPat :: [String]
   } elseif ( isType("[String]", $searchPat) ){
      
      $res = strpos($str, $searchPat);
      
      # no match #
      #   @return: <null>
      if( $res === false ){
         return null;
      # match #
      #   @return: <Num> position index
      } else {
         return $res;
      }
   } else {
      throw new \Exception("Invalid type of arguments in 'match()'");
   }
}



function replace($searchPattern, $replacePattern, $str){
   ## $searchPattern === [Regex]
   //_( type($searchPattern) );
   if( isType("[Regex]", $searchPattern) ){
      $regex = $searchPattern;
      
      ## $replacePattern === [Function]
      //_('type($replacePattern) in replace', type($replacePattern) );
      //_( 'isType("[Closure Object]", $replacePattern)',
         
      if( isType("[object Closure]", $replacePattern) ){
         $replacer = $replacePattern;
         $regex = filterFlags($regex, "g");
         
         return preg_replace_callback($regex, $replacer, $str);
      ## $replacePattern === [String]
      } else {
         $newVal = $replacePattern;
         $regex = filterFlags($regex, "g");
         
         return preg_replace($regex, $newVal, $str);
      }
      
   #### $searchPattern === [String]
   } else {
      $searchVal = $searchPattern;
      $newVal = $replacePattern;
      
      return str_replace($searchVal, $newVal, $str);
   }
}

function curry($fn){
   $inner = function($arguments) use ($fn, &$inner){
   
      return function(...$args) use ($fn, &$inner, $arguments){
         $paramLength = ( new \ReflectionFunction($fn) )->getNumberOfParameters();
         $passedArgs = concat($arguments, $args);
         $passedArgsLength = length($passedArgs);
         
         ## passedArgsNum >= paramNum :: ok
         if( $passedArgsLength >= $paramLength ){
            return $fn( ...$passedArgs );
         
         ## passedArgsNum <= paramNum :: not yet
         } else {
            return $inner( $passedArgs );
         }
      };
      
   };
   return $inner( [] );
}


function toClosure($fn){
   $refFn = new \ReflectionFunction($fn);
   
   ##### chaeck if fn is closure or not.
   ## fn :: closure
   if( $refFn->isClosure() ){
      $closure = $fn;
   
   ## fn :: not closure
   } else {
      $closure = \Closure::fromCallable($fn);
   }
   return $closure;
}


function apply($fn, $obj, $arrArgs=[]){
   $closure = toClosure($fn);
   
   ## null check for $obj
   if( $obj ){
      return $closure->call($obj, $arrArgs);
   } else {
      return $closure($arrArgs);
   }
}


function call($fn, $obj, ...$args){
   $closure = toClosure($fn);
   
   ## null check for $obj
   if( $obj ){
      return $closure->call($obj, ...$args);
   } else {
      return call_user_func($fn, ...$args);
   }
}


function bind($fn, $obj, ...$bArgs){
   $refFn = new \ReflectionFunction($fn);
   
   ##### chaeck if fn is closure or not.
   ## fn :: closure
   if( $refFn->isClosure() ){
      $closure = $fn;
   
   ## fn :: not closure
   } else {
      $closure = \Closure::fromCallable($fn);
   }
   
   ## bind $obj to $closure
   $func = $closure->bindTo($obj);
   
   return function(...$args) use ($func, $bArgs){
      if( length($func) <= length($bArgs) + length($args) ){
         return $func(...$bArgs, ...$args);
      } else {
         return bind($func, null, ...$bArgs, ...$args);
      }
   };
}



function merge($arr1, $arr2, $depth=0){
   if( isAssoc($arr1) ){
      foreach( $arr1 as $key1=>$val1){
         foreach( $arr2 as $key2=>$val2){
            # keyMatch
            if( $key1 === $key2 ){
         
               ###$val1 IsAssoc
               if( isAssoc($val1) ){
                  $arr1[$key1] = merge($val1, $val2, $depth+1);
                 
               ## overwrite
               } else {
                  $arr1[$key1] = $val2;
               }
            # no keyMatch
            ## add new key-val
            } else {
               $arr1[$key2] = $val2;
            }
         }
      }
   
   ### isArray($arr1)
   } else {
      $arr1 = concat($arr1, $arr2);
   }
   return $arr1;
}


function repeat($v, $num, ...$args){
   # Function
   if( is_callable($v) ){
      $res = "";
      
      for($i=0; $i < $num; $i++){
         $res .= $v(...$args);
      }
      return $res;
   
   # String #
   # repeat($v, $num, $assoc)
   # 
   # @pramas:
   #
   #    $assoc = [
   #       Num "interval" : interavl that 'char' is inserted
   #       Str "char" : char that is inserted to string when repeat count and 
   #                    'interval' match
   #    ]
   # 
   #
      
   } else {
      $str = "";
      $assoc = $args[0];
      $interval = $assoc["interval"];
      $char = $assoc["char"];
      
      for($i=0; $i < $num; $i++){
         if( $i === $interval - 1 ){
            $str .= $char;
         } else {
            $str .= $v;
         }
      }
      
      return $str;
   }
}