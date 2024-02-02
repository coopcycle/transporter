<?php

namespace DBSchenker\Utils;

interface AddressGeocoder
{
    public function geocode(string $address): ?array;

}