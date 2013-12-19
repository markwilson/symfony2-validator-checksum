<?php

namespace MarkWilson\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * Checksum constraint
 *
 * @package MarkWilson\Validation\Constraints
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class Checksum extends Constraint
{
    /**
     * Constraint message
     *
     * @var string
     */
    public $message = 'Checksum value is invalid';

    /**
     * Checksum type
     *
     * @var string
     */
    public $type;

    /**
     * Expected checksum value
     *
     * @var string
     */
    public $checksum;

    /**
     * Decoder callable
     *
     * @var Callable
     */
    public $decoderCallable;

    /**
     * {@inheritDoc}
     */
    public function __construct($options = null)
    {
        if (isset($options['decoder'])) {
            $this->decoderCallable = $options['decoder'];

            unset($options['decoder']);
        }

        parent::__construct($options);

        $validType = false;
        $availableTypes = static::getAvailableTypes();
        foreach ($availableTypes as $availableType) {
            if ($availableType === $this->type) {
                $validType = true;
            }
        }

        if (!$validType) {
            throw new ConstraintDefinitionException('The option "type" must be one of (' . implode(', ', $availableTypes) . ') in constraint ' . __CLASS__ . '.');
        }

        if (!is_string($this->checksum)) {
            throw new ConstraintDefinitionException('The option "checksum" must be a valid string in constraint ' . __CLASS__ . '.');
        }

        if (null !== $this->decoderCallable && !is_callable($this->decoderCallable)) {
            throw new ConstraintDefinitionException('The option "decoder" must be a valid callable in constraint ' . __CLASS__ . '.');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredOptions()
    {
        return array('type', 'checksum');
    }

    /**
     * Get the available types
     *
     * @return \SplFixedArray
     */
    public static function getAvailableTypes()
    {
        $types = new \SplFixedArray(1);

        $types[0] = 'md5';

        return $types;
    }
}
