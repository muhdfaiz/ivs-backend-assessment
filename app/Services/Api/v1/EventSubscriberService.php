<?php
namespace App\Services\Api\v1;

use App\Contracts\EventSubscriberRepository;
use App\Exceptions\ApiErrorException;

class EventSubscriberService
{
    /**
     * @var EventSubscriberRepository
     */
    protected $repository;

    /**
     * @param EventSubscriberRepository $repository
     */
    public function __construct(EventSubscriberRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all event subscribers available.
     *
     * @return mixed
     */
    public function getAllEventSubscriber()
    {
        return $this->repository->getAll();
    }

    /**
     * Get all event subscribers available.
     *
     * @return mixed
     * @throws ApiErrorException
     */
    public function retrieveEventSubscriberByID(int $id)
    {
        $eventSubscriber = $this->repository->getByID($id);

        if (!$eventSubscriber) {
            throw (new ApiErrorException())->setData(__('api.failed'), __('api.not_found'), 404);
        }

        return $eventSubscriber;
    }

    /**
     * Store new event subscriber in storage.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function storeNewEventSubscriber(array $inputs)
    {
        return $this->repository->store($inputs);
    }
}
