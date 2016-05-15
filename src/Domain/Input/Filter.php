<?php
namespace RaffleTools\Domain\Input;

use Aura\Filter\Exception;
use Aura\Filter\Failure\FailureCollection;
use Aura\Filter\Spec\SanitizeSpec;
use Aura\Filter\Spec\ValidateSpec;
use Aura\Filter\SubjectFilter;
use Aura\Input\Fieldset;
use Aura\Input\FilterInterface;


class Filter extends SubjectFilter implements FilterInterface
{

    public function __construct(ValidateSpec $validate_spec, SanitizeSpec $sanitize_spec, FailureCollection $failures)
    {
        parent::__construct($validate_spec, $sanitize_spec, $failures);
    }

    /**
     * called at the end of __construct
     */
    public function init()
    {
    }

    /**
     * Filter (sanitize and validate) the data.
     *
     * @param Fieldset $fieldset
     * @return bool True if all rules passed; false if one or more failed.
     */
    public function values(&$fieldset)
    {
        /** @var Fieldset $fieldset */
        $values = $fieldset->getValue();
        $success = $this->apply($values);
        if ($success) {
            $fieldset->fill($values);
        }
        return $success;
    }

    /**
     *
     * Gets the messages for all fields, or for a single field.
     *
     * @param string $field If empty, return all messages for all fields;
     * otherwise, return only messages for the named field.
     *
     * @return mixed
     *
     */
    public function getMessages($field = null)
    {
        if (!$field) {
            return $this->getFailures()->getMessages();
        }

        return $this->getFailures()->forField($field); //temporary until I figure out how the getfailures stuff works.
    }


    /**
     * Manually add messages to a particular field.
     *
     * @param string $field Add to this field.
     * @param string|array $messages Add these messages to the field.
     * @return void
     */
    public function addMessages($field, $messages)
    {
        // TODO: Implement addMessages() method.
    }
}
