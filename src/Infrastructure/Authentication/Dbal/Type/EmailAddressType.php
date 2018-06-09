<?php

namespace Infrastructure\Authentication\Dbal\Type;

use Authentication\Value\EmailAddress;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class EmailAddressType extends StringType
{
    private const NAME = EmailAddress::class;

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        /** @var EmailAddress $value */

        return $value->toString();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return EmailAddress::fromString($value);
    }

    public function getName()
    {
        return self::NAME;
    }
}
