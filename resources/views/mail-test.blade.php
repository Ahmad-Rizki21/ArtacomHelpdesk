<!-- resources/views/mail-test.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengujian Email Notifikasi Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="my-2">Pengujian Email Notifikasi Tiket</h3>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('mail-test.send') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="ticket_id" class="form-label">Pilih Tiket</label>
                                <select name="ticket_id" id="ticket_id" class="form-select @error('ticket_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tiket --</option>
                                    @foreach ($tickets as $ticket)
                                        <option value="{{ $ticket->id }}" @selected(old('ticket_id') == $ticket->id)>
                                            {{ $ticket->ticket_number }} - {{ $ticket->problem_summary }} ({{ $ticket->customer->composite_data ?? 'Tanpa Pelanggan' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('ticket_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email Tujuan</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" placeholder="teknisi@contoh.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Email akan dikirim ke alamat ini untuk pengujian. Gunakan alamat Mailtrap untuk testing.
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Kirim Email Pengujian</button>
                                <a href="{{ route('filament.admin.pages.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        Informasi Konfigurasi Email
                    </div>
                    <div class="card-body">
                        <p><strong>Mail Driver:</strong> {{ config('mail.default') }}</p>
                        <p><strong>Mail Host:</strong> {{ config('mail.mailers.smtp.host') }}</p>
                        <p><strong>Mail Port:</strong> {{ config('mail.mailers.smtp.port') }}</p>
                        <p><strong>Mail From:</strong> {{ config('mail.from.address') }}</p>
                        <p><strong>Mail Name:</strong> {{ config('mail.from.name') }}</p>
                        <p><strong>Queue Connection:</strong> {{ config('queue.default') }}</p>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header bg-success text-white">
                        Panduan Troubleshooting
                    </div>
                    <div class="card-body">
                        <h5>Jika Email Tidak Terkirim:</h5>
                        <ol>
                            <li>Periksa konfigurasi <code>.env</code> dan pastikan kredensial Mailtrap sudah benar</li>
                            <li>Periksa log Laravel di <code>storage/logs/laravel.log</code> untuk melihat pesan error detail</li>
                            <li>Pastikan akun Mailtrap masih aktif dan memiliki kuota</li>
                            <li>Coba restart server Laravel dengan <code>php artisan serve</code></li>
                            <li>Jika menggunakan queue, jalankan worker dengan <code>php artisan queue:work</code></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>