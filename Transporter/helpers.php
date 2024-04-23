<?php
if (!function_exists('normalize_depth')) {
    function normalize_depth(array $array): array
    {
        $isList = array_reduce(array_keys($array), function($carry, $item) {
            if ($carry) {
                return is_numeric($item);
            }
            return $carry;
        }, true);

        if (!$isList) {
            return [$array];
        }
        return $array;
    }
}