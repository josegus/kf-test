<?php

namespace App\Http\Controllers;

use App\Models\Coop;

class CoopsController
{
    public function index()
    {
        return view('coops.index', [
            'coops' => Coop::inDraft()->paginate()
        ]);
    }

    public function show(Coop $coop)
    {
        abort_unless($coop->isDraft(), 404);

        return view('coops.show', ['coop' => $coop]);
    }
}
