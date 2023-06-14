<?php

//se ReflectionClass;

function getExtendedClasses($className) {
    $extendedClasses = [];
/*
    $reflectionClass = new ReflectionClass($className);
    $parentClass = $reflectionClass->getParentClass();

    while ($parentClass !== false) {
        $extendedClasses[] = $parentClass->getName();
        $parentClass = $parentClass->getParentClass();
    }*/

    return $extendedClasses;
}
