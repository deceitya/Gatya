<?php
namespace Deceitya\Gatya\Form;

use pocketmine\form\Form;
use pocketmine\Player;
use Deceitya\Gatya\Utils\MessageContainer;

class SeriesForm implements Form
{
    public function handleResponse(Player $player, $data): void
    {
    }

    public function jsonSerialize()
    {
        $form = [
            'type' => 'form',
            'title' => MessageContainer::get('form.series.title'),
            'content' => MessageContainer::get('form.series.description'),
            'buttons' => []
        ];

        return $form;
    }
}
