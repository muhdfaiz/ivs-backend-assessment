<?php

namespace App\Exceptions;

use App\Resources\Api\ErrorResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ApiErrorException::class, ApiValidationException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     *
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        $e = $this->prepareException($this->mapException($e));

        foreach ($this->renderCallbacks as $renderCallback) {
            foreach ($this->firstClosureParameterTypes($renderCallback) as $type) {
                if (is_a($e, $type)) {
                    $response = $renderCallback($e, $request);

                    if (! is_null($response)) {
                        return $response;
                    }
                }
            }
        }

        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        } elseif ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        } elseif ($e instanceof ApiValidationException) {
            return $this->convertApiValidationExceptionToResponse($e, $request);
        } elseif ($e instanceof ApiErrorException) {
            return response()->json(new ErrorResource($e), $e->getCode());
        }

        return $this->shouldReturnJson($request, $e)
            ? $this->prepareJsonResponse($request, $e)
            : $this->prepareResponse($request, $e);
    }

        /**
     * Create a response object from the given api validation exception.
     *
     * @param ApiValidationException $e
     * @return Response
     */
    protected function convertApiValidationExceptionToResponse(ApiValidationException $e): Response
    {
        $errors = collect($e->errors())->flatten();

        return response()->json([
            'status' => 'error',
            'error' => [
                'title' => 'Validation Failed!',
                'message' => $e->getMessage(),
                'data' => $errors
            ]
        ], $e->status);
    }

    /**
     * Handle AuthenticationException.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse|RedirectResponse|Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->is('api/*')) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'title' => $exception->getMessage(),
                    'message' => 'Invalid access token. Please make sure access token is valid.',
                ]
            ], 401);
        }

        return $this->shouldReturnJson($request, $exception)
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
