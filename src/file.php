<?php

namespace Autil;



function searchfile($searchName, $path_dir=DOC_ROOT, $depth=0){
   $list = scandir( $path_dir );
   $matchedArr = [];
   
   foreach($list as $name) {
      $path_file = $path_dir  ."/".  $name;
      
      ## match
      if( $searchName === $name ){
         
         $matchedArr = append($path_file, $matchedArr);
         
      ## dir exists:: continue search
      } elseif( is_dir($path_file)  &&
         $name !== "."  &&
         $name !== ".."  ){
         
         $res = searchFile( $searchName, $path_file, $depth+1);
         if( !empty($res) ){
            //$matchedArr = append( $res, flatten($matchedArr) );
            $matchedArr = append( $res, $matchedArr );
         }
      }
   }
   
   return flattenDeep($matchedArr);
}



function getFileContents($fileName){
   $pathArr = searchFile($fileName);
   _( $pathArr );
   if( empty($pathArr) ){
      die("No such file exist");
   } else {
      $cssPath = $pathArr[0];
      
      return file_get_contents($cssPath);
   }
}
function loadStyles(...$fileNames){
   if( !empty($fileNames) ){
      $str = "";
      foreach( $fileNames as $fileName ){
         $str .= getFileContents($fileName)  ."\n";

      }
      $html = <<<DOC
<style>
$str
</style>
DOC;
   
      echo $html;
   }
}























