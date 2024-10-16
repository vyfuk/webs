<?php

declare(strict_types=1);

namespace App\Models\Downloader;

class ProblemModel
{
    public string $contest;
    public int $year;
    public int $series;
    public int $number;
    /**
     * @var string[]
     */
    public array $name;
    /**
     * @var string[]
     */
    public ?array $origin = [];
    public ?int $points; // null for backwards compatibility
    /**
     * @var string[]
     */
    public array $topics;
    /**
     * @var array[][]
     */
    public array $authors;
    /**
     * @var int[]
     */
    public ?array $studyYears;
    /**
     * @var string[]
     */
    public array $task;
    /**
     * @var string[]
     */
    public array $solution;
    public ?float $machineResult;
    /**
     * @var string[]
     */
    public array $humanResult;

    public function getLabel(): string
    {
        if ($this->contest === 'fykos') {
            switch ($this->number) {
                case 6:
                    return 'P';
                case 7:
                    return 'E';
                case 8:
                    return 'S';
            }
        } elseif ($this->contest === 'vyfuk') {
            switch ($this->number) {
                case 6:
                    return 'E';
                case 7:
                    return 'V';
            }
        }

        return (string)$this->number;
    }
}
