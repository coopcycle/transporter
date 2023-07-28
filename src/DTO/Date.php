<?php

namespace DBShenker\DTO;

use DateTime;
use DBShenker\Enums\DateEventType;

final class Date
{
    private DateEventType $event;

    private DateTime $date;

    /**
     * @param DateEventType $event
     * @param DateTime $date
     */
    public function __construct(DateEventType $event, DateTime $date)
    {
        $this->event = $event;
        $this->date = $date;
    }

    /**
     * @return DateEventType
     */
    public function getEvent(): DateEventType
    {
        return $this->event;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

}