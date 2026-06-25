# Product Requirements Document (PRD)

## Nama Produk

**Asqi Apparel E-Commerce**

## Platform

Web Application (Responsive / Mobile-First)

## Niche

Pakaian & Perlengkapan Olahraga (Sportswear)

## Visi Desain

**Elegan, Dinamis, Premium**  
Inspirasi visual dan pengalaman pengguna mengacu pada standar e-commerce modern seperti Adidas.

---

# 1. Ringkasan Eksekutif

Asqi Apparel adalah platform e-commerce yang berfokus pada penjualan pakaian dan perlengkapan olahraga berkualitas tinggi.

Aplikasi ini dirancang tidak hanya untuk transaksi jual beli, tetapi juga memberikan pengalaman berbelanja premium melalui:

- Desain UI/UX yang elegan
- Animasi yang halus (fluid)
- Performa tinggi
- Keamanan tingkat lanjut
- SEO optimal
- Infrastruktur yang skalabel

Sistem dibangun mengikuti standar industri e-commerce modern untuk memastikan pengalaman pengguna yang cepat, aman, dan nyaman.

---

# 2. Strategi UI/UX dan Desain

## 2.1 Inspirasi Desain

Mengadopsi pendekatan visual seperti Adidas E-commerce dengan fokus pada:

- Produk sebagai pusat perhatian (product-centric)
- Visual yang bersih dan premium
- Penggunaan whitespace yang luas
- Navigasi yang intuitif
- Responsif di seluruh perangkat

---

## 2.2 Gaya Visual

### Tema

- Light Mode
- Dark Mode

### Tipografi

- Sans-serif modern
- Bold dan tegas
- Mudah dibaca pada semua ukuran layar

### Layout

- Grid modern
- Ruang kosong (whitespace) yang cukup
- Fokus pada visual produk

---

## 2.3 Animasi & Interaksi

### Smooth Page Transition

Perpindahan halaman tanpa loading screen yang terasa kaku.

### Hover Effects

Saat cursor berada di atas produk:

- Menampilkan sudut gambar lain
- Atau video loop pendek produk

### Micro-interactions

Contoh:

- Animasi tombol "Tambah ke Keranjang"
- Ikon keranjang bergerak saat item ditambahkan
- Skeleton loading saat data sedang dimuat

### Parallax Scrolling

Digunakan pada:

- Hero banner
- Halaman promosi
- Landing page campaign

---

## 2.4 Navigasi

### Mega Menu

Kategori utama:

- Pria
- Wanita
- Anak
- Jenis Olahraga

### Smart Search

Fitur:

- Autocomplete
- Typo Tolerance
- Live Product Preview
- Thumbnail Produk

### Breadcrumb

Contoh:

Home → Pria → Jersey → Jersey Running X1

---

## 2.5 Mobile First Design

### Mobile Optimization

- Touch-friendly UI
- Hamburger menu
- Swipe gallery
- Sticky CTA
- Bottom sheet interaction

### Target Responsive Resolution

| Device       | Width  |
| ------------ | ------ |
| Mobile Small | 360px  |
| Mobile Large | 480px  |
| Tablet       | 768px  |
| Laptop       | 1366px |
| Desktop      | 1920px |

---

# 3. Fitur Inti dan Infrastruktur Sistem

---

## 3.1 Katalog Produk

### Product Listing Page (PLP)

#### Fitur

##### Dynamic Filter

- Harga
- Ukuran
- Warna
- Jenis Olahraga
- Gender

##### Sorting

- Terbaru
- Harga Termurah
- Harga Tertinggi
- Terlaris
- Rating Tertinggi

##### Tampilan

- Grid produk
- Infinite scroll atau pagination
- Quick add to cart

---

### Product Detail Page (PDP)

#### Galeri Produk

- Multiple images
- HD resolution
- Zoom image
- Image magnifier

#### Informasi Produk

- Nama Produk
- Harga
- Deskripsi
- Bahan
- Teknologi kain olahraga
- Instruksi perawatan

#### Varian

- Ukuran
- Warna

#### Size Guide

Panduan ukuran interaktif.

#### Ulasan Pelanggan

- Rating bintang
- Foto pembeli
- Review teks

#### Cross Selling

Section:

**"Lengkapi Gayamu"**

Menampilkan produk terkait.

---

## 3.2 Manajemen Pesanan & Checkout

### Cart

Fitur:

- Update Quantity
- Hapus Produk
- Voucher Diskon
- Estimasi Ongkir

---

### Checkout

#### Guest Checkout

Pelanggan dapat berbelanja tanpa registrasi akun.

#### Checkout Flow

1. Keranjang
2. Pengiriman
3. Pembayaran
4. Konfirmasi

#### Progress Bar

Menampilkan status checkout secara visual.

#### Order Summary

##### Desktop

Sticky Sidebar

##### Mobile

Sticky Bottom Summary

---

### Feedback Sistem

#### Toast Notification

Contoh:

- Produk berhasil ditambahkan
- Voucher berhasil digunakan

#### Inline Validation

Pesan error muncul tepat di bawah field yang salah.

---

## 3.3 Sistem Pembayaran & Pengiriman

### Payment Gateway

Integrasi:

- Midtrans
- Xendit

