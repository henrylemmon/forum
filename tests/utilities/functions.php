<?php

function factory($class, $method, $attributes = [])
{
    return $class::factory()->$method($attributes);
}
