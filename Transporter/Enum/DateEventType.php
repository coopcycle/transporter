<?php

namespace Transporter\Enum;

enum DateEventType: string
{
    case DEPARTURE_DATE = "DEP";
    case ESTIMATED_ARRIVAL_DATE = "AAR";
    case REQUESTED_DELIVERY_DATE = "DAD";
    case START_OF_DELIVERY_PERIOD = "DFD";
    case END_OF_DELIVERY_PERIOD = "DLD";
    case SHIPMENT_DATE = "DES";
}