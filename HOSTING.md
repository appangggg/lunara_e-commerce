# 🚀 Panduan Hosting Lunara E-Commerce di VPS Ubuntu

## Persyaratan Minimum VPS

| Spesifikasi | Minimum | Rekomendasi |
|-------------|---------|-------------|
| RAM | 1 GB | 2 GB |
| CPU | 1 vCPU | 2 vCPU |
| Storage | 20 GB SSD | 40 GB SSD |
| OS | Ubuntu 22.04+ | Ubuntu 24.04 LTS |

## Arsitektur Stack

```
┌─────────────────────────────────────────────┐
│                  Internet                    │
│                     │                        │
│              ┌──────▼──────┐                │
│              │    Nginx    │ (Reverse Proxy) │
│              │   Port 80   │                │
│              │  Port 443   │ (SSL)          │
│              └──────┬──────┘                │
│                     │                        │
│              ┌──────▼──────┐                │
│              │  PHP 8.3    │                │
│              │    FPM      │                │
│              └──────┬──────┘                │
│                     │                        │
│         ┌───────────┼───────────┐           │
│         │           │           │           │
│   ┌─────▼─────┐ ┌───▼───┐ ┌───▼────┐     │
│   │   MySQL   │ │ Queue │ │Storage │      │
│   │  Database │ │Worker │ │ (disk) │      │
│   └───────────┘ └───────┘ └────────┘      │
└─────────────────────────────────────────────┘
```

---

## 📋 Langkah-langkah Hosting

### Langkah 1: Akses VPS

```bash
ssh root@YOUR_SERVER_IP
# atau
ssh username@YOUR_SERVER_IP
```

### Langkah 2: Jalankan Deploy Script (Otomatis)

Deploy script sudah disiapkan untuk setup semua kebutuhan secara otomatis:

```bash
# Clone repo terlebih dahulu (temporary)
cd /tmp
git clone -b hosting https://github.com/appangggg/lunara_e-commerce.git lunara-temp

# Jalankan deploy script
bash lunara-temp/deploy/deploy.sh

# Hapus temporary clone
rm -rf /tmp/lunara-temp
```

Script ini akan **otomatis** menginstall:
- ✅ Nginx
- ✅ MySQL
- ✅ PHP 8.3 + semua extension yang dibutuhkan
- ✅ Composer
- ✅ Node.js LTS
- ✅ Certbot (SSL)
- ✅ Supervisor (Queue Worker)

### Langkah 3: Konfigurasi .env

Setelah deploy script selesai, edit file `.env`:

```bash
nano /var/www/lunara/.env
```

**Yang WAJIB diisi:**

| Variable | Keterangan |
|----------|------------|
| `APP_URL` | Domain/IP server Anda, misal: `https://lunara.com` |
| `DB_DATABASE` | Nama database (sudah diisi otomatis) |
| `DB_USERNAME` | Username database (sudah diisi otomatis) |
| `DB_PASSWORD` | Password database (sudah diisi otomatis) |
| `GOOGLE_CLIENT_ID` | Client ID dari Google Console |
| `GOOGLE_CLIENT_SECRET` | Client Secret dari Google Console |
| `GOOGLE_REDIRECT_URL` | `https://your-domain.com/auth/google/callback` |
| `MIDTRANS_SERVER_KEY` | Server Key dari Midtrans Dashboard |
| `MIDTRANS_CLIENT_KEY` | Client Key dari Midtrans Dashboard |
| `MIDTRANS_IS_PRODUCTION` | `true` untuk mode live |

### Langkah 4: Konfigurasi Nginx

Edit file Nginx untuk memasukkan domain/IP Anda:

```bash
sudo nano /etc/nginx/sites-available/lunara
```

Ganti `your-domain.com` dengan domain atau IP server:

```nginx
server_name lunara.com www.lunara.com;
# atau jika belum punya domain:
server_name YOUR_SERVER_IP;
```

Test dan reload:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

### Langkah 5: Setup SSL (Jika Punya Domain)

```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

Certbot akan otomatis:
- Generate sertifikat SSL
- Update konfigurasi Nginx
- Setup auto-renewal

Setelah SSL aktif, uncomment blok HTTPS di Nginx config dan update `.env`:

```bash
# Di .env
APP_URL=https://your-domain.com
GOOGLE_REDIRECT_URL=https://your-domain.com/auth/google/callback
```

### Langkah 6: Update Google OAuth Redirect

Di [Google Cloud Console](https://console.cloud.google.com/):
1. Buka **APIs & Services** → **Credentials**
2. Edit OAuth 2.0 Client ID Anda
3. Tambahkan **Authorized redirect URIs**:
   - `https://your-domain.com/auth/google/callback`

### Langkah 7: Update Midtrans Webhook URL

Di [Midtrans Dashboard](https://dashboard.midtrans.com/):
1. Buka **Settings** → **Configuration**
2. Set **Payment Notification URL** ke:
   - `https://your-domain.com/api/webhook/midtrans`
3. Jika sudah siap production, aktifkan mode **Production**

---

## 🔄 Update Deployment

Setelah ada perubahan kode, cukup jalankan:

```bash
cd /var/www/lunara
bash deploy/deploy.sh
```

Script akan otomatis:
1. Aktifkan maintenance mode
2. Pull kode terbaru
3. Install dependencies
4. Build assets
5. Jalankan migration
6. Clear & rebuild cache
7. Nonaktifkan maintenance mode

---

## 🛠️ Troubleshooting

### Cek Status Services

```bash
# Nginx
sudo systemctl status nginx

# PHP-FPM
sudo systemctl status php8.3-fpm

# MySQL
sudo systemctl status mysql

# Queue Worker
sudo supervisorctl status lunara-worker:*
```

### Cek Log Error

```bash
# Laravel log
tail -f /var/www/lunara/storage/logs/laravel.log

# Nginx error log
tail -f /var/log/nginx/lunara-error.log

# PHP-FPM log
tail -f /var/log/php8.3-fpm.log
```

### Permission Error

```bash
cd /var/www/lunara
sudo chown -R $USER:www-data .
sudo chmod -R 775 storage bootstrap/cache
```

### 502 Bad Gateway

```bash
# Pastikan PHP-FPM running
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx
```

### Clear All Cache

```bash
cd /var/www/lunara
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize
```

---

## 🔒 Keamanan Tambahan

### Setup Firewall (UFW)

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
sudo ufw status
```

### Auto-update SSL Certificate

Certbot sudah otomatis setup cron untuk renewal. Verify:

```bash
sudo certbot renew --dry-run
```

### Disable root SSH login (Rekomendasi)

```bash
sudo nano /etc/ssh/sshd_config
# Ganti: PermitRootLogin yes → PermitRootLogin no
sudo systemctl restart sshd
```

---

## 📁 Struktur File Hosting

```
deploy/
├── deploy.sh              # Script deployment otomatis
├── nginx/
│   └── lunara.conf        # Konfigurasi Nginx
.env.production.example    # Template .env untuk production
HOSTING.md                 # File ini (panduan hosting)
```
