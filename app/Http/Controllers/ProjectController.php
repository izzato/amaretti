<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Rinvex\Categories\Models\Category;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('permission:view dashboard', ['except' => ['index', 'show']]);

        $this->middleware('can:list,App\Project', ['only' => ['list']]);
        $this->middleware('can:create,App\Project', ['only' => ['create', 'store']]);
        $this->middleware('can:update,project', ['only' => ['edit', 'update']]);
        $this->middleware('can:trash,project', ['only' => ['trash', 'restore']]);
        $this->middleware('can:delete,project', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (config('app.open_to_public') === false && auth()->guest() && Cookie::get('testing_app') === null) {
            return response()->view('coming-soon', [], 503);
        }
        $projects = Project::with('categories')->published()->orderBy('featured', 'desc')->orderBy('sort_order', 'desc')->orderBy('published_at', 'desc')->get();
        $category_ids = $projects->map(function($project , $index){
             return $project->categories->pluck('id');
        });
        $categories = app('rinvex.categories.category')->whereIn('id', $category_ids->flatten())->get();
        return view('projects', compact('projects', 'categories'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        if (request()->query('status', '') === 'trashed') {
            $projects = Project::onlyTrashed()->with('user')->latest('deleted_at')->get();
        }
        else {
            $projects = Project::with('user')->latest()->get();
        }

        return view('projects.list', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        $keywords = $project->getKeywords();
        $categories = app('rinvex.categories.category')->all();
        $users = User::latest()->get();
        return view('projects.create', compact('project', 'users', 'categories', 'keywords'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $project = new Project($request->all());
        $project->user_id = $request->user_id ?: auth()->user()->id;
        $project->featured = !!$request->featured;
        $project->excerpt = (empty($request->excerpt)) ? $project->getExcerpt($request->get('content')) : $request->excerpt;
        $project->meta_keywords = strtolower(is_array($request->meta_keywords) ? implode(',', $request->meta_keywords) : $request->meta_keywords);
        if (!!$request->published && $request->published_at === null) {
            $project->published_at = Carbon::now();
        }
        if ($request->published_at) {
            $project->published_at = $request->published_at;
        }
        $project->slug = str_slug($request->slug);
        $project->save();

        $category_ids = (is_array($request->categories)) ? array_map(function ($value, $index) {
            if (intval($value) <= 0) {
                return Category::create(['name' => $value])->id;
            }
            return intval($value);
        }, $request->categories, array_keys($request->categories)) : [];

        $project->syncCategories($category_ids, true);

        flash('Project has been created &mdash; <a href="' . route('projects.show', $project) . '">View project</a>')->success()->important();

        if ($request->query('return')) {
            return redirect($request->query('return'));
        }

        return redirect()->route('projects.edit', $project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        if ((!$project->published_at || $project->published_at->gt(now())) && !optional(auth()->user())->hasAnyRole(['admin', 'owner', 'user'])) {
            abort(404);
        }
        return view('projects.show', compact('project'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function media(Project $project, $collection = 'default')
    {
        return response()->json(['data' => $project->getMedia($collection)], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $keywords = $project->getKeywords();
        $users = User::latest()->get();
        $categories = app('rinvex.categories.category')->all();
        return view('projects.create', compact('project', 'users', 'categories', 'keywords'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $dummy = new Project();
        $dummy->featured = !!$request->featured;
        $dummy->excerpt = (empty($request->excerpt)) ? $project->getExcerpt($request->get('content')) : $request->excerpt;
        $dummy->meta_keywords = strtolower(is_array($request->meta_keywords) ? implode(',', $request->meta_keywords) : $request->meta_keywords);
        if (!!$request->published && $request->published_at === null) {
            $dummy->published_at = Carbon::now();
        }
        if (!$request->published) {
            $dummy->published_at = null;
        }
        if (!$request->user_id) {
            $dummy->user_id = $project->user_id;
        }
        $dummy->slug = str_slug($request->slug);

        $category_ids = (is_array($request->categories)) ? array_map(function ($value, $index) {
            if (intval($value) <= 0) {
                return Category::create(['name' => $value])->id;
            }
            return intval($value);
        }, $request->categories, array_keys($request->categories)) : [];

        $project->syncCategories($category_ids, true);

        $project->update($request->merge($dummy->toArray())->all());

        flash('Project has been updated &mdash; <a href="' . route('projects.show', $project) . '">View project</a>')->success()->important();

        if ($request->query('return')) {
            return redirect($request->query('return'));
        }

        return redirect()->route('projects.edit', $project);
    }

    /**
     * Trash the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function trash(Project $project)
    {
        $project->delete();

        flash('Project has been moved to trash')->success();

        return redirect()->route('projects.list');
    }

    /**
     * Restore the specified resource.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function restore(Project $project)
    {
        $project->restore();

        flash('Project has been restored')->success();

        return redirect()->route('projects.list', ['status' => 'trashed']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->forceDelete();

        flash('Project has been permanently deleted')->success();

        return redirect()->route('projects.list', ['status' => 'trashed']);
    }
}
