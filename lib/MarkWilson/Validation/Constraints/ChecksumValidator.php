<?php

namespace MarkWilson\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Checksum validation class
 *
 * @package MarkWilson\Validation\Constraints
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class ChecksumValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     *
     * @throws \RuntimeException If invalid checksum type is supplied
     */
    public function validate($value, Constraint $constraint)
    {
        switch ($constraint->checksumType) {
            case Checksum::CHECKSUM_TYPE_INLINE:
                $providedChecksum = $constraint->checksum;
                break;
            case Checksum::CHECKSUM_TYPE_ARRAY_KEY:
                $providedChecksum = $value[$constraint->checksum];
                $value = $value[$constraint->dataKey];
                break;
            default:
                throw new \RuntimeException('Invalid checksum type provided');
        }

        $decoder = $constraint->decoderCallable;

        if (null !== $decoder) {
            $value = $decoder($value);
        }

        switch ($constraint->type) {
            case Checksum::TYPE_MD5:
                $checksum = md5($value);
                break;
            default:
                throw new \RuntimeException('Invalid constraint type provided');
        }

        if ($providedChecksum !== $checksum) {
            $this->context->addViolation($constraint->message);
        }
    }
}
