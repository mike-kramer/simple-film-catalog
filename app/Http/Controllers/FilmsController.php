<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveFilmRequest;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Support\Facades\Storage;

class FilmsController extends Controller
{
    public function saveFilm(Film $film = null, SaveFilmRequest $request)
    {
        $film ??= new Film();
        $film->fill($request->all());
        if ($request->hasFile("cover")) {
            $cover = $request->file("cover");
            if ($film->cover) {
                Storage::disk("public")->delete($film->cover);
            }
            $film->cover = $cover->store("covers", "public");
        }
        if ($request->hasFile("torrent")) {
            $cover = $request->file("torrent");
            if ($film->torrent_file) {
                Storage::disk("public")->delete($film->torrent_file);
            }
            $film->torrent_file = $cover->store("torrents", "public");
        }

        $film->save();

        if ($request->has("genres_ids")) {
            $film->genres()->sync($request->get("genres_ids"));
        }

        return ["id" => $film->id];

    }
}
