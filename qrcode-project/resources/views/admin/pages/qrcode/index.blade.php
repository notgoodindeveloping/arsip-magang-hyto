<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code - Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Notifikasi sukses saat data berhasil dihapus --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Bagian 1: Form Generate QR Code --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">QR Code Generator</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('qrcode.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="link" class="form-label fw-semibold">Masukkan URL / Link Website</label>
                            <input 
                                type="text" 
                                name="link" 
                                id="link" 
                                class="form-control @error('link') is-invalid @enderror" 
                                placeholder="masukkan link website di sini" 
                                value="{{ old('link') }}"
                                required
                            >
                            
                            @error('link')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Generate QR Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bagian 2: Tabel Riwayat QR Code & Tombol Unduh / Hapus --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Generate QR Code</h5>
                    <span class="badge bg-secondary">Total: {{ $histories->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 60px;">No</th>
                                    <th style="width: 100px;">QR Code</th>
                                    <th>Link / URL</th>
                                    <th style="width: 180px;">Waktu Dibuat</th>
                                    <th class="text-end pe-4" style="width: 180px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($histories as $item)
                                    <tr>
                                        <td class="ps-4">{{ $loop->iteration }}</td>
                                        <td>
                                            <img 
                                                src="{{ asset('storage/' . $item->image_path) }}" 
                                                alt="QR {{ $item->uid }}" 
                                                class="img-thumbnail" 
                                                style="width: 64px; height: 64px; object-fit: contain;"
                                            >
                                        </td>
                                        <td>
                                            <a href="{{ $item->link }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-break">
                                                {{ $item->link }}
                                            </a>
                                            <div class="text-muted small">ID: <code>{{ $item->uid }}</code></div>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $item->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-inline-flex gap-1">
                                                <a 
                                                    href="{{ asset('storage/' . $item->image_path) }}" 
                                                    download="qr-{{ $item->uid }}.svg" 
                                                    class="btn btn-sm btn-outline-primary"
                                                >
                                                    Unduh
                                                </a>

                                                <form 
                                                    action="{{ route('qrcode.destroy', $item->id) }}" 
                                                    method="POST" 
                                                    onsubmit="return confirm('Yakin ingin menghapus riwayat QR Code ini?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            Belum ada riwayat QR Code yang tersimpan di database.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>