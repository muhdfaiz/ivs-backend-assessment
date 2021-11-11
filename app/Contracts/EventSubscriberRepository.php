<?php

namespace App\Contracts;

interface EventSubscriberRepository {
    /**
     * Get all records.
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get record by ID.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getByID(int $id);

    /**
     * Store new record.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function store(array $inputs);
}

