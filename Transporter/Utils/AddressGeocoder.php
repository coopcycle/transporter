<?php

namespace Transporter\Utils;

interface AddressGeocoder
{
    public function geocode(string $address): ?array;

}