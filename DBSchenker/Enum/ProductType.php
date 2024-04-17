<?php

namespace DBSchenker\Enum;
enum ProductType: int
{
    // 21
    case PACKAGE = 21;

    // 23
    case HANDLING_UNIT = 23;

    // 99
    case CONSIGNED_EQUIPMENT = 99;

    // 98
    case UNKNOWN_QUANTITY = 98;
}
