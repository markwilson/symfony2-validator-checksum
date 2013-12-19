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
        $decoder = $constraint->decoderCallable;

        if (null !== $decoder) {
            $value = $decoder($value);
        }

        switch ($constraint->type) {
            case 'md5':
                $checksum = md5($value);
                break;
            default:
                throw new \RuntimeException('Invalid checksum type provided');
        }

        if ($constraint->checksum !== $checksum) {
            $this->context->addViolation($constraint->message);
        }
    }
}
