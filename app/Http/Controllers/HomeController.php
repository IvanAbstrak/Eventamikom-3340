<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Partner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data kategori dan partner untuk tampilan
        $categories = Category::all();
        $partners = Partner::all();

        // 2. Buat kueri dasar untuk mengambil event (Eager Loading)
        $query = Event::with('category')->where('date', '>=', now())->orderBy('date', 'asc');

        // 3. Logika Filter jika pengunjung memilih tab kategori tertentu
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Eksekusi pencarian data
        $events = $query->get();

        // 5. Kirim SEMUA data ($events, $categories, $partners) ke welcome.blade.php
        return view('welcome', compact('events', 'categories', 'partners'));
    }
}
