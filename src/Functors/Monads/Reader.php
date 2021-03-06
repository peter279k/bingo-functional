<?php

/**
 * Reader monad
 * 
 * @package bingo-functional
 * @author Lochemem Bruno Michael
 * @license Apache 2.0
 */

namespace Chemem\Bingo\Functional\Functors\Monads;

use Chemem\Bingo\Functional\Algorithms as A;

class Reader
{
    /**
     * @access private
     * @var callable $action The operation to use to lazily evaluate an environment variable
     */
    private $action;

    /**
     * Reader constructor
     * 
     * @param callable $action
     */
    public function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * of method
     * 
     * @static of
     * @param mixed $action
     * @return object Reader
     */

    public static function of($action) : Reader
    {
        return is_callable($action) ? 
            new static($action) :
            new static(
                function ($env) use ($action) {
                    return $action;
                }
            );
    }

    /**
     * withReader method
     * 
     * @param callable $action
     * @return object Reader
     */

    public function withReader(callable $action) : Reader
    {
        return new static(
            function ($env) use ($action) {
                $reader = call_user_func($action, $this->run($env));
                return $reader->run($env);
            }
        );
    }

    /**
     * map method
     * 
     * @param callable $action
     * @return object Reader
     */

    public function map(callable $function) : Reader
    {
        return $this->withReader($function);
    }

    /**
     * ask method
     * 
     * @return mixed $action
     */

    public function ask()
    {
        return $this->action;
    }

    /**
     * run method
     * 
     * @param mixed $env Environment variable
     * @return mixed $action 
     */

    public function run($env)
    {
        return call_user_func($this->action, $env);   
    }
}
