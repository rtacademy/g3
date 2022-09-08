<?php

namespace lib;

abstract class Car extends VehicleAbstract
{
    abstract public function getEngineVolume(): int;
}