<?php

namespace App\Http\Controllers;

use App\Models\Coop;
use Illuminate\Http\Request;

class FundCoopController extends Controller
{
    public function __invoke(Request $request, Coop $coop)
    {
        $attributes = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'package_quantity' => ['required', 'numeric', 'min:1'],
            'package_id' => ['required', 'integer', 'min:1'],
        ]);

        $attributes['buyer_id'] = $request->user()->id;

        $coop->purchases()->create($attributes);

        $request->session()->flash('success', 'Thanks for purchasing');

        return redirect()->route('coops.show', ['coop' => $coop]);
    }
}
