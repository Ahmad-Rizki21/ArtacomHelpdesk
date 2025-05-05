
<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://filamentphp.com/" target="_blank">
    <img src="https://www.fedrianto.com/content/images/2022/11/131910226-676cb28a-332d-4162-a6a8-136a93d5a70f.png" width="280" alt="Filament Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

---

## 📌 Tentang Proyek

Proyek ini dibangun menggunakan **Laravel 11** dan **Filament v3**, bertujuan untuk mengelola sistem **Helpdesk Ticketing** khususnya layanan **FTTH (Fiber to the Home)**.

Sistem ini dilengkapi dengan fitur:
- Perhitungan **SLA (Service Level Agreement)** secara akurat
- Laporan statistik SLA bulanan
- Dashboard admin dan ekspor laporan ke Excel

---

## ⚙️ Teknologi yang Digunakan

- PHP 8.2+
- Laravel 11
- Filament v3
- MariaDB

---

## 🎯 Fitur Utama

- Antarmuka admin modern berbasis Filament
- Otomatisasi perhitungan uptime dan SLA tiket
- Statistik SLA bulanan dan laporan terintegrasi
- Ekspor laporan ke Excel dengan format informatif
- Scope query khusus untuk analisis SLA
- Dashboard siap dikembangkan untuk real-time monitoring

---

## 📈 Implementasi SLA

### 🎯 Target SLA: 99,5%

#### ✅ Konstanta
```php
const TARGET_UPTIME_PERCENTAGE = 99.5;
```

#### 🧮 Fungsi Perhitungan SLA
- `calculateTotalTimeInMonth()` → total menit dalam bulan berjalan
- `calculateAllowedDowntimeInMonth()` → downtime maksimum yang diizinkan
- `calculateResolutionTime()` → waktu resolusi dikurangi waktu pending
- `calculateUptimePercentage()` → persentase uptime aktual

#### 📊 Atribut Baru di Model `Ticket`
- `uptime_percentage`
- `sla_status` (Memenuhi / Melebihi SLA)
- `resolution_time` (format mudah dibaca)
- `allowed_downtime`
- `duration_in_days`

#### 🔍 Scope Query Baru
- `scopeMeetingSla()` → tiket sesuai SLA
- `scopeExceedingSla()` → tiket melebihi SLA
- `scopeInMonth()` → filter berdasarkan bulan
- `scopeFtth()` → filter berdasarkan layanan FTTH

#### 📤 Ekspor Excel
- Ringkasan statistik SLA
- Kolom tambahan:
  - Durasi (dd hh)
  - Uptime Percentage
  - Allowed Downtime
  - SLA Status
- Styling agar laporan lebih informatif

---

## 📚 Referensi Pembelajaran

- 📖 [Laravel Documentation](https://laravel.com/docs)
- 🚀 [Laravel Bootcamp](https://bootcamp.laravel.com)
- 🎥 [Laracasts](https://laracasts.com)

---

## 🤝 Berkontribusi

Terima kasih atas minat Anda! Lihat panduan kontribusi di [dokumentasi Laravel](https://laravel.com/docs/contributions).

### 🧭 Kode Etik
Mohon untuk mematuhi [Kode Etik Laravel](https://laravel.com/docs/contributions#code-of-conduct) demi menjaga komunitas yang inklusif dan profesional.

---

## 🔐 Keamanan

Jika menemukan celah keamanan, harap hubungi langsung:
📧 [taylor@laravel.com](mailto:taylor@laravel.com)

Semua laporan akan ditangani secara rahasia dan prioritas tinggi.

---

## ⚖️ Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).

---

## 🚧 Rencana Pengembangan

- Dashboard real-time untuk SLA monitoring
- Notifikasi otomatis ketika tiket hampir melewati batas SLA
- Integrasi sistem pelaporan untuk analisis tren dan performa layanan

---
