<?php

declare(strict_types=1);

namespace WolfShop\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiClientException extends HttpException
{
    /**
     * ApiClientException constructor.
     *
     * @param string                         $message   Exception message
     * @param \Throwable|null                $previous  Previous exception
     * @param int                            $code      Exception code
     * @param array<string, string|string[]> $headers   Exception headers
     */
    public function __construct(string $message = 'Error communicating with the external API', \Throwable $previous = null, int $code = 500, array $headers = [])
    {
        parent::__construct($code, $message, $previous, $headers);
    }
}
