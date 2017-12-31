<?php

use PHPUnit\Framework\TestCase;
use Chemem\Bingo\Functional\Algorithms as A;
use Chemem\Bingo\Functional\Common\Callbacks as CB;

class AlgorithmTest extends TestCase
{
    public function testIdentityFunctionReturnsValueSupplied()
    {
        $value = A\identity('foo');
        $this->assertEquals($value, 'foo');
    }

    public function testComposeFunctionNestsFunctions()
    {
        $addTen = function (int $a) : int {
            return $a + 10;
        };
        $multiplyTen = function (int $b) : int {
            return $b * 10;
        };
        $composed = A\compose($addTen, $multiplyTen);
        $transformed = array_map($composed, [1, 2, 3]);

        $this->assertEquals($transformed, [110, 120, 130]);
    }

    public function testPickGetsArrayIndexValue()
    {
        $toPick = ['bar', 'foo'];

        $picked = A\pick($toPick, 'foo', CB\invalidArrayKey);
        $this->assertEquals($picked, 'foo');
    }

    public function testPluckReturnsArrayValue()
    {
        $toPluck = ['foo' => 'bar', 'baz' => 'foo-bar'];

        $plucked = A\pluck($toPluck, 'foo', CB\invalidArrayValue);
        $this->assertEquals($plucked, 'bar');
    }

    public function testZipReturnsZippedArray()
    {
        $nums = [1, 2];
        $positions = ['PG', 'SG'];

        $zipped = A\zip(
            function ($num, $pos) {
                return $num . ' is ' . $pos;
            },
            $nums,
            $positions
        );
        $this->assertEquals($zipped, ['1 is PG', '2 is SG']);
    }

    public function testCurryReturnsClosure()
    {
        $curryied = A\curry('preg_match');

        $this->assertInstanceOf(\Closure::class, $curryied);
    }

    public function testCurryNReturnsCurryiedFunction()
    {
        $curryied = A\curryN(2, 'array_key_exists')('foo')(['foo' => 'bar']);

        $this->assertEquals($curryied, true);
    }

    public function testUnzipReturnsArrayOfInitiallyGroupedArrays()
    {
        $zipped = A\zip(null, [1, 2], ['PG', 'SG']);
        $unzipped = A\unzip($zipped);

        $this->assertEquals(
            $unzipped,
            [
                [1, 2],
                ['PG', 'SG']
            ]
        );
        $this->assertTrue(is_array($unzipped));
    }

    public function testPartialLeftAppliesArgumentsFromLeftToRight()
    {
        $fn = function (int $a, int $b) : int {
            return $a + $b;
        };
        $partial = A\partialLeft($fn, 2)(2);

        $this->assertEquals($partial, 4);
    }

    public function testHeadReturnsFirstItemInArray()
    {
        $array = [1, 2, 3, 4];
        $this->assertEquals(A\head($array), 1);
    }

    public function testTailReturnsSecondToLastArrayItems()
    {
        $array = [1, 2, 3, 4];
        $this->assertEquals(
            A\tail($array),
            [2, 3, 4]
        );
    }

    public function testPartitionSubDividesArray()
    {
        $array = [1, 2, 3, 4];
        $this->assertEquals(
            A\partition(2, $array),
            [[1, 2], [3, 4]]
        );
    }

    public function testExtendAppendsElementsOntoArray()
    {
        $array = ['foo' => 'bar', 'baz' => 12];
        $extended = A\extend($array, ['foo' => 9, 'bar' => 19]);
        $this->assertEquals(
            $extended,
            [
                'foo' => 9,
                'baz' => 12,
                'bar' => 19
            ]
        );
    }

    public function testConstantFunctionAlwaysReturnsFirstArgumentSupplied()
    {
        $const = A\constantFunction(12);
        $this->assertEquals($const(), 12);
    }

    public function testIsArrayOfReturnsArrayType()
    {
        $array = [1, 2, 3, 4];
        $type = A\isArrayOf($array, CB\emptyArray);

        $this->assertEquals($type, 'integer');
    }

    public function testPartialRightReversesParameterOrder()
    {
        $divide = function (int $a, int $b) {
            return $a / $b;
        };

        $partialRight = A\partialRight($divide, 6)(3);
        $this->assertEquals($partialRight, 0.5);
    }

    public function testThrottleFunctionReturnsSuppliedFunctionReturnValue()
    {
        $toThrottle = A\constantFunction(12);

        $this->assertEquals(A\throttle($toThrottle, 2), 12);
    }

    public function testConcatFunctionConcatenatesStrings()
    {
        $wildcard = '/';
        $testsPath = A\concat($wildcard, 'path', 'to', 'tests');

        $this->assertEquals($testsPath, 'path/to/tests');
    }

    public function mapFunctionTransformsArrayElements()
    {
        $numbers = [1, 2, 3, 4, 5];

        $transformed = A\map(
            function ($val) {
                return $val + 10;
            },
            $numbers
        );

        $this->assertEquals($transformed, [11, 12, 13, 14, 15]);
    }

    public function testFilterFunctionSelectsArrayElementsBasedOnBooleanPredicate()
    {
        $numbers = [1, 2, 3, 4, 5];

        $filtered = A\filter(
            function ($val) {
                return $val < 4;
            },
            $numbers
        );

        $this->assertEquals($filtered, [1, 2, 3]);
    }

    public function testFoldFunctionTransformsCollectionIntoSingleValue()
    {
        $characters = [
            ['name' => 'Tyrion', 'incest' => false],
            ['name' => 'Cersei', 'incest' => true],
            ['name' => 'Jaimie', 'incest' => true]
        ];

        $reduce = A\fold(
            function ($acc, $val) {
                return $val['incest'] === true ?
                    $acc + 1 :
                    $acc;
            },
            $characters,
            0
        );

        $this->assertEquals($reduce, 2);
    }

    public function testArrayKeysExistFunctionDeterminesIfKeysExistInCollection()
    {
        $character = [
            'name' => 'Tyrion',
            'house' => 'Lannister'
        ];

        $testBasicInfoIsSet = A\arrayKeysExist($character, 'name', 'house');

        $this->assertEquals($testBasicInfoIsSet, true);
    }
}
