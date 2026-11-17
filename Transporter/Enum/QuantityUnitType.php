<?php

namespace Transporter\Enum;

enum QuantityUnitType: string
{
    case KILOGRAM = "KG";
    case METER_CUBE = "MTQ";
    case LITER = "LTR";
    case CENTILITER = "CTL";
    // Unknown values
    case LM = "LM";

    public static function fromCode(string $code): self
    {
        return match (trim($code)) {
            'KGM' => self::KILOGRAM,
            default => self::from(trim($code)),
        };
    }
}
