<?php

namespace App\Repositories\Eloquent;

use App\Contracts\EventSubscriberRepository as EventSubscriberRepositoryContract;
use App\Models\EventSubscriber;

class EventSubscriberRepository implements EventSubscriberRepositoryContract
{
    /**
     * Get all event subscribers.
     *
     * @return mixed
     */
    public function getAll()
    {
        return EventSubscriber::select('id', 'name')->get();
    }

    /**
     * Get event subscriber by ID.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getByID(int $id)
    {
        return EventSubscriber::select('id', 'email', 'name')->where('id', $id)->first();
    }

    /**
     * Store new event subscriber in storage.
     *
     * @param array $inputs
     *
     * @return mixed|void
     */
    public function store(array $inputs)
    {
        return EventSubscriber::create($inputs);
    }
}
