<?php

namespace DBShenker\Enums;

enum QuantityType: string
{
    case CONSIGNMENT_WEIGHT = "CGW";
    case CONSIGNMENT_VOLUME = "CGM";
    case ALCOHOL_VOLUME = "AAW";
    case PURE_ALCOHOL_VOLUME = "ABQ";
    case WEIGHT_TOTAL = "AAD";
    case NET_WEIGHT = "AAC";
    case QUANTITY_EXCEPTION = "EQ";
    case QUANTITY_LIMIT = "LQ";
}