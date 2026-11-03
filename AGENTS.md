# AGENTS.md — Lapangin

Dokumen ini dibaca sebelum mengerjakan apa pun di repository ini. Baca seluruhnya.

## 0. Status Fase
Fase 1 sedang berjalan: setup auth dan role. Kode aplikasi hanya untuk cakupan fase 1.
Lihat bagian 6 untuk daftar fitur yang boleh dan tidak boleh dikerjakan di fase ini.

## 1. Tujuan
Lapangin adalah aplikasi web booking lapangan olahraga (futsal dan badminton). Customer mencari venue, memilih lapangan dan jadwal, lalu membayar online lewat payment gateway atau di lokasi. Admin mengelola venue, lapangan, booking, dan pembayaran.
Ini tugas akhir mata kuliah PBO2, dengan fokus relasi tabel database dan Eloquent ORM.

## 2. Stack
- Laravel (versi di `composer.json`: `laravel/framework ^13.17`, PHP `^8.3`)
- Autentikasi: Laravel Breeze, dengan middleware role
- Otorisasi: Policy (customer hanya bisa mengakses booking miliknya)
- Validasi: Form Request
- Logika bisnis: Service class, controller tipis
- Frontend: Blade + Tailwind CSS. Livewire belum diputuskan.
- Pembayaran: payment gateway (Midtrans atau Xendit, belum diputuskan). Metode: QRIS, VA, e-wallet.
- Queue dan scheduler: webhook, auto-cancel, auto-complete, rekonsiliasi
- Database: sesuai `.env` (default `.env.example`: sqlite)

## 3. Peran
- `customer`: cari venue, booking, bayar, lihat riwayat, batalkan, beri review
- `admin`: kelola venue dan court, kelola booking, catat pembayaran di lokasi, verifikasi pembayaran, moderasi review, kelola user, lihat laporan
Publik (tanpa login): lihat daftar dan detail venue, registrasi, login.

## 4. Keputusan Desain (sudah final)

a. Status jadwal dan status uang dipisah.
- `bookings.status`: `pending`, `confirmed`, `cancelled`, `completed`
- `bookings.payment_status`: `unpaid`, `partially_paid`, `paid`, `refunded`
- Jangan digabung dalam satu kolom.

b. `payments` adalah one-to-many terhadap `bookings`. Jangan pasang unique pada `payments.booking_id`.
- `payment_type`: `dp`, `full`, `remaining`
- `channel`: `online`, `onsite`
- `payment_method`: `qris`, `va`, `ewallet`, `cash_onsite`, `transfer_onsite`
- `status`: `pending`, `paid`, `failed`, `expired`, `refunded`
- Kolom gateway: `gateway`, `gateway_order_id`, `gateway_transaction_id`, `gateway_payload` (JSON, jejak audit), `expired_at`
- `payment_code` dibuat otomatis. Formatnya belum diputuskan.

c. Opsi pembayaran per booking: `bookings.payment_option` = `full_online`, `dp`, `onsite`.
- Kolom turunan: `dp_amount`, `paid_amount`, `remaining_amount`.
- Sumber kebenaran: `total_price` dikurangi total pembayaran berstatus `paid`.

d. Pengaturan per venue (kolom di `venues`):
- `allow_full_online`, `allow_dp`, `allow_onsite` (boolean)
- `dp_type` (persen atau nominal), `dp_value`

e. Snapshot harga: `bookings.price_per_hour` disalin dari `courts.price_per_hour` saat booking dibuat. `total_price = price_per_hour × duration`. Perubahan harga court tidak mengubah booking lama.

f. Anti double-booking:
- Bentrok jika `start_time < end_time_lama` AND `end_time > start_time_lama`, untuk court dan tanggal yang sama, hanya pada booking `pending` atau `confirmed`.
- Cek dan buat booking dalam `DB::transaction()` dengan row locking.

g. Keamanan pembayaran:
- Nominal selalu dihitung ulang di server dari database. Nilai dari browser tidak dipercaya.
- Status booking hanya berubah lewat webhook gateway yang signature-nya sudah diverifikasi, bukan dari redirect.
- Webhook idempotent: notifikasi yang sama dua kali tidak menggandakan pembayaran. Proses lewat queue.

h. Aturan waktu:
- Slot ditahan 15 menit untuk pembayaran online. Lewat itu, booking dibatalkan otomatis.
- Booking harus di dalam jam buka venue, tidak di masa lalu, dan court berstatus `available`.
- Review hanya untuk booking `completed`, satu kali per booking.

