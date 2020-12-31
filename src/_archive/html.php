<?php

namespace Autil\HTML;

use OOPe\Classes\DOM\Document;




/**
 *
 */
function render($val, $tagName="div", $assocArr=[]){
   # reder highlighted code
   
   if( match('STYLE_CODE', $tagName) !== Null ){

      $assocArr = [
         "class" => "",
         "style" => [
            "padding" => "0.875rem 2.5rem",
            "margin-top" => "3.5rem",
            "font" => "400 0.875rem/1.2 Incinsolata, Courier, monospace",
            "background" => "#f6f6f6",
            
            "opacity" => "0.73",
            "border-left" => "11px solid #3535A6",
         ],
      ];
      inject( highlight($val), "pre", $assocArr);
      return;
   }
   
   
   # 
   if( empty($assocArr) ){
      $assocArr = [
         "style" => [
            "margin-top" => "3.5rem",
            "font" => "400 1rem/1.2 sans-serif",
         ],
      ];
   }
   
   inject( $val, $tagName, $assocArr);
}


function escapeHTML($str, ...$ops){
   $flags = $ops[0] ? $ops[0] : ENT_COMPAT;
   $encoding = $ops[1] ? $ops[1] : ini_get("default_charset");
   $double_encode = $ops[2] ? $ops[2] : TRUE;
   
   return htmlspecialchars($str, $flags, $encoding, $double_encode);
}


function unescapeHTML($str, ...$flags){
   $flags = $flags[0] ? $flags[0] : ENT_COMPAT | ENT_HTML401;
   return htmlspecialchars_decode($str, $flags);
}


function space2Dot($str, $interval=5, $char=","){
   $regex = '/^\s*/';
   $replacer = function($match) use ($interval, $char){
      $len = length($match[0]);
      return repeat('.', $len, [
         "interval" => $interval,
         "char" => $char,
      ]);
   };
   return replace($regex, $replacer, $str);
}


function escapeD($str){
   $escaped = escapeHTML($str);
   
   return space2Dot($str);
}


/**
 *
 */
function highlight($str){
   $str = "<?php\n".  $str;
   
   return highlight_string($str, true);
}


function toLiteral($val){
   $str = new \OOPe\StringO( toString( $val ) );
   
   
   return $str->replace('~(\(|\)|<br>)~', function($match){
      $match = $match[0];
      
      if( $match === '<br>' ){
         return '';
      } elseif( $match === '(' ){
         return '[';
      } elseif( $match === ')' ){
         return ']';
      }
      
   });
}


function getOrCreateDOMDoc(){
   $ref = new \ReflectionClass('OOPe\Classes\DOMDoc');
   
   foreach( $GLOBALS as $key=>$val){
      # already exist
      
      if( $val instanceof Document ){
         _( $key, $val );
         return $val;
      }
   }
   # no exist yet
   return new Document();
}





//getOrCreateDOMDoc()->init()->render();

//(new Document())->init()->render();
// === (new Document)->init()->render(); // without parenthesis on instanciation
// === Document()->init()->render(); // using helper function to instanciate class 'OOPe\DOM\Document'