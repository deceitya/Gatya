<?php
namespace Deceitya\Gatya\Form;

use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\Server;
use Deceitya\Gatya\Series\SeriesFactory;
use Deceitya\Gatya\Utils\MessageContainer;
use onebone\economyapi\EconomyAPI;

class InputForm implements Form
{
    private $series;

    public function __construct(string $name)
    {
        $this->series = SeriesFactory::getSeries($name);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }

        Server::getInstance()->dispatchCommand($player, "gt {$this->series->getId()} {$data[2]}");
    }

    public function jsonSerialize()
    {
        return [
            'type' => 'custom_form',
            'title' => MessageContainer::get('form.input.title'),
            'content' => [
                [
                    'type' => 'label',
                    'text' => MessageContainer::get(
                        'form.input.label1',
                        $this->series->getName(),
                        $this->series->getId()
                    )
                ],
                [
                    'type' => 'label',
                    'text' => MessageContainer::get(
                        'form.input.label2',
                        EconomyAPI::getInstance()->getMonetaryUnit(),
                        $this->series->getCost()
                    )
                ],
                [
                    'type' => 'input',
                    'text' => MessageContainer::get('form.input.input.text'),
                    'placeholder' => MessageContainer::get('form.input.input.placeholder'),
                    'default' => MessageContainer::get('form.input.input.default')
                ]
            ]
        ];
    }
}
