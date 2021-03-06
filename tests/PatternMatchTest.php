<?php

namespace Chemem\Bingo\Functional\Tests;

use PHPUnit\Framework\TestCase;
use Chemem\Bingo\Functional\{
    Algorithms as A,
    PatternMatching as PM
};

class PatternMatchTest extends TestCase
{
    public function testGetNumConditionsFunctionOutputsArrayOfArities()
    {
        $numConditions = PM\getNumConditions(['(a:b:_)', '(a:_)', '_']);

        $this->assertEquals(
            $numConditions, 
            [
                '(a:b:_)' => 2, 
                '(a:_)' => 1, 
                '_' => 0
            ]
        );
    }

    public function testMatchFunctionComputesMatches()
    {
        $match = PM\match(
            [
                '(dividend:divisor:_)' => function (int $dividend, int $divisor) {
                    return $dividend / $divisor;
                },
                '(dividend:_)' => function (int $dividend) {
                    return $dividend / 2;
                },
                '_' => function () {
                    return 1;
                }
            ]
        );

        $result = $match([10, 5]);

        $this->assertEquals($result, 2);
    }

    public function testEvalStringPatternEvaluatesStrings()
    {
        $strings = A\partialLeft(
            PM\evalStringPattern,
            [
                '"foo"' => function () {
                    return 'foo';
                },
                '"bar"' => function () {
                    return 'bar';
                },
                '_' => function () {
                    return 'undefined';
                }
            ] 
        );

        $this->assertEquals($strings('foo'), 'foo');
        $this->assertEquals($strings('baz'), 'undefined');
    }

    public function testEvalStringPatternEvaluatesNumbers()
    {
        $numbers = A\partialLeft(
            PM\evalStringPattern,
            [
                '"1"' => function () {
                    return 'first';
                },
                '"2"' => function () {
                    return 'second';
                },
                '_' => function () {
                    return 'undefined';
                }
            ]
        );

        $this->assertEquals($numbers(1), 'first');
        $this->assertEquals($numbers(24), 'undefined');
    }

    public function testArrayPatternEvaluatesArrayPatterns()
    {
        $patterns = A\partialLeft(
            PM\evalArrayPattern,
            [
                '["foo", "bar", baz]' => function ($baz) {
                    return strtoupper($baz);
                },
                '["foo", "bar"]' => function () {
                    return 'foo-bar';
                },
                '_' => function () {
                    return 'undefined';
                }
            ]
        );

        $this->assertEquals($patterns(['foo', 'bar']), 'foo-bar');
        $this->assertEquals($patterns(['foo', 'bar', 'cat']), 'CAT');
        $this->assertEquals($patterns([]), 'undefined');
    }

    public function testPatternMatchFunctionPerformsSingleValueSensitiveMatch()
    {
        $pattern = PM\patternMatch(
            [
                '"foo"' => function () {
                    $val = strtoupper('FOO');
                    
                    return $val;
                },
                '"12"' => function () {
                    return 12 * 12;
                },
                '_' => function () {
                    return 'undefined';
                }
            ],
            'foo'
        );

        $this->assertEquals($pattern, 'FOO');
    }

    public function testPatternMatchFunctionPerformsMultipleValueSensitiveMatch()
    {
        $pattern = PM\patternMatch(
            [
                '["foo", "bar"]' => function () {
                    $val = strtoupper('foo-bar');

                    return $val;
                },
                '["foo", "bar", baz]' => function ($baz) {
                    $val = lcfirst(strtoupper($baz));

                    return $val;
                },
                '_' => function () {
                    return 'undefined';
                }
            ],
            explode('/', 'foo/bar/functional')
        );

        $this->assertEquals($pattern, 'fUNCTIONAL');
    }
}