### Metode Pembayaran

#### Transfer Bank (VA)

- BCA
- Mandiri
- BNI
- BRI

#### Kartu Kredit

- Visa
- Mastercard

#### E-Wallet

- GoPay
- OVO
- ShopeePay
- DANA

#### QRIS

Pembayaran lintas aplikasi.

---

### Pengiriman

Integrasi API:

- RajaOngkir

#### Perhitungan Otomatis

Berdasarkan:

- Berat produk
- Alamat tujuan

#### Opsi Pengiriman

- Reguler
- Next Day
- Same Day
- Instant

---

## 3.4 Manajemen Inventaris

### Stok Produk

- Sinkron otomatis
- Update real-time

### Pengurangan Stok

Terjadi saat pembayaran berhasil.

### Dashboard Admin

#### Monitoring Stok

- Total stok
- Produk habis
- Produk hampir habis

#### Low Stock Alert

Notifikasi otomatis untuk admin.

---

## 3.5 Layanan Pelanggan

### Live Chat

Floating button di kanan bawah.

#### Integrasi

- WhatsApp
- Sistem Ticketing Internal

### Customer Support

Fitur:

- Riwayat percakapan
- Status tiket
- SLA monitoring

---

# 4. Keamanan Sistem dan Privasi Data

## 4.1 Protokol Keamanan

### SSL/TLS

Seluruh website wajib menggunakan:

- HTTPS
- TLS terbaru

---

## 4.2 Enkripsi Data

### Password

Algoritma:

- Argon2
- bcrypt

### Data Sensitif

Dilindungi saat:

- In Transit
- At Rest

---

## 4.3 Keamanan Pembayaran

### PCI DSS

Kepatuhan ditangani oleh payment gateway.

### Tokenisasi

Menyimpan token pembayaran, bukan data kartu.

### 2FA / OTP

Digunakan untuk:

- Perubahan email
- Perubahan password
- Aktivitas sensitif akun

---

## 4.4 Keamanan Infrastruktur

### Proteksi

- Web Application Firewall (WAF)
- Anti SQL Injection
- Anti XSS
- Anti CSRF
- DDoS Protection

### Backup

- Auto Backup Harian
- Recovery Point Management

---

## 4.5 Privasi Pengguna

### Privacy Policy

Halaman khusus yang menjelaskan:

- Pengumpulan data
- Penggunaan data
- Hak pengguna

### Cookie Consent

Muncul saat pertama kali mengunjungi website.

---

# 5. SEO dan Analitik

## 5.1 SEO On-Page

### URL Structure

Contoh:

```text
asqiapparel.com/produk/sepatu-lari-x1
```

### Heading Hierarchy

- H1
- H2
- H3

### Dynamic Meta Tags

Per halaman:

- Meta Title
- Meta Description
- Open Graph

### Image Optimization

- Alt Text
- WebP
- Lazy Loading

### Performance Optimization

- Minify CSS
- Minify JavaScript
- CDN
- Browser Caching

---

## 5.2 Analitik dan Tracking

### Google Analytics 4 (GA4)

Tracking:

- Page View
- Session
- User Journey

### Meta Pixel

Tracking:

- View Content
- Add To Cart
- Initiate Checkout
- Purchase

---

## 5.3 Dashboard Reporting Admin

### KPI Utama

#### Penjualan

- Total Revenue
- Total Orders

#### Performa Toko

- Conversion Rate
- Average Order Value (AOV)

#### Perilaku Pengguna

- Cart Abandonment Rate
- Checkout Completion Rate

#### Produk

- Best Seller
- Low Performing Product

---

# 6. Kriteria Kesuksesan (Acceptance Criteria)

## Functional Requirements

### Katalog Produk

- [x] Katalog produk tampil dan dapat diakses.
- [x] Varian ukuran dan warna berfungsi dengan baik.

### Checkout

- [x] Checkout berjalan hingga simulasi pembayaran berhasil.
- [x] Guest Checkout dapat digunakan.

### Pengiriman

- [x] Kalkulasi ongkir otomatis aktif.

### Responsiveness

- [x] Tampilan responsif pada ukuran layar 360px–1920px.
- [x] Tidak terdapat cacat visual pada perangkat mobile maupun desktop.

### User Experience

- [x] Animasi berjalan halus tanpa menyebabkan lag.

### Security

- [x] SSL/HTTPS aktif dan valid.

### Reporting

- [x] Dashboard analitik dasar dapat diakses oleh admin.

---

# Lampiran: Teknologi yang Direkomendasikan

## Frontend

- Next.js
- React
- TypeScript
- Tailwind CSS
- Framer Motion

## Backend

- Laravel / NestJS
- REST API / GraphQL

## Database

- PostgreSQL

## Authentication

- JWT
- OAuth (Google Login)

## Payment Gateway

- Midtrans
- Xendit

## Shipping

- RajaOngkir

## Hosting & Infrastructure

- Vercel (Frontend)
- VPS / Cloud Server
- Cloudflare CDN
- Cloudflare WAF

## Analytics

- Google Analytics 4
- Meta Pixel

## Monitoring

- Sentry
- Uptime Robot

---

**Versi Dokumen:** 1.0  
**Tanggal:** Juni 2026  
**Status:** Draft Final
