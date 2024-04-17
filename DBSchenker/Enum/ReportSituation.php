<?php

namespace DBSchenker\Enum;

enum ReportSituation: string {
    case AAR = 'Arrival';
    case CHG = 'Loading';
    case COM = 'Communication';
    case DCH = 'Unloading';
    case DIF = 'Delayed at departure';
    case ECH = 'Pickup taken in charge';
    case EDI = 'Delayed pickup';
    case EML = 'Pickup initiated';
    case ENE = 'Unrealized pickup';
    case EPC = 'Pickup request';
    case EXP = 'Shipped';
    case LIV = 'Delivered';
    case MAJ = 'Modification';
    case MLV = 'Delivery initiation';
    case PAQ = 'Quay taken';
    case PCH = 'In charge';
    case POD = 'Electronic proof of delivery';
    case POP = 'Electronic proof of pickup';
    case QIN = 'Information request';
    case RAQ = 'Remaining at quay';
    case REN = 'Returned (not delivered)';
    case SEQ = 'Equipment consigned';
    case SOL = 'Settled';
}
