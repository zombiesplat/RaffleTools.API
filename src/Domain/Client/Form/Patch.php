<?php
namespace RaffleTools\Domain\Client\Form;

use Aura\Input\Form;

class Patch extends Form
{
    protected $fields = [
        'name',
        'ein',
        'email',
        'phone',
        'contactName',
        'address1',
        'address2',
        'city',
        'state',
        'postalCode',
        'country',
    ];

    public function addField($field)
    {
        if (in_array($field, $this->fields)) {
            /** @var \RaffleTools\Domain\Input\Filter $filter */
            $filter = $this->getFilter();
            $this->setField($field);
            $filter->validate($field)->isNotBlank()->setMessage('Required');
            $filter->sanitize($field)->to('filterVar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($field == 'email') {
                $filter->validate('email')->is('email')->setMessage('Invalid email format');
            }
        }
    }
}