<?php
namespace Deceitya\Gatya\Command;

use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Deceitya\Gatya\Main;
use Deceitya\Gatya\Form\SeriesForm;
use Deceitya\Gatya\Series\SeriesFactory;
use Deceitya\Gatya\Utils\MessageContainer;
use onebone\economyapi\EconomyAPI;

class GatyaCommand extends PluginCommand
{
    public function __construct(Main $plugin)
    {
        parent::__construct('gt', $plugin);

        $this->setPermission('gatya.command.gt');
        $this->setDescription(MessageContainer::get('command.gt.description'));
        $this->setUsage(MessageContainer::get('command.gt.usage'));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!parent::execute($sender, $commandLabel, $args)) {
            return false;
        }

        if (!($sender instanceof Player)) {
            return true;
        }

        if (count($args) < 1) {
            $sender->sendForm(new SeriesForm());
        }

        try {
            $series = SeriesFactory::getSeries(array_shift($args));
            $count = array_shift($args) ?? 1;
            $api = EconomyAPI::getInstance();

            for ($i = 0; $i < $count; $i++) {
                if ($api->myMoney($sender) < $series->getCost()) {
                    $sender->sendMessage(MessageContainer::get('command.gatya.no_money'));

                    return true;
                }

                $item = $series->getItem(mt_rand(0, 10000) / 100);
                if (empty($sender->getInventory()->addItem($item))) {
                    $sender->sendMessage(MessageContainer::get('command.gatya.result', $item->getCustomName() ?: $item->getName()));
                    $api->reduceMoney($sender, $series->getCost());
                } else {
                    $sender->sendMessage(MessageContainer::get('command.gatya.no_space'));

                    return true;
                }
            }

            return true;
        } catch (\Exception $e) {
            $sender->sendMessage(MessageContainer::get('command.gatya.no_series'));

            return true;
        }
    }
}
