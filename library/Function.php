<?php

function isFunction($fn)
{
    return !is_string($fn) && is_callable($fn);
}
