<?php

namespace Transporter\Enum;

enum NameAndAddressType: string
{
    case RECIPIENT = "CN";
    case SENDER = "CO";
    case PARTICIPANT = "N1";
    case PARTICIPANT2 = "N2";
    case DELIVERY_AGENT = "DP";
    case PROVIDER = "FW";
}