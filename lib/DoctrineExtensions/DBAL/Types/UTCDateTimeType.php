<?php

namespace DoctrineExtensions\DBAL\Types;

use
	Doctrine\DBAL\Platforms\AbtractPlatform,
	Doctrine\DBAL\Types\ConversionException,
	Doctrine\DBAL\Platforms\AbstractPlatform,
	Doctrine\DBAL\Types\DateTimeType
;

class UTCDateTimeType extends DateTimeType
{
	static private $utc = null;
	
	public function getName()
	{
		return Type::UTCDATETIME;
	}
	
	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if ($value === null) {
			return null;
		}
		
		return $value
			->setTimezone((self::$utc) ? self::$utc : (self::$utc = new \DateTimeZone('UTC')))
			->format($platform->getDateTimeFormatString())
		;
	}
	
	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		if ($value === null) {
			return null;
		}
		
		$val = \DateTime::createFromFormat(
			$platform->getDateTimeFormatString(),
			$value,
			(self::$utc) ? self::$utc : (self::$utc = new \DateTimeZone('UTC'))
		);
		if (!$val) {
			throw ConversionException::conversionFailed($value, $this->getName());
		}
		
		return $val;
	}
}