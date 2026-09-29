<?php

namespace Transporter\Enum;

enum DocumentType: string
{
    case WAYBILL = "WBL";
    case CARRIER_REFERENCE = "824";
    case SHIPPER_REFERENCE = "150";
    case CONSIGNEE_ORDER = "AAO";
    case PREVIOUS_REFERENCE = "227";
    // Customs documents and any other code (see INOVERT directory)
    case OTHER = "OTHER";

    public static function fromCode(string $code): self
    {
        return self::tryFrom($code) ?? self::OTHER;
    }
}
