<?php

namespace App\Support\Response;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item as FractalItem;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection as FractalCollection;

class Transform
{
    private $fractal;

    protected $includeKey = 'include';

    protected $includeSeparator = ',';

    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;

        $this->parseFractalIncludes(request());
    }

    public function collection($data, TransformerAbstract $transformer = null)
    {
        $collection = new FractalCollection($data, $transformer);

        if ($data instanceof LengthAwarePaginator) {
            $collection->setPaginator(new IlluminatePaginatorAdapter($data));
        }

        return $this->fractal->createData($collection)->toArray();
    }

    public function item($data, TransformerAbstract $transformer = null)
    {
        return $this->fractal->createData(new FractalItem($data, $transformer))->toArray();
    }

    public function parseFractalIncludes(Request $request)
    {
        $includes = $request->input('include');

        if (!is_array($includes)) {
            $includes = array_filter(explode($this->includeSeparator, $includes));

            $this->fractal->parseIncludes($includes);
        }
    }
}