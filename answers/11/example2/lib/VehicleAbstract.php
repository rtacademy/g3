<?php

namespace lib;

abstract class VehicleAbstract implements VehicleEngineVolumeInterface
{
    abstract public function getEngineVolume() : int;
}