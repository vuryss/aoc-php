<?php

declare(strict_types=1);

namespace App;

use SensitiveParameter;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class InputResolver
{
    const string URL_FORMAT = 'https://adventofcode.com/%s/day/%s/input';

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
        private HttpClientInterface $httpClient,
        #[SensitiveParameter]
        private string $sessionToken,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function getInputForYearAndDay(int $year, int $day): ?string
    {
        $cacheFile = sprintf('%s/inputs/%s/%s', $this->projectDir, $year, $day);

        if (!file_exists($cacheFile)) {
            $downloadedInput = $this->downloadForYearAndDay($year, $day);

            if (!file_exists(dirname($cacheFile))) {
                mkdir(dirname($cacheFile), 0777, true);
            }

            file_put_contents($cacheFile, $downloadedInput);
        }

        return file_get_contents($cacheFile);
    }

    /**
     * @throws ExceptionInterface
     */
    private function downloadForYearAndDay(int $year, int $day): string
    {
        echo 'Downloading input for ' . $year . ' day ' . $day . PHP_EOL;

        $response = $this->httpClient->request(
            'GET',
            sprintf(self::URL_FORMAT, $year, $day),
            [
                'headers' => [
                    'cookie' => 'session=' . $this->sessionToken,
                    'user-agent' => 'https://github.com/vuryss/aoc-php by vuryss@gmail.com',
                ],
            ]
        );

        return $response->getContent();
    }
}
