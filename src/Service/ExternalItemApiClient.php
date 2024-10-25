<?php

declare(strict_types=1);

namespace WolfShop\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use WolfShop\DTO\ApiItemDTO;
use WolfShop\Exception\ApiClientException;
use WolfShop\Strategy\AbstractItemUpdateStrategy;

final class ExternalItemApiClient
{
    private const API_URL = 'https://api.restful-api.dev/objects';

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    /**
     * Fetches items from the external API and returns an array of ApiItemDto.
     *
     * @return ApiItemDto[]
     *
     * @throws ApiClientException
     */
    public function fetchItems(): array
    {
        $response = $this->sendRequest();
        $data = $this->parseResponse($response);
        return $this->transformDataToItems($data);
    }

    /**
     * Sends a GET request to the external API.
     *
     * @throws ApiClientException
     */
    private function sendRequest(): ResponseInterface
    {
        try {
            $response = $this->httpClient->request('GET', self::API_URL);

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new ApiClientException(
                    message: sprintf('HTTP error %d while fetching data.', $response->getStatusCode()),
                    code: $response->getStatusCode()
                );
            }

            return $response;
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw new ApiClientException('Error communicating with the external API.', $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Parses the response content to an array.
     *
     * @throws ApiClientException
     */
    private function parseResponse(ResponseInterface $response): array
    {
        try {
            $content = $response->getContent();
            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            if (! is_array($data)) {
                throw new ApiClientException('Received data is not in the expected format.', code: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $data;
        } catch (\JsonException $e) {
            throw new ApiClientException('Error decoding JSON data.', $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Transforms the data array to an array of ApiItemDTO.
     *
     * @return ApiItemDTO[]
     */
    private function transformDataToItems(array $data): array
    {
        $items = [];
        foreach ($data as $itemData) {
            try {
                $items[] = $this->createItemDTO($itemData);
            } catch (\InvalidArgumentException $e) {
                continue;
            }
        }

        return $items;
    }

    /**
     * Creates an ApiItemDTO from item data.
     */
    private function createItemDTO(array $itemData): ApiItemDTO
    {
        $id = $itemData['id'] ?? '';
        $name = $itemData['name'] ?? '';
        $itemDetails = $itemData['data'] ?? null;

        //TODO: This is a temporary solution to generate random values for sellIn and Quality
        // In a real-world scenario, these values should be fetched from the API response
        $sellIn = $itemDetails['sellIn'] ?? rand(1, 30);
        $quality = $itemDetails['quality'] ?? rand(AbstractItemUpdateStrategy::MIN_QUALITY, AbstractItemUpdateStrategy::MAX_QUALITY);

        if (empty($name)) {
            throw new \InvalidArgumentException('Item ID or name is missing.');
        }

        return new ApiItemDTO($id, $name, $sellIn, $quality, $itemDetails);
    }
}
