<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

class PageController extends Controller
{
    public function welcome()
    {
        if (config('app.open_to_public') === false && auth()->guest() && Cookie::get('testing_app') === null) {
            return response()->view('coming-soon', [], 503);
        }
        return view('welcome');
    }

    public function sitemap()
    {
        $projects = Cache::remember('projects', 60, function () {
            return Project::published()->orderBy('featured', 'desc')->orderBy('sort_order', 'desc')->orderBy('published_at', 'desc')->get();
        });
        return response()->view('sitemap', compact('projects'))->header('Content-Type', 'text/xml');
    }

    public function password() {
        if (request()->password === config('app.password')) {
            Cookie::queue(Cookie::make('testing_app', true, 60));
        } else {
            request()->session()->flash('status', 'Incorrect password');
        }
        return redirect('/');
    }
}
