<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = \App\Models\Category::all();
        return view('admin.checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        // 1. Validasi Input
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 2. Cegah Check-out Jika Tiket Habis
        if ($event->stock <= 0) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        // 3. Generate Kode TRX (Unik)
        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        $totalPrice = $event->price + 5000;

        // 4. Merekam Transaksi ke Database Lokal
        $transaction = Transaction::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
            'event_id' => $event->id,
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price' => $totalPrice,
            'status' => 'Pending',
        ]);

        $event->decrement('stock');

        // ==========================================
        // 5. BAGIAN YANG TERLEWAT: REQUEST SNAP TOKEN
        // ==========================================
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
        ];

        try {
            // Meminta token pembayaran dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Melemparkan token ini ke halaman pembayaran (view baru yang akan kita buat)
            return view('checkout.payment', compact('snapToken', 'transaction', 'event'));

        } catch (\Exception $e) {
            // Jika gagal terhubung ke Midtrans, kembalikan stok tiket dan batalkan pesanan
            $event->increment('stock');
            $transaction->delete();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }}
