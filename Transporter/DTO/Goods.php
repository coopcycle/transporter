<?php

namespace Transporter\DTO;

final class Goods
{

    /**
     * @param string|null $description GDS 7002
     * @param DangerousGoods|null $dangerousGoods
     * @param Mesurement[] $mesurements Total gross (AAD, LQ, EQ) and net (AAC) quantities
     */
    public function __construct(
        private ?string $description = null,
        private ?DangerousGoods $dangerousGoods = null,
        private array $mesurements = []
    )
    { }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return DangerousGoods|null
     */
    public function getDangerousGoods(): ?DangerousGoods
    {
        return $this->dangerousGoods;
    }

    /**
     * @return Mesurement[]
     */
    public function getMesurements(): array
    {
        return $this->mesurements;
    }

}
