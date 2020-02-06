<?php
namespace Deceitya\Gatya\Utils;

use pocketmine\utils\TextFormat;
use Deceitya\Gatya\Main;

class MessageContainer
{
    private function __construct()
    {
    }

    /** @var array[string=>string] */
    private static $messages = [];

    public static function load(Main $plugin)
    {
        $stream = $plugin->getResource('message.json');
        self::$messages = json_decode(stream_get_contents($stream), true);

        fclose($stream);
    }

    public static function get(string $key, string ...$params): string
    {
        $keys = explode('.', $key);
        $msg = self::$messages;
        foreach ($keys as $k) {
            if (isset($msg[$k])) {
                $msg = $msg[$k];
            } else {
                $msg = $key;
                break;
            }
        }

        $i = 0;
        $search = [];
        $replace = [];
        foreach ($params as $param) {
            $search[] = '%' . ++$i;
            $replace[] = $param;
        }

        return TextFormat::colorize(str_replace($search, $replace, $msg));
    }
}
