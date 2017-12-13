<?php

/**
 * filter function
 * 
 * filter :: (a -> Bool) -> [a] -> [a]
 * @package bingo-functional
 * @author Lochemem Bruno Michael
 * @license Apache 2.0
 */

namespace Chemem\Bingo\Functional\Algorithms;

const filter = "Chemem\\Bingo\\Functional\\Algorithms\\filter";

function filter(callable $func, array $collection, array $acc = []) : array
{
    $collection = array_values($collection);

    foreach ($collection as $value) {
        $acc[] = call_user_func($func, $value) ? $value : null;
    }

    return array_intersect($collection, $acc);
}