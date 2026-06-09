<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiServiceController extends Controller
{
    // Format service untuk response
    private function formatService($item)
    {
        return [
            'id'            => $item->id,
            'nama'          => $item->name,
            'slug'          => Str::slug($item->name),
            'tipe'          => $item->type,
            'alamat'        => $item->location,
            'jarak'         => $item->distance,
            'harga'         => (float) $item->price,
            'rating'        => (float) ($item->rating ?? 0),
            'total_review'  => $item->reviews_count ?? 0,
            'foto_urls'     => $item->image ? [$item->image] : [],
            'tersedia'      => true,
            'mitra_id'      => $item->user_id,
            'mitra_nama'    => $item->user->name ?? '',
            // Kos specific
            'tipe_kos'      => $item->gender,
            'fasilitas'     => $item->features ?? [],
            'ukuran_kamar'  => $item->room_size,
            'listrik'       => $item->electricity,
            'air'           => $item->water,
            // Catering specific
            'menu_contoh'   => $item->extra_info ?? [],
            'jadwal'        => $item->schedule,
            // Laundry specific
            'layanan'       => $item->features ?? [],
            'estimasi_hari' => 2,
            'harga_per_kg'  => (float) $item->price,
        ];
    }

    public function kos(Request $request)
    {
        $query = Service::with('user')->where('type', 'kos');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('location', 'like', "%{$request->search}%");
        }

        if ($request->tipe) {
            $query->where('gender', $request->tipe);
        }

        if ($request->max_harga) {
            $query->where('price', '<=', $request->max_harga);
        }

        $items = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $items->map(fn($i) => $this->formatService($i)),
        ]);
    }

    public function catering(Request $request)
    {
        $query = Service::with('user')->where('type', 'katering');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return response()->json([
            'success' => true,
            'data'    => $query->latest()->get()->map(fn($i) => $this->formatService($i)),
        ]);
    }

    public function laundry(Request $request)
    {
        $query = Service::with('user')->where('type', 'laundry');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return response()->json([
            'success' => true,
            'data'    => $query->latest()->get()->map(fn($i) => $this->formatService($i)),
        ]);
    }

    public function paket()
    {
        $kosList      = Service::with('user')->where('type', 'kos')->get();
        $cateringList = Service::with('user')->where('type', 'katering')->get();
        $laundryList  = Service::with('user')->where('type', 'laundry')->get();

        $paket = [];

        // Paket Kenyang (Kos + Catering)
        if ($kosList->count() > 0 && $cateringList->count() > 0) {
            $kos      = $kosList->first();
            $catering = $cateringList->first();
            $normal   = $kos->price + $catering->price;

            $paket[] = [
                'id'            => 'paket-kenyang-1',
                'tipe'          => 'kenyang',
                'nama'          => 'Paket Kenyang',
                'deskripsi'     => 'Kos nyaman + catering lezat setiap hari',
                'deskripsi_layanan' => 'Kos + Catering',
                'kos'           => $this->formatService($kos),
                'catering'      => $this->formatService($catering),
                'laundry'       => null,
                'harga_normal'  => (float) $normal,
                'harga_paket'   => (float) ($normal * 0.9),
                'diskon_persen' => 10,
            ];
        }

        // Paket Bersih (Kos + Laundry)
        if ($kosList->count() > 1 && $laundryList->count() > 0) {
            $kos     = $kosList->skip(1)->first();
            $laundry = $laundryList->first();
            $normal  = $kos->price + $laundry->price;

            $paket[] = [
                'id'            => 'paket-bersih-1',
                'tipe'          => 'bersih',
                'nama'          => 'Paket Bersih',
                'deskripsi'     => 'Kos nyaman + laundry bersih tanpa repot',
                'deskripsi_layanan' => 'Kos + Laundry',
                'kos'           => $this->formatService($kos),
                'catering'      => null,
                'laundry'       => $this->formatService($laundry),
                'harga_normal'  => (float) $normal,
                'harga_paket'   => (float) ($normal * 0.9),
                'diskon_persen' => 10,
            ];
        }

        // Paket Lengkap (Kos + Catering + Laundry)
        if ($kosList->count() > 0 && $cateringList->count() > 0 && $laundryList->count() > 0) {
            $kos      = $kosList->last();
            $catering = $cateringList->last();
            $laundry  = $laundryList->last();
            $normal   = $kos->price + $catering->price + $laundry->price;

            $paket[] = [
                'id'            => 'paket-lengkap-1',
                'tipe'          => 'lengkap',
                'nama'          => 'Paket Lengkap',
                'deskripsi'     => 'Semua kebutuhan kos dalam satu paket hemat',
                'deskripsi_layanan' => 'Kos + Catering + Laundry',
                'kos'           => $this->formatService($kos),
                'catering'      => $this->formatService($catering),
                'laundry'       => $this->formatService($laundry),
                'harga_normal'  => (float) $normal,
                'harga_paket'   => (float) ($normal * 0.85),
                'diskon_persen' => 15,
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $paket,
        ]);
    }

    public function detail($type, $slug)
    {
        $item = Service::with('user')
            ->where('type', $type)
            ->get()
            ->first(fn($s) => Str::slug($s->name) === $slug);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->formatService($item),
        ]);
    }
}
