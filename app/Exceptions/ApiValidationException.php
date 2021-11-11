<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Symfony\Component\HttpFoundation\Response;

class ApiValidationException extends Exception
{
    /**
     * The validator instance.
     *
     * @var Validator
     */
    public Validator $validator;

    /**
     * The recommended response to send to the client.
     *
     * @var Response|null
     */
    public ?Response $response;

    /**
     * The status code to use for the response.
     *
     * @var int
     */
    public int $status = 422;

    /**
     * The name of the error bag.
     *
     * @var string
     */
    public string $errorBag;

    /**
     * Create a new exception instance.
     *
     * @param  Validator  $validator
     * @param  Response|null  $response
     * @param  string  $errorBag
     * @return void
     */
    public function __construct(Validator $validator, $response = null, $errorBag = 'default')
    {
        parent::__construct('The given data was invalid.');

        $this->response = $response;
        $this->errorBag = $errorBag;
        $this->validator = $validator;
    }

    /**
     * Create a new validation exception from a plain array of messages.
     *
     * @param  array  $messages
     * @return static
     */
    public static function withMessages(array $messages): ApiValidationException
    {
        return new static(tap(ValidatorFacade::make([], []), function ($validator) use ($messages) {
            foreach ($messages as $key => $value) {
                foreach (Arr::wrap($value) as $message) {
                    $validator->errors()->add($key, $message);
                }
            }
        }));
    }

    /**
     * Get all the validation error messages.
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->validator->errors()->messages();
    }

    /**
     * Set the HTTP status code to be used for the response.
     *
     * @param int $status
     *
     * @return $this
     */
    public function status(int $status): ApiValidationException
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set the error bag on the exception.
     *
     * @param string $errorBag
     *
     * @return $this
     */
    public function errorBag(string $errorBag): ApiValidationException
    {
        $this->errorBag = $errorBag;

        return $this;
    }

    /**
     * Get the underlying response instance.
     *
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
