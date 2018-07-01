<?php

/**
 * Applicative functor
 *
 * @package bingo-functional
 * @author Lochemem Bruno Michael
 * @license Apache 2.0
 */

namespace Chemem\Bingo\Functional\Functors\Applicatives;

use Chemem\Bingo\Functional\Common\Applicatives\ApplicativeTrait;
use Chemem\Bingo\Functional\Common\Applicatives\ApplicativeAbstract;
use Chemem\Bingo\Functional\Functors\Maybe\Maybe;
use Chemem\Bingo\Functional\Functors\Maybe\Just;
use Chemem\Bingo\Functional\Functors\Maybe\Nothing;

final class Applicative extends ApplicativeAbstract
{
    /**
     * @see ApplicativeTrait
     */

    use ApplicativeTrait;
}
