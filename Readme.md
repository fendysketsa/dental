# C-MORE Web

## Tentang
C-MORE Web merupakan aplikasi berbasis web untuk mengelola data rekam medik gigi, transaksi dan riwayat pemeriksaan pasien 

## Instalasi
1. Buat file **.env**, isi bisa salin dari file **.env.example**. Sesuaikan konfigurasi nama aplikasi, url, kredensial database.
2. Jalankan perintah **composer install** untuk instalasi dependensi package
3. Jalankan perintah **composer setup** untuk setup konfigurasi
4. Jalankan perintah **php artisan migrate --seed** untuk menjalankan migrasi database serta seeder data.

### Postman Collections

 1. Import postman collections file di folder `collections` ke dalam postman
 2. Buat environment dan tambahkan variable berikut :

    a. `BASE_API_URL` untuk url api, misalnya `http://localhost:8000`

    b. `JWT` untuk token api `Bearer yourtokenhere`

