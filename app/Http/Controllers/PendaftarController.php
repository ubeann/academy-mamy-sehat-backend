<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Acara;
use App\Models\Pendaftar;
use Xendit\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\InvoiceItem;
use Illuminate\Support\Facades\Storage;
use Xendit\Invoice\CreateInvoiceRequest;

class PendaftarController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }
    
    /**
     * Store a newly created registration in storage.
        */
        public function index()
        {
            $pendaftars = Pendaftar::with('user', 'acara')->get(); // Muat data user dan acara
    
            return response()->json([
                'status' => 'success',
                'data' => $pendaftars,
            ], 200);
        }
    
    
        public function show($id)
        {
            $pendaftar = Pendaftar::with('user', 'acara')->find($id); // Muat data user dan acara
    
            if (!$pendaftar) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pendaftar tidak ada',
                ], 404);
            }
    
            return response()->json([
                'status' => 'success',
                'data' => $pendaftar,
            ], 200);
        }

        public function indexByAcara($acaraId)
        {
            // Validasi ID acara
            $acara = Acara::find($acaraId);
    
            if (!$acara) {
                return response()->json(['message' => 'Acara tidak ditemukan'], 404);
            }
    
            // Ambil semua pendaftar untuk acara yang diberikan
            $pendaftarList = Pendaftar::where('acara_id', $acaraId)
                ->with('user') // Optional: Untuk menyertakan data user jika perlu
                ->get();
    
            // Hitung jumlah pendaftar
            $jumlahPendaftar = $pendaftarList->count();
    
            // Gabungkan nama acara dan jumlah pendaftar dengan data pendaftar
            $response = [
                'acara' => [
                    'id' => $acara->id,
                    'nama_acara' => $acara->nama_acara,
                    'jumlah_pendaftar' => $jumlahPendaftar
                ],
                'pendaftar' => $pendaftarList
            ];
    
            return response()->json($response, 200);
        }
    


        public function store(Request $request)
        {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'acara_id' => 'required|exists:acaras,id',
            ]);
    
            // Ambil data acara
            $acara = Acara::find($request->acara_id);
    
            if (!$acara) {
                return response()->json(['message' => 'Acara tidak ditemukan'], 404);
            }
    
            // Cek apakah acara aktif dan jika waktu saat ini sebelum tgl_ditutup
            $now = now(); // Mendapatkan waktu saat ini
            if ($acara->status !== 'aktif' || ($acara->tgl_ditutup && $now->greaterThan($acara->tgl_ditutup))) {
                return response()->json(['message' => 'Acara tidak aktif atau sudah ditutup'], 403);
            }
    
            $existingPendaftar = Pendaftar::where('user_id', $request->user_id)
            ->where('acara_id', $request->acara_id)
            ->where('status', 'terbayar')
            ->first();
        
        if ($existingPendaftar) {
            return response()->json(['message' => 'User sudah terdaftar pada acara ini'], 409);
        }
        

            $bayar = 0;
            if($acara->tgl_early> Carbon::now()){
                $bayar = $acara->harga_early;
            }else {
                $bayar = $acara->harga_reguler;
            }
        
            
            $items = new InvoiceItem([
                'name' => $acara->nama_acara,
                'price' => $bayar,
                'quantity' => 1
            ]);

            $external_id =  'Inv - ' . rand();

            $invoice = new CreateInvoiceRequest([
                'external_id' => $external_id,
                'amount' => $bayar,
                'invoice_duration' =>172800,
                'items' => array($items)
            ]);
    
            $apiInstance = new InvoiceApi();
            $generateInvoice = $apiInstance->createInvoice($invoice);
            $paymentUrl = $generateInvoice['invoice_url'];
            

    
            $pendaftar = Pendaftar::create([
                'user_id' => $request->user_id,
                'acara_id' => $request->acara_id,
                'jumlah_bayar' => $bayar,
                'status' => 'belum_bayar',
                'external_id' => $external_id
            
            ]);
    
            return response()->json(['message' => 'Registration created successfully', 'pendaftar' => $pendaftar,'payment_url' => $paymentUrl], 201);
        }
    

    public function konfirmasiBayar($id){
        // Temukan pendaftar berdasarkan ID
        $pendaftar = Pendaftar::find($id);

        // Cek apakah pendaftar ditemukan
        if (!$pendaftar) {
            return response()->json(['message' => 'Pendaftar tidak ditemukan'], 404);
        }

        // Update status pendaftar
        $pendaftar->update(['konfirmasi_bayar' => 'sudah_terbayar']);

        return response()->json(['message' => 'Status pendaftar berhasil diubah menjadi sudah_terbayar', 'pendaftar' => $pendaftar], 200);
    }

    

    /**
     * Update the specified registration in storage.
     */
    public function update(Request $request, Pendaftar $pendaftar)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'acara_id' => 'required|exists:acaras,id',
            'konfirmasi_bayar' => 'required|string',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        // Upload bukti jika ada
        if ($request->hasFile('bukti')) {
            // Hapus bukti lama jika ada
            if ($pendaftar->bukti) {
                Storage::disk('public')->delete($pendaftar->bukti);
            }

            $buktiPath = $request->file('bukti')->store('bukti_pendaftaran', 'public');
        } else {
            $buktiPath = $pendaftar->bukti;
        }

        $pendaftar->update([
            'user_id' => $request->user_id,
            'acara_id' => $request->acara_id,
            'konfirmasi_bayar' => $request->konfirmasi_bayar,
            'bukti' => $buktiPath,
        ]);

        return response()->json(['message' => 'Registration updated successfully', 'pendaftar' => $pendaftar], 200);
    }

    /**
     * Remove the specified registration from storage.
     */
    public function destroy(Pendaftar $pendaftar)
    {
        // Hapus bukti dari storage jika ada
        if ($pendaftar->bukti) {
            Storage::disk('public')->delete($pendaftar->bukti);
        }

        $pendaftar->delete();
        return response()->json(['message' => 'Registration deleted successfully'], 200);
    }

    public function notificationCallback(Request $request){
        $getToken = $request->headers->get('x-callback-token');
        $callbackToken = env('XENDIT_CALLBACK_TOKEN');

        if($getToken !== $callbackToken){
            return response()->json([
                'status' => 'Error',
                'pesan' => 'Token Invalid',
            ]);
        }


        $pendaftar = Pendaftar::where('external_id',$request->external_id)->first();
        
        if($pendaftar){
            if($request->status == 'PAID'){
                $pendaftar->update([
                    'status' => 'terbayar'
                ]);
                


    
            }
        }
        return response()->json([
            'status' => 'terbayarkan',
            [$pendaftar]
            
        ]);


    }
}
