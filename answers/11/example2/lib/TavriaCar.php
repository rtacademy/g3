<?php

namespace lib;

class TavriaCar extends Car
{
    public function getEngineVolume(): int
    {
        return 1200;
    }

    public static function getName() : string
    {
        return 'Таврична сила';
    }
}