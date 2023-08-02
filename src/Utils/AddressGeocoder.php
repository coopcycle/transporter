<?php

namespace DBShenker\Utils;

interface AddressGeocoder
{
    public function geocode(string $address): ?array;
}