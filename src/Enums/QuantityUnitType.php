<?php

namespace DBShenker\Enums;

enum QuantityUnitType: string
{
    case KILOGRAM = "KG";
    case METER_CUBE = "MTQ";
    case LITER = "LTR";
    case CENTILITER = "CTL";
}