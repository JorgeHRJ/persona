<?php

namespace App\Library\Maker\Utils;

use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;

class Validator
{
    public static function notBlank(string $value = null): string
    {
        if (null === $value || '' === $value) {
            throw new RuntimeCommandException('This value cannot be blank.');
        }

        return $value;
    }
}
