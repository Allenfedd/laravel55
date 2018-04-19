<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*']);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $columns
     * @param null $key
     * @return mixed
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function pluck($columns, $key = null);

    /**
     * @param null $limit
     * @param array $columns
     * @return mixed
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*']);

    /**
     * @param null $limit
     * @param array $columns
     * @return mixed
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($perPage = null, $columns = ['*']);

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findOrFail($id, $columns = ['*']);

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = ['*']);

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findAllBy($attribute, $value, $columns = ['*']);

    /**
     * Load relations
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations);
}
