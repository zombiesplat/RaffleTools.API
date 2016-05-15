<?php
namespace RaffleTools\Domain\Input\Sanitize;

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
        $subject->$field = filter_var($subject->$field, $filter);
        return true;
    }

}
