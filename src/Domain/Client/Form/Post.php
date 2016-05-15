<?php
namespace RaffleTools\Domain\Client\Form;

use Aura\Input\Form;

class Post extends Form
{

    public function init()
    {
        /** @var \RaffleTools\Domain\Input\Filter $filter */
        $filter = $this->getFilter();
        $fields = [
            'name',
            'ein',
            'email',
            'phone',
            'contactName',
            'address1',
            'address2', //TODO: not required
            'city',
            'state',
            'postalCode',
            'country',
        ];
        foreach ($fields as $field) {
            $this->setField($field);
            $filter->validate($field)->isNotBlank()->setMessage('Required');
            $filter->sanitize($field)->to('filterVar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        $filter->validate('email')->is('email')->setMessage('Invalid email format');
    }
}