<?php

function factory($class, $method, $attributes = [], $times = null)
{
    return $class::factory($times)->$method($attributes);
}
