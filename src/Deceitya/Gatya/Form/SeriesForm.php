<?php
namespace Deceitya\Gatya\Form;

use pocketmine\form\Form;
use pocketmine\Player;
use Deceitya\Gatya\Series\SeriesFactory;
use Deceitya\Gatya\Utils\MessageContainer;

class SeriesForm implements Form
{
    /** @var string[] */
    private $names = [];

    public function __construct()
    {
        foreach (SeriesFactory::getAllSeries() as $series) {
            $this->names[] = $series->getName();
        }
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }

        $player->sendForm(new InputForm($this->names[$data]));
    }

    public function jsonSerialize()
    {
        $form = [
            'type' => 'form',
            'title' => MessageContainer::get('form.series.title'),
            'content' => MessageContainer::get('form.series.description'),
            'buttons' => []
        ];
        foreach ($this->names as $name) {
            $form['buttons'][] = ['text' => $name];
        }

        return $form;
    }
}
