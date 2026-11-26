<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil QR Code & Scanner - Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center g-4">
        
        {{-- Kolom Kiri: Hasil Generate QR Code (jika ada $uid) --}}
        @isset($uid)
        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Hasil QR Code</h5>
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-center align-items-center p-4">
                    <img 
                        src="{{ asset('storage/qr/' . $uid . '.svg') }}" 
                        alt="Generated QR Code" 
                        class="img-fluid border p-2 rounded mb-3"
                        style="max-width: 240px;"
                    >
                    <p class="text-muted small mb-3">File: <code>{{ $uid }}.svg</code></p>
                    <div class="d-flex gap-2">
                        <a 
                            href="{{ asset('storage/qr/' . $uid . '.svg') }}" 
                            download="qr-{{ $uid }}.svg" 
                            class="btn btn-success btn-sm"
                        >
                            Unduh QR
                        </a>
                        <a href="{{ route('qrcode.index') }}" class="btn btn-outline-secondary btn-sm">
                            &larr; Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endisset

        {{-- Kolom Kanan: Kamera PC Scanner --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">QR Code Scanner (Kamera PC)</h5>
                    @empty($uid)
                        <a href="{{ route('qrcode.index') }}" class="btn btn-sm btn-outline-light">&larr; Kembali</a>
                    @endempty
                </div>
                <div class="card-body p-4 text-center">
                    {{-- Area tampilan kamera PC --}}
                    <div id="reader" style="width: 100%;"></div>
                    <small class="text-muted d-block mt-2">
                        Arahkan QR Code dari layar HP atau kertas ke arah webcam PC.
                    </small>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Memuat library HTML5-QRCode --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan kamera setelah QR berhasil terbaca, lalu buka URL hasil scan
        html5QrcodeScanner.clear().then(() => {
            window.location.href = decodedText;
        }).catch(() => {
            window.location.href = decodedText;
        });
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { 
            fps: 10, 
            qrbox: { width: 220, height: 220 } 
        },
        false
    );

    html5QrcodeScanner.render(onScanSuccess);
</script>

</body>
</html>