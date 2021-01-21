<?php

namespace App\DBAL;

class EnumMovieRequestAction extends EnumType {
	public const ENUM_ACTIVITY_STATUS = 'enum_movie_request_action';
	public const ACTION_ADD = 'add';
	public const ACTION_REMOVE = 'remove';
	public const ACTION_EDIT = 'edit';
	public const ENUM_VALUES = [
		self::ACTION_ADD, self::ACTION_REMOVE, self::ACTION_EDIT
	];

	protected $name = self::ENUM_ACTIVITY_STATUS;
	protected $values = self::ENUM_VALUES;

	public static function isValid(string $enumValue): bool {
		return in_array($enumValue, self::ENUM_VALUES);
	}
}