<?php

namespace Transporter\DTO;

final class DangerousGoods
{

    /**
     * @param string $regulation DGS 8753, e.g. ADR
     * @param string|null $class DGS 8755
     * @param string|null $unNumber DGS 7124
     * @param string|null $packingGroup DGS 8724 (1 = I, 2 = II, 3 = III)
     * @param string[] $labels DGS 8712, main label first
     * @param string|null $officialName TXT+AAD
     * @param string|null $technicalName TXT+AAC
     * @param string|null $tunnelCode TXT+REG
     */
    public function __construct(
        private string $regulation,
        private ?string $class = null,
        private ?string $unNumber = null,
        private ?string $packingGroup = null,
        private array $labels = [],
        private ?string $officialName = null,
        private ?string $technicalName = null,
        private ?string $tunnelCode = null
    )
    { }

    /**
     * @return string
     */
    public function getRegulation(): string
    {
        return $this->regulation;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @return string|null
     */
    public function getUnNumber(): ?string
    {
        return $this->unNumber;
    }

    /**
     * @return string|null
     */
    public function getPackingGroup(): ?string
    {
        return $this->packingGroup;
    }

    /**
     * @return string[]
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @return string|null
     */
    public function getOfficialName(): ?string
    {
        return $this->officialName;
    }

    /**
     * @return string|null
     */
    public function getTechnicalName(): ?string
    {
        return $this->technicalName;
    }

    /**
     * @return string|null
     */
    public function getTunnelCode(): ?string
    {
        return $this->tunnelCode;
    }

}
