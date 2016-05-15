<?php
namespace RaffleTools\Domain\RaffleItem\Form;

use Aura\Input\Form;

class Post extends Form
{

    public function init()
    {
        /** @var \RaffleTools\Domain\Input\Filter $filter */
        $filter = $this->getFilter();
        $fields = [
            'name',
            'image',
        ];
        foreach ($fields as $field) {
            $this->setField($field);
            $filter->validate($field)->isNotBlank()->setMessage('Required');
            $filter->sanitize($field)->to('filterVar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }
}