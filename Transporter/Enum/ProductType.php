<?php

namespace Transporter\Enum;
enum ProductType: int
{
    case PACKAGE = 21;
    case HANDLING_UNIT = 23;
    case CONSIGNED_EQUIPMENT = 99;
    case UNKNOWN_QUANTITY = 98;
}
