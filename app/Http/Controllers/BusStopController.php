<?php

namespace App\Http\Controllers;

use App\Models\BusStop;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Inertia;
use Inertia\Response;

class BusStopController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('query');
        $bus_stops = BusStop::query()->when($query, function ($q, $query) {
            $q->where(fn($q) => $q->where('name', 'LIKE', '%' . strtoupper($query) . '%')
                ->orWhere('number', 'LIKE', '%' . strtoupper($query) . '%'));
        })->paginate(9);

        $bus_stops->setPath('/bus_stops');
        if ($request->wantsJson()) return JsonResource::make($bus_stops);

        return Inertia::render('BusStops', compact('bus_stops', 'query'));
    }
}
