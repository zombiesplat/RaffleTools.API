<?php
namespace RaffleTools\Domain\Auth\Form;

use Aura\Input\Form;
use RaffleTools\Entity\User;

class Register extends Form
{

    public function init()
    {
        /** @var \RaffleTools\Domain\Input\Filter $filter */
        $filter = $this->getFilter();
        $fields = [
            'email',
            'password',
            'passwordCheck',
        ];
        foreach ($fields as $field) {
            $this->setField($field);
            $filter->validate($field)->isNotBlank()->setMessage('Required');
        }
        $filter->validate('email')->is('email');
        $filter->validate('email')->is('checkUnique', User::class)->setMessage('email is currently registered');
        $filter->validate('password')->is('strlenMin', 8);
        $filter->validate('password')->is('regex', '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/')->setMessage('Password must contain 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character');
        $filter->validate('password')->is('equalToField', 'passwordCheck');
    }
}