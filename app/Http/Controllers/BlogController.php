<?php

namespace App\Http\Controllers;

use App\Services\NewsServices;
use Illuminate\Http\Request;


class BlogController extends Controller
{

    public function __construct(private NewsServices $newsServices)
    {
        $this->newsServices = $newsServices;
    }
    public $numberRegister = 10;
    public function index(Request $request, $page = 1)
    {
        $result = $this->newsServices->getNewsList($page);

        $news = $result['articles'];

        $totalPages = $result['totalPages'];

        $authors = $this->newsServices->getAuthorsList();

        return view('blogs.index', compact('news', 'authors', 'totalPages', 'page'));
    }


}
