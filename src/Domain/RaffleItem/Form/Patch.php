<?php
namespace RaffleTools\Domain\RaffleItem\Form;

use Aura\Input\Form;

class Patch extends Form
{
    private $fields = [
        'name',
        'image',
    ];

    public function addField($field)
    {
        if (in_array($field, $this->fields)) {
            /** @var \RaffleTools\Domain\Input\Filter $filter */
            $filter = $this->getFilter();
            $this->setField($field);
            $filter->validate($field)->isNotBlank()->setMessage('Required');
            $filter->sanitize($field)->to('filterVar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }
}