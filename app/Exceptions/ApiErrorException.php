<?php

namespace App\Exceptions;

class ApiErrorException extends \Exception
{
    /**
     * @var string $title
     */
    protected string $title;

    /**
     * @var string $message
     */
    protected $message;

    /**
     * Set the affected Eloquent model and instance ids.
     *
     * @param string $title
     * @param string $message
     * @param int $status
     *
     * @return ApiErrorException
     */
    public function setData(string $title, string $message, int $status): ApiErrorException
    {
        $this->title = $title;
        $this->message = $message;
        $this->code = $status;

        return $this;
    }

    /**
     * Get the error message
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
