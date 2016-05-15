<?php
namespace RaffleTools\Domain\Input\Validate;

use Aura\Filter\Rule;

class FilterVar
{
    /**
     *
     * @param object $subject The subject to be filtered.
     * @param string $field The subject field name.
     * @param int $filter
     * @return bool True if the value was sanitized, false if not.
     */
    public function __invoke($subject, $field, $filter)
    {
        return filter_var($subject->$field, $filter);
    }

}
