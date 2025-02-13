# Install Laravel dari GitHub

## Clone repository
Untuk menginstall Laravel dari GitHub, ikuti langkah-langkah berikut:

1. **Clone repository**:
    ```bash
    git clone https://github.com/alhilalanwar07/sistem-uji-kir.git
    ```

2. **Masuk ke direktori proyek**:
    ```bash
    cd sistem-uji-kir
    ```

3. **Install dependensi menggunakan Composer**:
    ```bash
    composer install
    ```

4. **Salin file `.env.example` menjadi `.env`**:
    ```bash
    cp .env.example .env
    ```

5. **Generate application key**:
    ```bash
    php artisan key:generate
    ```

6. **Konfigurasi database di file `.env`**:
    Edit file `.env` dan sesuaikan konfigurasi database sesuai dengan pengaturan Anda.

7. **Jalankan migrasi database**:
    ```bash
    php artisan migrate
    ```

8. **Jalankan server pengembangan**:
    ```bash
    php artisan serve
    ```

Sekarang, Anda dapat mengakses aplikasi Laravel Anda di `http://localhost:8000`.

