<?php

namespace App\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

abstract class EnumType extends Type {
	protected $name;
	protected $values = [];

	public function getSQLDeclaration(array $column, AbstractPlatform $platform): string {
		$values = array_map(fn($val) => "'".$val."'", $this->values);

		return "ENUM(".implode(", ", $values).")";
	}

	public function convertToPHPValue($value, AbstractPlatform $platform) {
		return $value;
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		if (!in_array($value, $this->values)) {
			throw new InvalidArgumentException("Invalid '".$this->name."' value.");
		}

		return $value;
	}

	public function getName(): string {
		return $this->name;
	}

	public function requiresSQLCommentHint(AbstractPlatform $platform): bool {
		return true;
	}

	abstract public static function isValid(string $enumValue): bool;
}