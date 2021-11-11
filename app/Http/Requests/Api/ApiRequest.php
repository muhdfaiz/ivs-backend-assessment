<?php

namespace App\Http\Requests\Api;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Exceptions\ApiValidationException;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Validation\ValidationException;

class ApiRequest extends Request implements ValidatesWhenResolved
{
    use ValidatesWhenResolvedTrait;

    /**
     * The container instance.
     *
     * @var Container
     */
    protected $container;

    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected string $errorBag = 'default';

    /**
     * The validator instance.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Get the validator instance for the request.
     *
     * @return Validator
     * @throws BindingResolutionException
     */
    protected function getValidatorInstance(): Validator
    {
        if ($this->validator) {
            return $this->validator;
        }

        $factory = $this->container->make(ValidationFactory::class);

        if (method_exists($this, 'validator')) {
            $validator = $this->container->call([$this, 'validator'], compact('factory'));
        } else {
            $validator = $this->createDefaultValidator($factory);
        }

        if (method_exists($this, 'withValidator')) {
            $this->withValidator($validator);
        }

        $this->setValidator($validator);

        return $this->validator;
    }

    /**
     * Create the default validator instance.
     *
     * @param ValidationFactory $factory
     * @return Validator
     */
    protected function createDefaultValidator(ValidationFactory $factory): Validator
    {
        return $factory->make(
            $this->validationData(), $this->container->call([$this, 'rules']),
            $this->messages(), $this->attributes()
        );
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        return $this->all();
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws ApiValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ApiValidationException($validator))
            ->errorBag($this->errorBag);
    }

    /**
     * Determine if the request passes the authorization check.
     *
     * @return bool
     * @throws AuthorizationException
     */
    protected function passesAuthorization(): bool
    {
        if (method_exists($this, 'authorize')) {
            $result = $this->container->call([$this, 'authorize']);

            return $result instanceof Response ? $result->authorize() : $result;
        }

        return true;
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException;
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     * @throws ValidationException
     */
    public function validated(): array
    {
        return $this->validator->validated();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * Set the Validator instance.
     *
     * @param Validator $validator
     * @return $this
     */
    public function setValidator(Validator $validator): ApiRequest
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Set the container implementation.
     *
     * @param Container $container
     * @return $this
     */
    public function setContainer(Container $container): ApiRequest
    {
        $this->container = $container;

        return $this;
    }
}
