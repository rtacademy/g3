<?php

interface A
{
    public const CONST_A = 100;
    public function getConstA(): int;
}

abstract class B implements A
{
    public const CONST_A = 300;
    abstract public function getConstA(): int;
}

class C extends B
{
    public const CONST_A = 500;
    public function getConstA(): int
    {
        var_dump( A::CONST_A );
        var_dump( B::CONST_A );
        var_dump( parent::CONST_A );
        var_dump( static::CONST_A );
        var_dump( self::CONST_A );

        return
            A::CONST_A + B::CONST_A + parent::CONST_A +
            static::CONST_A + self::CONST_A;
    }
}

$c = new C();
var_dump( '$c: ' . $c->getConstA() );

/*
int(100)
int(300)
int(300)
int(500)
int(500)
string(8) "$c: 1700"
*/

class D extends C
{
    public const CONST_A = 1000;
}

$d = new D();
var_dump( '$d: ' .$d->getConstA() );

/*
int(100)
int(300)
int(300)
int(1000)
int(500)
string(8) "$d: 2200"
*/