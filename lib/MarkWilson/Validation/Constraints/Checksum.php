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
     * Value providers - default is inline
     */
    const CHECKSUM_TYPE_INLINE = 'inline';
    const CHECKSUM_TYPE_ARRAY_KEY = 'array key';

    /**
     * Constraint types
     */
    const TYPE_MD5 = 'md5';

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
     * Checksum provider type - defaults to inline
     *
     * @var string
     */
    public $checksumType = self::CHECKSUM_TYPE_INLINE;

    /**
     * Data key if CHECKSUM_TYPE_ARRAY_KEY is used
     *
     * @var string
     */
    public $dataKey;

    /**
     * {@inheritDoc}
     */
    public function __construct($options = null)
    {
        if (isset($options['decoder'])) {
            $this->decoderCallable = $options['decoder'];

            unset($options['decoder']);
        }

        if (isset($options['checksum_type'])) {
            $this->checksumType = $options['checksum_type'];

            unset($options['checksum_type']);
        }

        if (isset($options['data_key'])) {
            $this->dataKey = $options['data_key'];

            unset($options['data_key']);
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

        if ($this->checksumType !== self::CHECKSUM_TYPE_INLINE && $this->checksumType !== self::CHECKSUM_TYPE_ARRAY_KEY) {
            throw new ConstraintDefinitionException('The option "checksum_type" is invalid in constraint ' . __CLASS__ . '.');
        }

        if ($this->checksumType === self::CHECKSUM_TYPE_ARRAY_KEY && (!isset($this->dataKey) || !is_string($this->dataKey))) {
            throw new ConstraintDefinitionException('The option "data_key" is invalid in constraint ' . __CLASS__ . '.');
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

        $types[0] = self::TYPE_MD5;

        return $types;
    }
}
