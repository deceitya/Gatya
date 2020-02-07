<?php
namespace Deceitya\Gatya\Series;

use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class SeriesFactory
{
    /** @var array[string|int=>Series] */
    private static $series = [];

    public static function init(string $file)
    {
        foreach (json_decode(file_get_contents($file), true) as $series) {
            $items = [];
            foreach ($series['items'] as $data) {
                $item = Item::get($data['id'], $data['meta'], $data['count']);
                if ($data['name'] !== null) {
                    $item->setCustomName($data['name']);
                }
                if ($data['description'] !== null) {
                    $item->setLore([$data['description']]);
                }
                foreach ($data['enchants'] as $enchant) {
                    $item->addEnchantment(
                        new EnchantmentInstance(
                            Enchantment::getEnchantment($enchant['id']),
                            $enchant['level']
                        )
                    );
                }

                $items[] = [$data['chance'], $item];
            }

            self::registerSeries(new Series($series['id'], $series['name'], $series['cost'], $items));
        }
    }

    public static function getSeries($key): Series
    {
        return self::$series[$key];
    }

    public static function registerSeries(Series $series)
    {
        self::$series[$series->getId()] = $series;
        self::$series[$series->getName()] = $series;
    }

    public static function getAllSeries(): \Generator
    {
        foreach (self::$series as $key => $series) {
            if (is_string($key)) {
                yield $series;
            }
        }
    }
}
