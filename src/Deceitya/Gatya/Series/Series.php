<?php
namespace Deceitya\Gatya\Series;

use pocketmine\item\Item;

class Series
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var int */
    private $cost;
    /** @var array[float,Item] */
    private $items;

    /**
     * @param integer $id
     * @param string $name
     * @param int $cost
     * @param array[float,Item] $items
     */
    public function __construct(int $id, string $name, int $cost, array $items)
    {
        $this->id = $id;
        $this->name = $name;
        $this->cost = $cost;
        $this->items = $items;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getChanceSum(): float
    {
        $sum = 0.0;
        foreach ($this->items as $data) {
            $sum += $data[0];
        }
        return $sum;
    }

    public function getItem(float $percent): Item
    {
        $prev = 0.0;
        foreach ($this->items as $item) {
            if ($prev < $percent && $percent <= $prev + $item[0]) {
                return $item[1];
            }

            $prev += $item[0];
        }
    }
}
