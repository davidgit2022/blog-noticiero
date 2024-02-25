<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Utils\FetchUtils;

class NewsServices
{
    private $numberRegister = 10;

    public function getNewsList($page = 1)
    {
        $allArticles = FetchUtils::fetchNewsArticles();

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
        $authors = FetchUtils::fetchAuthors($this->numberRegister);

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

}
