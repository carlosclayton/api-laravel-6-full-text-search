<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ProductRepository;
use App\Models\Product;
use App\Validators\ProductValidator;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductRepositoryEloquent extends BaseRepository implements ProductRepository
{

    protected $fieldSearchable = [
        'id' => '=',
        'name' => 'like',
        'price' => 'like'
    ];


    public function paginate($limit = null, $page = null, $columns =
    ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ?
            config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns, 'page',
            $page);
        $results->appends(app('request')->query());
        $this->resetModel();
        return $this->parserResult($results);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return ProductValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


}
