<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show($id)
    {
        // 1. Ambil data event berdasarkan ID, termasuk relasi kategorinya
        $event = Event::with('category')->findOrFail($id);

        // 2. Kirim data $event ke view event-detail
        return view('event-detail', compact('event'));
    }
}
