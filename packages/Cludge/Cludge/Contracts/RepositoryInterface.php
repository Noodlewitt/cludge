<?php
/**
 * @file(RepositoryInterface.php)
 *
 * Interface that all repositories should implement
 */
namespace Cludge\Contracts;

interface RepositoryInterface
{
    public function all($options = [], $columns = ['*']);

    public function paginate($options = [], $perPage = 15, $columns = ['*']);

    public function create(array $data);

    public function update(array $data, $id, $attribute = 'id');

    public function delete($id);

    public function find($id, $options = [], $columns = ['*']);

    public function findOrFail($id, $options = [], $columns = ['*']);

    public function findBy($field, $options = [], $value, $columns = ['*']);
}