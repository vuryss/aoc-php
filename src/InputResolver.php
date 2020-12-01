<?php

declare(strict_types=1);

namespace App;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InputResolver
{
    const URL_FORMAT = 'https://adventofcode.com/%s/day/%s/input';

    private HttpClientInterface $httpClient;
    private CacheInterface $cache;
    private string $sessionToken;

    public function __construct(HttpClientInterface $httpClient, CacheInterface $aocInput, string $sessionToken)
    {
        $this->httpClient = $httpClient;
        $this->cache = $aocInput;
        $this->sessionToken = $sessionToken;
    }

    /**
     * @param int $year
     * @param int $day
     *
     * @return string|null
     */
    public function getInputForYearAndDay(int $year, int $day): ?string
    {
        try {
            return $this->cache->get(
                'event.' . $year . '.' . $day,
                fn () => $this->downloadForYearAndDay($year, $day)
            );
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * @param int $year
     * @param int $day
     *
     * @return string|null
     * @throws ExceptionInterface
     */
    private function downloadForYearAndDay(int $year, int $day): ?string
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf(self::URL_FORMAT, $year, $day),
            [
                'headers' => [
                    'cookie' => 'session=' . $this->sessionToken,
                ],
            ]
        );

        $input = $response->getContent();

        return is_string($input) ? $input : null;
    }
}
