<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class SaveException extends Exception
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $code;

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Failed to save data", int $code = 422, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function render($request) {
        return response()->json(["message" => $this->message], $this->code);
    }
}
