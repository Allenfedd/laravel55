<?php

namespace App\Support\Response;

use Illuminate\Routing\ResponseFactory;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class Response
{
    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    private $response;

    private $statusCode = HttpResponse::HTTP_OK;

    /**
     * @var \APP\Support\Response\Transform
     */
    public $transform;

    public function __construct(ResponseFactory $response, Transform $transform)
    {
        $this->response = $response;
        $this->transform = $transform;
    }

    /**
     * Return a 201 response with the given created resource.
     */
    public function withCreated($resource = null, TransformerAbstract $transformer = null)
    {
        $this->statusCode = HttpResponse::HTTP_CREATED;

        if (is_null($resource)) {
            return $this->json();
        }

        return $this->item($resource, $transformer);
    }

    /**
     * Make a 204 'No Content' response
     */
    public function withNoContent()
    {
        return $this->setStatusCode(HttpResponse::HTTP_NO_CONTENT)->json();
    }

    /**
     * Make a 400 'Bad Request' response
     */
    public function withBadRequest($message = 'Bad Request')
    {
        return $this->setStatusCode(HttpResponse::HTTP_BAD_REQUEST)->withError($message);
    }

    /**
     * Make a 401 'Unauthorized' response
     */
    public function withUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(HttpResponse::HTTP_UNAUTHORIZED)->withError($message);
    }

    /**
     * Make a 403 'Forbidden' response
     */
    public function withForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(HttpResponse::HTTP_FORBIDDEN)->withError($message);
    }

    /**
     * Make a 404 'Not Found' response
     */
    public function withNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)->withError($message);
    }

    /**
     * Make a 500 'Internal Server Error' response.
     */
    public function withInternalServer($message = 'Internal Server Error')
    {
        return $this->setStatusCode(HttpResponse::HTTP_INTERNAL_SERVER_ERROR)->withError($message);
    }

    public function withError($message)
    {
        return $this->json(['message' => is_array($message) ? $message : [$message]]);
    }

    public function item($item, TransformerAbstract $transformer = null)
    {
        return $this->json($this->transform->item($item, $transformer));
    }

    public function collection($items, TransformerAbstract $transformer = null)
    {
        return $this->json($this->transform->collection($items, $transformer));
    }

    /**
     * Make a JSON response.
     *
     * @param mixed $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($data = [], array $headers = [])
    {
        return $this->response->json($data, $this->statusCode, $headers);
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}