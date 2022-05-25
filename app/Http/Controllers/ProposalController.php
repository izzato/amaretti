<?php

namespace App\Http\Controllers;

use App\User;
use App\Proposal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProposalRequest;
use App\Http\Requests\UpdateProposalRequest;
use Illuminate\Support\Facades\Cookie;

class ProposalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'password']]);
        $this->middleware('throttle:10,1', ['only' => ['password']]);
        $this->middleware('permission:view dashboard', ['except' => ['show', 'password']]);

        $this->middleware('can:list,App\Proposal', ['only' => ['list']]);
        $this->middleware('can:create,App\Proposal', ['only' => ['create', 'store']]);
        $this->middleware('can:update,proposal', ['only' => ['edit', 'update']]);
        $this->middleware('can:trash,proposal', ['only' => ['trash', 'restore']]);
        $this->middleware('can:delete,proposal', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        if (request()->query('status', '') === 'trashed') {
            $proposals = Proposal::onlyTrashed()->with('user')->latest('deleted_at')->get();
        }
        else {
            $proposals = Proposal::with('user')->latest()->get();
        }

        return view('proposals.list', compact('proposals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function create(Proposal $proposal)
    {
        $users = User::latest()->get();
        return view('proposals.create', compact('proposal', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreProposalRequest $request
     * @param \App\Proposal $proposal
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProposalRequest $request, Proposal $proposal)
    {
        $model = new Proposal($request->all());

        $model->user_id = $request->user_id ?: auth()->user()->id;

        if ($request->published && $request->published_at === null) {
            $model->published_at = Carbon::now();
        }
        
        if ($request->published_at) {
            $model->published_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->published_at)->toDateTimeString();
        }

        $model->slug = str_slug($request->slug);

        if ($request->password ) {
            $model->password = $request->password;
        }

        $model->save();

        $location = 'public/uploads/';

        $request->file('pdf')->storeAs(
            $location,
            $model->pathHash() . '.pdf'
        );

        flash('Proposal has been created &mdash; <a href="' . route('proposals.show', $model) . '">View proposal</a>')->success()->important();

        if ($request->query('return')) {
            return redirect($request->query('return'));
        }

        return redirect()->route('proposals.edit', $model);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function show(Proposal $proposal)
    {
        if ((!$proposal->published_at || $proposal->published_at->gt(now())) && !optional(auth()->user())->hasAnyRole(['admin', 'owner'])) {
            abort(404);
        }
        if ($proposal->password !== null && auth()->guest() && Cookie::get('proposal_' . $proposal->slug) !== sha1_file(public_path() . '/storage/uploads/' . $proposal->pathHash() . '.pdf')) {
            return response()->view('proposals.password', compact('proposal'));
        }
        return view('proposals.show', compact('proposal'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param \App\Proposal $proposal
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request, Proposal $proposal)
    {
        if ($request->password === $proposal->password) {
            Cookie::queue(Cookie::make('proposal_' . $proposal->slug, sha1_file(public_path() . '/storage/uploads/' . $proposal->pathHash() . '.pdf'), 120));
        } else {
            request()->session()->flash('status', 'Incorrect password');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Proposal $proposal
     * @param string $collection
     * @return \Illuminate\Http\Response
     */
    public function media(Proposal $proposal, $collection = 'default')
    {
        return response()->json(['data' => $proposal->getMedia($collection)], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Proposal $proposal
     * @return \Illuminate\Http\Response
     */
    public function edit(Proposal $proposal)
    {
        $users = User::latest()->get();
        return view('proposals.create', compact('proposal', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProposalRequest  $request
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProposalRequest $request, Proposal $proposal)
    {
        $model = new Proposal();

        if (!$request->published) {
            $model->published_at = null;
        }

        if ($request->published && $request->published_at === null) {
            $model->published_at = Carbon::now();
        }

        if (!$request->user_id) {
            $model->user_id = $proposal->user_id;
        }

        $model->slug = str_slug($request->slug);

        $proposal->update($request->merge($model->toArray())->all());

        flash('Proposal has been updated &mdash; <a href="' . route('proposals.show', $proposal) . '">View proposal</a>')->success()->important();

        if ($request->query('return')) {
            return redirect($request->query('return'));
        }

        return redirect()->route('proposals.edit', $proposal);
    }

    /**
     * Trash the specified resource.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function trash(Proposal $proposal)
    {
        try {
            $proposal->delete();

             flash('Proposal has been moved to trash')->success();
        } catch (\Exception $exception) {
             flash('Proposal could not be deleted')->warning();
        }

        return redirect()->route('proposals.list');
    }

    /**
     * Restore the specified resource.
     *
     * @param Proposal $proposal
     * @return \Illuminate\Http\Response
     */
    public function restore(Proposal $proposal)
    {
        try {
            $proposal->restore();

            flash('Proposal has been restored')->success();
        } catch (\Exception $exception) {
             flash('Proposal could not be restored')->info();
        }

        return redirect()->route('proposals.list', ['status' => 'trashed']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Proposal $proposal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proposal $proposal)
    {
        try {
            $path = public_path() . '/storage/uploads/' . $proposal->pathHash() . '.pdf';
            if (file_exists($path)) {
                unlink($path);
            }

            $proposal->forceDelete();

            flash('Proposal has been permanently deleted')->success();
        } catch (\Exception $exception) {
             flash('Proposal could not be deleted')->warning();
        }

        return redirect()->route('proposals.list');
    }
}
