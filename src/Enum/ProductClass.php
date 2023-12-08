<?php

namespace DBShenker\Enum;

use DBShenker\DBShenkerException;

enum ProductClass {
    case SYSTEM_FRANCE;
    case PALLET_FRANCE;
    case WECARE_FRANCE;
    case PREMIUM_13_FRANCE;
    case PREMIUM_FRANCE;
    case PALLET_PREMIUM_FRANCE;
    case PHARMA_PLUS_SYSTEM_FRANCE;
    case PHARMA_PLUS_PREMIUM_13_FRANCE;
    case PHARMA_PLUS_PREMIUM_FRANCE;
    case PHARMA_PLUS_PALLET_FRANCE;
    case EXPRESS_FRANCE;

    public static function from(int $TSR_4219, ?string $TSR_4751): self
    {
        $TSR_4751 = empty($TSR_4751) ? null : $TSR_4751;
        return match ([$TSR_4219, $TSR_4751]) {
            [3, null]  => self::SYSTEM_FRANCE,
            [3, 'G51'] => self::PALLET_FRANCE,
            [1, null]  => self::WECARE_FRANCE,
            [1, 'G13'] => self::PREMIUM_13_FRANCE,
            [3, 'G18'] => self::PREMIUM_FRANCE,
            [3, 'G18'] => self::PALLET_PREMIUM_FRANCE,
            [3, 'P01'] => self::PHARMA_PLUS_SYSTEM_FRANCE,
            [1, 'P13'] => self::PHARMA_PLUS_PREMIUM_13_FRANCE,
            [3, 'P18'] => self::PHARMA_PLUS_PREMIUM_FRANCE,
            [3, 'P51'] => self::PHARMA_PLUS_PALLET_FRANCE,
            [1, 'G12'] => self::EXPRESS_FRANCE,
            default    => throw new DBShenkerException('Not valid product class')
        };
    }
}
