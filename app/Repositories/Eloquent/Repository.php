<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RepositoryInterface;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var Application
     */
    protected $app;

    protected $model;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    abstract public function model();

    /**
     * @throws \Exception
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * @return Model|mixed
     * @throws \Exception
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function all($columns = ['*'])
    {
        $result = $this->model->get($columns);

        $this->resetModel();

        return $result;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id, $attribute = 'id')
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function pluck($columns, $key = null)
    {
        return $this->model->pluck($columns, $key);
    }

    public function paginate($perPage = null, $columns = ['*'], $method = "paginate")
    {
        $paginator = $this->model->{$method}($perPage, $columns);

        $this->resetModel();

        return $paginator;
    }

    public function simplePaginate($perPage = null, $columns = ['*'])
    {
        return $this->paginate($perPage, $columns, 'simplePaginate');
    }

    public function find($id, $columns = ['*'])
    {
        $result = $this->model->find($id, $columns);

        $this->resetModel();

        return $result;
    }

    public function findOrFail($id, $columns = ['*'])
    {
        $result = $this->model->findOrFail($id, $columns);

        $this->resetModel();

        return $result;
    }

    public function findBy($attribute, $value, $columns = ['*'])
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    public function findAllBy($attribute, $value, $columns = ['*'])
    {
        return $this->model->where($attribute, '=', $value)->get($columns);
    }
}