{{-- resources/views/emails/ticket-assigned.blade.php --}}
@component('mail::message')
# Tiket Baru Ditugaskan: {{ $ticket->ticket_number }}

Halo {{ $technicianName }},

Sebuah tiket baru telah ditugaskan kepada Anda.

@component('mail::panel')
## Informasi Tiket

<table width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
  <tr>
    <td width="40%" style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">Nomor Tiket:</td>
    <td style="border-bottom: 1px solid #e2e8f0;">
      <span style="background-color: #1e40af; color: white; padding: 4px 8px; border-radius: 4px; font-family: monospace;">{{ $ticket->ticket_number }}</span>
    </td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">Teknisi yang Ditugaskan:</td>
    <td style="border-bottom: 1px solid #e2e8f0;">{{ $technicianName }}</td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">Pelanggan:</td>
    <td style="border-bottom: 1px solid #e2e8f0;">{{ $customerName }}</td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">Layanan:</td>
    <td style="border-bottom: 1px solid #e2e8f0;">{{ $ticket->service }}</td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">Ringkasan Masalah:</td>
    <td style="border-bottom: 1px solid #e2e8f0;">
      <span style="background-color: #0d6efd; color: white; padding: 4px 8px; border-radius: 4px;">{{ $ticket->problem_summary }}</span>
    </td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">Deskripsi Detail:</td>
    <td style="border-bottom: 1px solid #e2e8f0;">{{ $ticket->extra_description ?: 'Tidak ada deskripsi tambahan' }}</td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">Status:</td>
    <td style="border-bottom: 1px solid #e2e8f0;">
      <span style="background-color: {{ $statusColor }}; color: white; padding: 4px 8px; border-radius: 4px;">{{ $ticket->status }}</span>
    </td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">SLA:</td>
    <td style="border-bottom: 1px solid #e2e8f0;">
      <span style="background-color: {{ $slaColor }}; color: white; padding: 4px 8px; border-radius: 4px;">{{ $slaLevel }}</span>
    </td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">Dilaporkan Pada:</td>
    <td style="border-bottom: 1px solid #e2e8f0;">{{ $reportDate }}</td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid #e2e8f0; font-weight: bold;">Dibuat Oleh:</td>
    <td style="border-bottom: 1px solid #e2e8f0;">{{ $creatorName }}</td>
  </tr>
</table>
@endcomponent

@component('mail::panel')
## Informasi Penting
Harap segera menindaklanjuti tiket ini sesuai dengan tingkat prioritas SLA. Tiket dengan SLA **{{ $slaLevel }}** harus ditangani dengan prioritas tinggi.
@endcomponent

@component('mail::panel')
## Langkah Selanjutnya
1. Periksa detail tiket dengan seksama
2. Hubungi pelanggan untuk konfirmasi masalah
3. Lakukan troubleshooting sesuai prosedur
4. Update progress tiket di sistem secara berkala
5. Setelah selesai, tutup tiket dengan solusi yang lengkap
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Lihat Tiket
@endcomponent

Terima kasih atas kerja keras Anda dalam memberikan layanan terbaik kepada pelanggan kami.

Salam,<br>
Tim NOC {{ config('app.name') }}
@endcomponent