<?php
/**
 * Base interface for working with query
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\DataSource\Query;

/**
 * Interface QueryInterface
 * @package Framework\DataSource\Query
 */
interface QueryInterface
{
    public function where($field, $value = null);
    public function groupBy($groups);
    public function orderBy($group);
    public function select($fields);
    public function delete($fields);
    public function update($fields);
    public function insert(array $fields);
    public function either();
    public function collection($collections);
    public function limit($limit);
    public function raw($query);
    public function query();
}