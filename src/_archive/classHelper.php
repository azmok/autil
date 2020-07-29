<?php

namespace Autil;

use \OOPe\Classes\NullO;
use \OOPe\Classes\NumberO;
use \OOPe\Classes\StringO;
use \OOPe\Classes\ArrayO;
use \OOPe\Classes\AssocArrayO;
use \OOPe\Classes\ObjectO;
use \OOPe\Classes\FunctionO;
use \OOPe\Classes\RegExpO;

use \OOPe\Classes\DOM\Document;
use \OOPe\Classes\DOM\Element;

use \OOPe\Classes\LoggerO;





function NullO(...$args){
   return new NullO(...$args);
}
function NumberO(){
   return new NumberO(...$args);
}
function StringO(...$args){
   return new StringO(...$args);
}
function ArrayO(...$args){
   return new ArrayO(...$args);
}
function AssocArrayO(...$args){
   return new AssocArrayO(...$args);
}
function ObjectO(...$args){
   return new ObjectO(...$args);
}
function FunctionO(...$args){
   return new FunctionO(...$args);
}
function RegExpO(...$args){
   return new RegExpO(...$args);
}

function Document(...$args){
   return new Document(...$args);
}
function Elmement(...$args){
   return new Element(...$args);
}

function LoggerO(...$args){
   return new LoggerO(...$args);
}



















