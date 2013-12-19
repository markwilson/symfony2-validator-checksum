# Symfony2 Validation checksum constraint

Note: currently only works for validator component 2.2.x

Data checksum constraint for Symfony2 validator component.

## Install

Add `markwilson/symfony2-validator-checksum` to composer.json requires.

## Usage

`Checksum` requires a `type` option which, currently, must be set to 'md5', and a `checksum` option which is the
expected checksum value. It also has an optional parameter, `decoder`, to pass a decoder callable function.

e.g.

```` php
use MarkWilson\Validator\Constraints\Checksum;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

$constraint = new Checksum(
    array(
        'type' => 'md5',
        'decoder' => 'base64_decode',
        'checksum' => 'some md5 string'
    )
);

$validator = Validation::createValidator();
$validator->validateValue($value, $constraint);
````
