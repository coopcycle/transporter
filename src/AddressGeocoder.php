<?php

namespace DBShenker;

interface AddressGeocoder
{
    public function geocode(string $address): ?array;
}