i. Kode otomatis:
- `booking_code` format `BK-YYYYMMDD-NNNN`, contoh `BK-20261103-0001`.

j. Cascade:
- Hapus venue dibatasi selama masih ada court.
- Hapus court dibatasi selama masih ada booking.
- Hapus booking ikut menghapus review dan status history. Payment tidak ikut dihapus.

k. Gateway: Midtrans.
l. Default pembayaran venue: `allow_full_online = true`, `allow_dp = false`, `allow_onsite = false`, `dp_type = percent`, `dp_value = 30`.
m. Booking bayar penuh di lokasi tanpa DP: status langsung `confirmed`, tanpa persetujuan admin.
n. Autentikasi dan notifikasi:
- Fase 1: login memakai **nomor HP + password** (nomor HP sebagai identitas login).
- Email dipakai untuk **notifikasi** (konfirmasi booking, pengingat, dan pemberitahuan lain). Email tidak dipakai untuk login.
- Tahap berikutnya (belum dikerjakan): **verifikasi OTP** yang dikirim ke WhatsApp setiap login. Provider belum dipilih (misalnya Fonnte atau Wablas).
- Alasan: nomor HP lebih sulit dibuat asal-asalan daripada email, sehingga mendukung pemblokiran akun no-show.

## 5. Hal yang Belum Diputuskan (jangan diputuskan sendiri)
1. Kebijakan pembatalan dan refund DP
2. Biaya gateway: ditanggung venue atau customer
3. Livewire atau Blade murni untuk halaman jadwal
4. Format `payment_code`
5. Bukti transfer, foto venue dan court, alasan pembatalan, jadwal khusus, fasilitas venue. Belum masuk database, jangan ditambahkan tanpa persetujuan.
6. Provider OTP WhatsApp (Fonnte atau Wablas): belum dipilih, belum boleh dikerjakan di fase ini.
7. Pemblokiran akun no-show: keputusan pemilik, sudah diterima tapi belum dirancang (butuh field dan aturan pemicu). Belum boleh dikerjakan.

Jika ada keputusan dari pemilik project, pindahkan ke bagian 4 dan hapus dari daftar ini.

## 6. Cakupan Fase 1
Boleh: setup Breeze dan middleware role, migration dan model sesuai bagian 4, CRUD venue dan court (admin), daftar dan detail venue (publik), booking dengan pencegahan bentrok, integrasi gateway satu metode (QRIS), webhook terverifikasi dan idempotent, auto-cancel, pembayaran onsite yang dicatat admin, riwayat booking customer.
Tidak boleh (di luar fase 1): DP penuh, refund, rekonsiliasi, notifikasi email atau WhatsApp, dashboard admin, review, laporan dan ekspor, upload foto, booking manual admin, moderasi review, kelola user (kecuali yang dibutuhkan auth).

## 7. Aturan Kerja
- Sebelum coding, baca `composer.json`, `.env.example`, `routes/`, dan migration yang ada. Jangan mengasumsikan isi file yang belum dibaca.
- Kerjakan per langkah kecil, satu fitur per iterasi. Setelah tiap langkah, sebutkan file yang berubah dan alasannya.
- Jangan menambah fitur, kolom, atau tabel di luar dokumen ini tanpa bertanya.
- Tulis test untuk bentrok jadwal, perhitungan harga, dan validasi webhook.
- Kredensial gateway hanya di `.env`, jangan di kode.
- Jangan menyalin kode dari repository lain, termasuk milik teman.
- Perubahan `AGENTS.md` ditampilkan sebagai diff dan menunggu persetujuan.
- Git: kerja di `feature/lapangin`. Format commit `docs:`, `feat:`, `fix:`, `refactor:`, `test:`, `chore:`. Jangan push ke `main`. Jangan `reset --hard` atau `push --force` tanpa konfirmasi.
- Komunikasi dengan pemilik project dalam Bahasa Indonesia.

## 8. Verifikasi Pemahaman
Sebelum mulai kerja di sesi baru, jawab:
1. Apa fase aktif dan apa yang belum boleh dikerjakan?
2. Apa beda `bookings.status` dan `bookings.payment_status`?
3. Kenapa `payments` one-to-many, dan bagaimana webhook ganda dicegah?
