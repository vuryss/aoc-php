<?php

declare(strict_types=1);

namespace App;

use SensitiveParameter;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class AnswerResolver
{
    const string URL_FORMAT = 'https://adventofcode.com/%s/day/%s';

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
        private HttpClientInterface $httpClient,
        #[SensitiveParameter]
        private string $sessionToken,
    ) {
    }

    /**
     * @psalm-return array{part1: string, part2: string}
     * @throws ExceptionInterface
     */
    public function getAnswersForYearAndDay(int $year, int $day): ?array
    {
        $cacheFile = sprintf('%s/answers/%s/%s', $this->projectDir, $year, $day);

        if (!file_exists($cacheFile)) {
            $downloadedAnswers = $this->downloadForYearAndDay($year, $day);

            if (!file_exists(dirname($cacheFile))) {
                mkdir(dirname($cacheFile), 0777, true);
            }

            file_put_contents($cacheFile, json_encode($downloadedAnswers));
        }

        return json_decode(file_get_contents($cacheFile), associative: true);
    }

    /**
     * @return array{part1: string, part2: string}
     * @throws ExceptionInterface
     */
    private function downloadForYearAndDay(int $year, int $day): array
    {
        echo 'Downloading answers for ' . $year . ' day ' . $day . PHP_EOL;

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

        $crawler = new Crawler($response->getContent());
        $nodes = $crawler->filter('article + p > code');

        if ($day < 25) {
            return ['part1' => $nodes->eq(0)->text(), 'part2' => $nodes->eq(1)->text()];
        }

        return ['part1' => $nodes->text(), 'part2' => ''];
    }
}
