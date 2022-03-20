<?php

declare(strict_types=1);

namespace Omasn\ObjectHandler\HandleTypes;

use Omasn\ObjectHandler\Exception\InvalidHandleValueException;
use Omasn\ObjectHandler\HandleContextInterface;
use Omasn\ObjectHandler\HandleProperty;
use Omasn\ObjectHandler\HandleType;

final class HandleEnumType extends HandleType
{
    public function getId(): string
    {
        if (PHP_VERSION_ID < 81000) {
            throw new \RuntimeException('Support only PHP >= 8.1.0');
        }

        return \BackedEnum::class;
    }

    /**
     * @return \BackedEnum|object
     *
     * @throws InvalidHandleValueException
     */
    public function resolveValue(HandleProperty $handleProperty, HandleContextInterface $context)
    {
        if (PHP_VERSION_ID < 81000) {
            throw new \RuntimeException('Support only PHP >= 8.1.0');
        }

        /** @var \BackedEnum $class */
        $class = $handleProperty->getType()->getClassName();
        $value = $handleProperty->getInitialValue();

        if ($value instanceof \BackedEnum::class) {
            return $value;
        }

        if (!is_scalar($value)) {
            throw new InvalidHandleValueException($handleProperty,
                sprintf('Expected of type "scalar", "%s" given', get_class($value))
            );
        }

        return $class::from($value);
    }

    public function supports(HandleProperty $handleProperty): bool
    {
        if (PHP_VERSION_ID < 81000) {
            throw new \RuntimeException('Support only PHP >= 8.1.0');
        }

        $class = $handleProperty->getType()->getClassName();

        if (class_exists($class)) {
            return is_subclass_of($class, \BackedEnum::class);
        }

        return false;
    }
}
