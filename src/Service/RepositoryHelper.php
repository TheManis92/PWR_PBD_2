<?php


namespace App\Service;


use Cassandra\SSLOptions\Builder;

class RepositoryHelper {

	/**
	 * Apply pagination limits, fields to select, sorting rules
	 *
	 * Note: $sort should be in following shape
	 * $sort = ["fieldName1" => "asc|desc", "fieldName2" => "asc|desc"]
	 * In the example above results will be sorted first by fieldName1
	 * then by fieldName2
	 *
	 * @param Builder $qb
	 * @param int $from
	 * @param int $to
	 * @param array|null $fields
	 * @param array|null $sort
	 */
	public static function applyLimits(Builder $qb, int $from=0, int $to=0,
									   ?array $fields=null, ?array $sort=null): void {
		if ($from > 0) $qb->skip($from);
		if ($to > 0) $qb->limit($to);
		if (!empty($fields)) $qb->select($fields);
		if (!empty($sort)) {
			foreach ($sort as $field => $order) {
				$qb->sort($field, $order);
			}
		}
	}
}