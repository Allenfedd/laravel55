<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Support\Response\Response;

class ApiController extends Controller
{
    /**
     * @var \App\Support\Response\Response
     */
    public function response(){
        return app(Response::class);
    }
}
