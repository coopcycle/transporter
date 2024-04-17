<?php

namespace Transporter\Utils;

class GoogleMapsAddressGeocoder implements AddressGeocoder
{

    public function __construct(
      private readonly string $apiKey
    )
    { }

    /**
     * @param string $address
     * @return array|null
     */
    public function geocode(string $address): ?array
    {
        $address = urlencode($address);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$this->apiKey}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        $data = json_decode($response, true);

        //TODO: add better error handling
        if ($data['status'] === 'OK') {
            $latitude = $data['results'][0]['geometry']['location']['lat'];
            $longitude = $data['results'][0]['geometry']['location']['lng'];
            return ['latitude' => $latitude, 'longitude' => $longitude];
        } else {
            return null;
        }
    }
}