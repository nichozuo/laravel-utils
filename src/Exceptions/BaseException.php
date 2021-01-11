<?php


namespace Nichozuo\LaravelUtils\Exceptions;


use Exception;

class BaseException extends Exception
{
    private string $description;

    /**
     * BaseException constructor.
     * @param int $code
     * @param string $message
     * @param string $description
     */
    public function __construct(int $code, string $message, string $description)
    {
        $this->code = $code;
        $this->message = $message;
        $this->description = $description;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

}

