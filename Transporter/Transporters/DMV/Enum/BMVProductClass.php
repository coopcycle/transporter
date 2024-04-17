<?php

namespace Transporter\Transporters\DMV\Enum;

enum BMVProductClass {
    case AFG_PALETTS;
    case LIVING_CREATURE;
    case ATK_MESSAGE_PLUS;
    case SHOPPING;
    case CX_EXPRESS;
    case NET_EXPRESS_EUROPE;
    case BMV_EXPRESS;
    case FRANCE_EXPRESS;
    case INTERPACK;
    case INTERNATIONAL_GROUPING;
    case MESSAGE_LIGHT;
    case MESSAGE;
    case NTX_EXPRESS;
    case PACK_30;
    case PLI;
    case TOP_24;
    case TP9;
    case X24;
    case XPACK;
    case UNKNOWN;

    /**
     * @param int $TSR_4219
     * @param string|null $TSR_4751
     * @return self
     */
    public static function from(int $TSR_4219, ?string $TSR_4751): self
    {
        $TSR_4751 = empty($TSR_4751) ? null : $TSR_4751;
        return match ($TSR_4751) {
            'AFG' => self::AFG_PALETTS,
            'ANI' => self::LIVING_CREATURE,
            'ATK' => self::ATK_MESSAGE_PLUS,
            'COU' => self::SHOPPING,
            'CXI' => self::CX_EXPRESS,
            'EEX' => self::NET_EXPRESS_EUROPE,
            'EXP' => self::BMV_EXPRESS,
            'INE' => self::FRANCE_EXPRESS,
            'INP' => self::INTERPACK,
            'MEI' => self::INTERNATIONAL_GROUPING,
            'MEL' => self::MESSAGE_LIGHT,
            'MES' => self::MESSAGE,
            'NTX' => self::NTX_EXPRESS,
            'P30' => self::PACK_30,
            'PLI' => self::PLI,
            'T24' => self::TOP_24,
            'TP9' => self::TP9,
            'X24' => self::X24,
            'XPK' => self::XPACK,
            default => self::UNKNOWN
        };
    }
}
