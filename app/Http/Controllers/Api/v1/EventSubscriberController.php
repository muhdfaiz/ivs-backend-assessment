<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\ApiErrorException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\v1\CreateEventSubscriberRequest;
use App\Services\Api\v1\EventSubscriberService;
use Illuminate\Http\JsonResponse;

class EventSubscriberController extends ApiController
{
    /**
     * @var EventSubscriberService
     */
    protected $service;

    /**
     * @param EventSubscriberService $service
     */
    public function __construct(EventSubscriberService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieve all event subscribers available.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $eventSubscribers = $this->service->getAllEventSubscriber();

        return $this->successResponse($eventSubscribers, __('api.get'));
    }

    /**
     * View specific event subscribers by ID.
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function view(int $id): JsonResponse
    {
        $eventSubscribers = $this->service->retrieveEventSubscriberByID($id);

        return $this->successResponse($eventSubscribers, __('api.get'));
    }

    /**
     * Create new event subscriber.
     *
     * @param CreateEventSubscriberRequest $request
     *
     * @return JsonResponse
     */
    public function store(CreateEventSubscriberRequest $request): JsonResponse
    {
        $inputs = $request->all();

        $eventSubscriber = $this->service->storeNewEventSubscriber($inputs);

        return $this->successResponse($eventSubscriber, __('api.created'));
    }
}
