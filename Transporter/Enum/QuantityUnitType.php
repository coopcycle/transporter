<?php

namespace Transporter\Enum;

enum QuantityUnitType: string
{
    case KILOGRAM = "KG";
    case METER_CUBE = "MTQ";
    case LITER = "LTR";
    case CENTILITER = "CTL";
}