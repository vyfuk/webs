<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use DateTime;

class SeriesModel
{
    public ?string $deadline;
    public int $year;
    public int $series;
    /**
     * @var int[]
     */
    public array $problems;

    public function getDeadline()
    {
        if ($this->deadline) {
            return new DateTime($this->deadline);
        }
        return null;
    }
}