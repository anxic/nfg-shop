<?php

declare(strict_types=1);

namespace WolfShop\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiClientException extends HttpException
{
    /**
     * ApiClientException constructor.
     *
     * @param string          $message   Message de l'exception
     * @param \Throwable|null $previous  Exception précédente
     * @param int             $code      Code d'erreur (HTTP)
     * @param array           $headers   En-têtes de la réponse
     */
    public function __construct(string $message = 'Error communicating with the external API', \Throwable $previous = null, int $code = 500, array $headers = [])
    {
        parent::__construct($code, $message, $previous, $headers);
    }
}
