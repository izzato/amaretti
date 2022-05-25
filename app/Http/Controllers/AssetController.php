<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Media;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Models\Media as MediaItem;

class AssetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => array_merge(
            collect(Storage::disk('uploads')->allFiles())->map(function ($file) {
                return [Storage::disk('uploads')->url($file), Storage::disk('uploads')->mimeType($file)];
            })->all(),
            Asset::all()->toArray()
        )], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->file('file')) {
            return response()->json(['message' => 'failed'], 500);
        }
        $path = [];
        if(is_array($request->file('file'))) {
            foreach ($request->file('file') as $key => $file) {
                $stored = $file->storeAs(
                    'public/uploads/',
                    $file->getClientOriginalName()
                );
                $path[] = Storage::url($stored);
                if ($request->project_id) {
                    $project = Project::find($request->project_id);
                    $project
                        ->addMedia(Storage::path($stored))
                        ->toMediaCollection();
                }
            }
        } else {
            $stored = $request->file('file')->storeAs(
                'public/uploads/',
                $request->file('file')->getClientOriginalName()
            );
            $path[] = Storage::url($stored);
            if ($request->project_id) {
                $project = Project::find($request->project_id);
                $collection = !empty($request->collection) ? $request->collection : 'default';
                $project
                    ->addMedia(Storage::path($stored))
                    ->toMediaCollection($collection);
            }
        }
        return response()->json(['message' => 'success', 'url' => $path], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \Spatie\MediaLibrary\Models\Media $asset
     * @return MediaItem
     */
    public function show(MediaItem $asset)
    {
        return $asset;
    }

    /**
     * Download the specified resource.
     *
     * @param  \Spatie\MediaLibrary\Models\Media  $asset
     * @return \Illuminate\Http\Response
     */
    public function download(MediaItem $asset)
    {
        return response()->download($asset->getPath(), $asset->file_name);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param MediaItem $asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MediaItem $asset)
    {
        if ($request['order']) {
            MediaItem::setNewOrder(json_decode($request['data']));
            $asset->model()->touch();
            return response()->json(['message' => 'success'], 200);
        }
        $asset->update($request->all());
        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Asset $asset
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($asset, Request $request)
    {
        $media = Media::find($asset);
        if (!$media) {
            $file = collect(Storage::disk('uploads')->allFiles())->filter(function ($value, $key) use ($asset) {
                return $asset === base64_encode(Storage::disk('uploads')->url($value));
            })->first();

            if (!$file) {
                return response()->json(['message' => 'failed'], 500);
            }

            Storage::disk('uploads')->delete($file);
            return response()->json(['message' => 'success'], 200);
        }
        $media->delete();
        return response()->json(['message' => 'success'], 200);
    }
}
