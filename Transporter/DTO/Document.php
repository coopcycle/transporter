<?php

namespace Transporter\DTO;

use Transporter\Enum\DocumentType;

final class Document
{

    /**
     * @param DocumentType $type
     * @param string $code Raw DOC 1001 code, useful when $type is OTHER
     * @param string|null $number DOC 1004
     * @param string|null $name DOC 1000, e.g. the barcode content printed on the receipt
     * @param string|null $state DOC 1791 (ACG, PRI, INF)
     * @param string|null $origin DOC 1788, e.g. the GTF code of the issuing agency
     */
    public function __construct(
        private DocumentType $type,
        private string $code,
        private ?string $number = null,
        private ?string $name = null,
        private ?string $state = null,
        private ?string $origin = null
    )
    { }

    /**
     * @return DocumentType
     */
    public function getType(): DocumentType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return string|null
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

}
