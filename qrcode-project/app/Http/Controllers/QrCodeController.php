<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeController extends Controller
{
    public function index()
    {
        // Mengambil seluruh riwayat QR Code terbaru untuk ditampilkan di halaman index
        $histories = QrCode::latest()->get();

        return view('admin.pages.qrcode.index', compact('histories'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'link' => 'required|url',
        ]);

        $uid = (string) time();
        $imageName = $uid . '.svg';
        $imagePath = 'qr/' . $imageName;

        // 1. Generate gambar QR Code format SVG (tanpa butuh imagick)
        $qrImage = QrCodeGenerator::format('svg')
            ->size(250)
            ->margin(1)
            ->generate($request->link);

        // 2. Simpan file ke storage/app/public/qr/
        Storage::disk('public')->put($imagePath, (string) $qrImage);

        // 3. Simpan riwayat ke database MySQL
        QrCode::create([
            'uid' => $uid,
            'link' => $request->link,
            'image_path' => $imagePath,
        ]);

        return view('admin.pages.qrcode.scanner', compact('uid'));
    }

    public function destroy(QrCode $qrCode)
    {
        if ($qrCode->image_path && Storage::disk('public')->exists($qrCode->image_path)) {
            Storage::disk('public')->delete($qrCode->image_path);
        }

        $qrCode->delete();

        return redirect()
            ->route('qrcode.index')
            ->with('success', 'Riwayat QR Code berhasil dihapus.');
    }
}