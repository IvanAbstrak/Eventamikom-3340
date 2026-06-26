@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran')

@section('content')
<main class="max-w-2xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-3xl border border-slate-200 p-12 shadow-sm">
        <h2 class="text-2xl font-black mb-2">Selesaikan Pembayaran</h2>
        <p class="text-slate-500 mb-8">Selesaikan pembayaran untuk mengamankan tiket Anda.</p>

        <div class="bg-slate-50 p-6 rounded-2xl mb-8 text-left">
            <p class="text-sm text-slate-500">Order ID</p>
            <p class="font-bold mb-4">{{ $transaction->order_id }}</p>

            <p class="text-sm text-slate-500">Acara</p>
            <p class="font-bold mb-4">{{ $event->title }}</p>

            <p class="text-sm text-slate-500">Total Tagihan</p>
            <p class="font-bold text-xl text-indigo-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
        </div>

        <button id="pay-button" class="w-full px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
            Bayar Sekarang
        </button>
    </div>
</main>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        // Memanggil fungsi Snap dari Midtrans
        window.snap.pay('{{ $snapToken }}', {

            // Aksi jika pelanggan sukses membayar
            onSuccess: function (result) {
                // Arahkan ke halaman sukses yang sudah kita buat sebelumnya!
                window.location.href = "{{ route('checkout.success', $transaction->order_id) }}";
            },

            // Aksi jika pelanggan menutup jendela tanpa membayar
            onPending: function (result) {
                alert("Menunggu pembayaran Anda!");
            },

            // Aksi jika pembayaran gagal (kartu ditolak, dsb)
            onError: function (result) {
                alert("Pembayaran gagal!");
            },

            // Aksi jika pelanggan menekan tombol X (close)
            onClose: function () {
                alert("Anda menutup jendela pembayaran tanpa menyelesaikan transaksi.");
            }
        });
    };
</script>
@endsection
