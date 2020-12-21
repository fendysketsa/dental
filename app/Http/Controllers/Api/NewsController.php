<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsCollection;
use App\Models\Api\NewsModel;

class NewsController extends Controller
{
    public function index()
    {
        return new NewsCollection(NewsModel::orderBy('id', 'desc')->paginate(5));
    }
}