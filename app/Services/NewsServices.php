<?php

namespace App\Services;

use GuzzleHttp\Client;

class NewsServices
{
    private $numberRegister = 10;

    public function getNewsList($page = 1)
    {
        $allArticles = $this->fetchNewsArticles();

        $totalArticles = count($allArticles);
        $totalPages = ceil($totalArticles / $this->numberRegister);
        $page = max(1, min($page, $totalPages));
        $start = ($page - 1) * $this->numberRegister;
        $end = min($start + $this->numberRegister, $totalArticles);

        $articles = array_slice($allArticles, $start, $this->numberRegister);

        return [
            'articles' => $articles,
            'totalPages' => $totalPages
        ];
    }

    public function getAuthorsList()
    {
        $authors = $this->fetchAuthors();

        $collectionAuthors = collect($authors)->map(function ($author) {
            return [
                'name' => [
                    'first' => $author['name']['first'],
                    'last' => $author['name']['last'],
                ],
                'email' => $author['email']
            ];
        })->all();

        return $collectionAuthors;
    }

    private function fetchNewsArticles()
    {
        $client = new Client();

        $allNewsResponse = $client->get('https://newsapi.org/v2/top-headlines', [
            'query' => [
                'apiKey' => config('services.newsapi.key'),
                'country' => 'de',
                'category' => 'business',
                'pageSize' => 100
            ],
        ]);

        return json_decode($allNewsResponse->getBody(), true)['articles'];
    }

    private function fetchAuthors()
    {
        $client = new Client();
        $response = $client->get('https://randomuser.me/api/', [
            'query' => [
                'results' => $this->numberRegister,
            ],
        ]);

        return json_decode($response->getBody(), true)['results'];
    }
}
