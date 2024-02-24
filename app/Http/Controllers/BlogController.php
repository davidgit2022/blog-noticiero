<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlogController extends Controller
{
    public $numberRegister = 10;
    public function index(Request $request,  $page = 1)
    {


        $result = $this->getNews($page);
        $news = $result['articles'];
        $totalPages = $result['totalPages'];

        $authors = $this->getAuthors();

        return view('blogs.index', compact('news', 'authors', 'totalPages', 'page'));
    }


    private function getNews($page = 2)
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

        $allArticles = json_decode($allNewsResponse->getBody(), true)['articles'];
        $totalArticles = count($allArticles);
        $totalPages = ceil($totalArticles / $this->numberRegister);


        $page = max(1, min($page, $totalPages));
        $start = ($page - 1) * $this->numberRegister;
        $end = min($start + $this->numberRegister, $totalArticles);

        $articles = array_slice($allArticles, $start, $this->numberRegister);

        return compact('articles', 'totalPages');
    }

    private function getAuthors()
    {
        $client = new Client();
        $response = $client->get('https://randomuser.me/api/', [
            'query' => [
                'results' => $this->numberRegister,
            ],
        ]);

        $authors = json_decode($response->getBody(), true)['results'];

        return collect($authors)->map(function ($author) {
            return [
                'name' => [
                    'first' => $author['name']['first'],
                    'last' => $author['name']['last'],
                ],
                'email' => $author['email']
            ];
        })->all();
    }
}
