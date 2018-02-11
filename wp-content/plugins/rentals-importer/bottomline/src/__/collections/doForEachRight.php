<?php

namespace collections;

/**
 * Iterate an array or other foreach-able without making a copy of it.
 *
 * Code for PHP_VERSION >= 5.5.(using `yiel`) is from mpen and linepogl
 * See https://stackoverflow.com/a/36605605/1956471
 *
 * @param array|\Traversable $iterable
 * @return Generator
 */
 if (version_compare(PHP_VERSION, '5.5.0', '<')) {
     eval('
     function iter_reverse($iterable) {
         return array_reverse($iterable, true);
     }
     ');
 } else {
     eval('
     function iter_reverse($iterable) {
         for (end($iterable); ($key = key($iterable)) !== null; prev($iterable)) {
             yield $key => current($iterable);
         }
     }
     ');
 }

/**
 * Iterate over elements of the collection, from right to left, and invokes iteratee
 * for each element.
 *
 * The iteratee is invoked with three arguments: (value, index|key, collection).
 * Iteratee functions may exit iteration early by explicitly returning false.
 *
 ** __::doForEachRight([1, 2, 3], function ($value) { print_r($value) });
 ** // → (Side effect: print 3, 2, 1)
 *
 * @param array|object $collection The collection to iterate over.
 * @param Closure $iterate The function to call for each value.
 *
 * @return null
 *
 */
function doForEachRight($collection, \Closure $iteratee)
{
    \__::doForEach(iter_reverse($collection), $iteratee);
}
