<?php

namespace App\Http\Controllers;

use App\Models\Artwork;

class SavedController extends Controller
{
    public function index()
    {
        $ids   = session('saved', []);
        $saved = $ids ? Artwork::whereIn('artwork_id', $ids)->get() : collect();

        return view('saved', compact('saved'));
    }

    public function toggle(Artwork $artwork)
    {
        $saved = session('saved', []);
        $key   = $artwork->artwork_id;

        if (in_array($key, $saved)) {
            $saved = array_values(array_filter($saved, fn ($id) => $id !== $key));
            $msg   = "\"{$artwork->title}\" removed from saved.";
        } else {
            $saved[] = $key;
            $msg     = "\"{$artwork->title}\" saved.";
        }

        session(['saved' => $saved]);

        return back()->with('success', $msg);
    }
}
