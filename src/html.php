<?php

namespace Autil;


use OOPe\Classes\DOMDoc;




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
         "class" => "alert alert-warning text-center",
         "style" => [
            "margin-top" => "3.5rem",
            "font" => "700 1.3125rem/1.2 sans-serif",
         ],
      ];
   }
   
   inject( $val, $tagName, $assocArr);
}


function escape($str, ...$ops){
   $flags = $ops[0] ? $ops[0] : ENT_COMPAT;
   $encoding = $ops[1] ? $ops[1] : ini_get("default_charset");
   $double_encode = $ops[2] ? $ops[2] : TRUE;
   
   return htmlspecialchars($str, $flags, $encoding, $double_encode);
}


function unescape($str, ...$flags){
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
   $escaped = escape($str);
   
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
   ##  'DOMDoc(DOMDocument)' instance must exist only one in whole PHP envs.
   # 
   #  this flow pushing into 'OOPe\DOMDoc' fail proverbly because of returned value is
   #  copied instance, not reference(pointer), if defined with '&'.
   $ref = new \ReflectionClass('OOPe\Classes\DOMDoc');
   foreach( $GLOBALS as $key=>$val){
      # already exist
      if( $val instanceof DOMDoc ){
         return $val;
      }
   }
   # no exist yet
   return new DOMDoc();
}


function loadCss($path){
   $doc = getOrCreateDOMDoc();
   
   $doc->create('link')
      ->attr("rel", "stylesheet")
      ->attr("href", $path)
      ->appendTo('head');
}


function loadJs($path){
   $doc = getOrCreateDOMDoc();
   
   $doc->create('script')
      ->attr("src", $path)
      ->appendTo('body');
}


getOrCreateDOMDoc()->init()->render();


























