<?php

function exho(...$args){
   foreach( $args as $arg){
      echo "<h3>{$arg}</h3>";
   }
}

$startDir = getcwd();
exho( getcwd() );

chdir("../../");
exho( getcwd() );

chdir( $startDir );
exho( getcwd() );