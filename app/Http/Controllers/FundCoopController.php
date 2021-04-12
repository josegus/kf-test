<?php

namespace App\Http\Controllers;

use App\Models\Coop;
use Illuminate\Http\Request;

class FundCoopController
{
    public function __invoke(Request $request, Coop $coop)
    {
        abort_unless($coop->isApproved(), 404);

        $attributes = $request->validate([
            'package_quantity' => ['required', 'numeric', 'min:1'],
            'package_id' => ['required', 'integer', 'min:1'],
        ]);

        // I'm hardcoding this, I supose this is stored in DB
        $packagePrice = 100;

        $attributes['amount'] = $packagePrice * $request->package_quantity;
        $attributes['buyer_id'] = auth()->user()->id;

        $coop->purchases()->create($attributes);

        flash()->success('Thanks for purchasing');

        return redirect()->route('coops.show', ['coop' => $coop]);
    }
}
