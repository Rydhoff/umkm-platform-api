# UMKM Platform API

Backend REST API untuk platform marketplace UMKM berbasis lokasi.\
Project ini memungkinkan pengguna menemukan warung terdekat, melihat
produk, menambahkan ke keranjang, dan melakukan pemesanan.

Backend ini dibangun menggunakan Laravel dengan arsitektur REST API dan
autentikasi menggunakan Laravel Sanctum.

------------------------------------------------------------------------

# Features

## Authentication

-   Register user
-   Login user
-   Token authentication
-   Get user profile
-   Logout

## Store Management

-   Seller dapat membuat warung
-   Melihat detail warung
-   Menampilkan warung terdekat berdasarkan lokasi

## Product Management

-   Menambahkan produk
-   Mengupdate produk
-   Menghapus produk
-   Melihat daftar produk dari suatu warung

## Cart System

-   Menambahkan produk ke cart
-   Melihat isi cart
-   Menghapus item dari cart

## Order System

-   Checkout cart
-   Membuat order
-   Melihat riwayat order
-   Seller menerima order
-   Seller menyelesaikan order

------------------------------------------------------------------------

# Tech Stack

Backend ini dibangun menggunakan teknologi berikut:

-   Laravel
-   MySQL
-   Laravel Sanctum
-   Postman

------------------------------------------------------------------------

# Database Structure

Entity utama dalam sistem:

-   Users
-   Stores
-   Products
-   Carts
-   Orders
-   Order_Items

Relasi utama:

User - has one Store - has many Orders - has many Cart items

Store - has many Products

Order - has many Order Items

------------------------------------------------------------------------

# API Endpoints

## Authentication

POST /api/register\
POST /api/login\
GET /api/profile\
POST /api/logout

## Stores

POST /api/stores\
GET /api/stores/{id}\
GET /api/stores/nearby

## Products

POST /api/products\
PUT /api/products/{id}\
DELETE /api/products/{id}\
GET /api/stores/{id}/products

## Cart

GET /api/cart\
POST /api/cart\
DELETE /api/cart/{id}

## Orders

POST /api/orders\
GET /api/orders\
GET /api/orders/{id}\
POST /api/orders/{id}/accept\
POST /api/orders/{id}/complete

------------------------------------------------------------------------

# Installation

Clone repository:

git clone https://github.com/username/umkm-platform-api.git

Masuk ke folder project:

cd umkm-platform-api

Install dependency:

composer install

Copy file environment:

cp .env.example .env

Generate key:

php artisan key:generate

------------------------------------------------------------------------

# Database Setup

Buat database baru di MySQL lalu update file `.env`.

Contoh:

DB_DATABASE=umkm_platform\
DB_USERNAME=root\
DB_PASSWORD=

Jalankan migration:

php artisan migrate

------------------------------------------------------------------------

# Running the Server

Jalankan server lokal:

php artisan serve

Server akan berjalan di:

http://127.0.0.1:8000

Contoh endpoint:

http://127.0.0.1:8000/api/login

------------------------------------------------------------------------

# Testing API

API dapat diuji menggunakan:

-   Postman
-   Insomnia

Contoh request login:

POST /api/login

Body:

{ "email": "user@mail.com", "password": "password" }

------------------------------------------------------------------------

# Project Structure

app - Models - Http - Controllers - Middleware

database - migrations

routes - api.php

------------------------------------------------------------------------

# Future Improvements

Pengembangan selanjutnya:

-   Payment gateway integration
-   Realtime order notification
-   Delivery tracking
-   Admin dashboard
-   API documentation menggunakan Swagger

------------------------------------------------------------------------

# Author

Ridho Darmawan

Mahasiswa Teknologi Informasi.

# 📘 UMKM Platform API Documentation (Updated)

Base URL:
```
http://umkm-platform.my.id/api
```

---

# 🔐 AUTH

## 1. Register
❌ No Bearer Token

**Endpoint**
```
POST /register
```

**Request Body**
```json
{
  "name": "Ridho Darmawan",
  "email": "ridho@mail.com",
  "password": "password123",
  "phone": "08123456789",
  "role": "buyer"
}
```

**Response**
```json
{
  "success": true,
  "message": "User registered",
  "data": {
    "user": {
      "id": 1,
      "name": "Ridho Darmawan",
      "email": "ridho@mail.com",
      "phone": "08123456789",
      "role": "buyer"
    },
    "token": "1|xxxxx"
  }
}
```

---

## 2. Login
❌ No Bearer Token

**Endpoint**
```
POST /login
```

**Request Body**
```json
{
  "email": "ridho@mail.com",
  "password": "password123"
}
```

**Response**
```json
{
  "success": true,
  "message": "Login success",
  "data": {
    "user": {
      "id": 1,
      "name": "Ridho Darmawan",
      "email": "ridho@mail.com",
      "role": "buyer"
    },
    "token": "1|xxxxx"
  }
}
```

---

## 3. Profile
✅ Bearer Token

**Endpoint**
```
GET /profile
```

**Response**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Ridho Darmawan",
    "email": "ridho@mail.com",
    "phone": "08123456789",
    "role": "buyer"
  }
}
```

---

## 4. Logout
✅ Bearer Token

**Endpoint**
```
POST /logout
```

**Response**
```json
{
  "success": true,
  "message": "Logout success"
}
```

---

## 5. Update Profile
✅ Bearer Token

**Endpoint**
```
PUT /update-profile
```

**Request Body**
```json
{
  "name": "Ridho D.",
  "email": "ridho.new@mail.com",
  "phone": "08123456790"
}
```

**Response**
```json
{
  "success": true,
  "message": "Profile updated",
  "data": {
    "id": 1,
    "name": "Ridho D.",
    "email": "ridho.new@mail.com",
    "phone": "08123456790",
    "role": "buyer"
  }
}
```

---

# 🔑 PASSWORD

## 1. Forgot Password
❌ No Bearer Token

**Endpoint**
```
POST /forgot-password
```

**Request Body**
```json
{
  "email": "ridho@mail.com"
}
```

**Response**
```json
{
  "success": true,
  "message": "Reset link sent to email"
}
```

> 🔹 Catatan: Email akan berisi link reset password yang mengarah ke frontend React.

---

## 2. Reset Password
❌ No Bearer Token

**Endpoint**
```
POST /reset-password
```

**Request Body**
```json
{
  "email": "ridho@mail.com",
  "token": "xxxxxx",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response**
```json
{
  "success": true,
  "message": "Password reset success"
}
```

---

# 🏪 STORE

## 1. Create Store
✅ Bearer Token (Seller)

```
POST /stores
```

**Request Body**
```json
{
  "name": "Warung Bu Siti",
  "description": "Makanan rumahan enak",
  "address": "Bekasi Timur",
  "latitude": -6.2345,
  "longitude": 106.9921
}
```

**Response**
```json
{
  "message": "Store created",
  "data": {
    "id": 1,
    "name": "Warung Bu Siti"
  }
}
```

---

## 2. Get Store Detail
❌ No Bearer Token

```
GET /stores/{id}
```

**Response**
```json
{
  "id": 1,
  "name": "Warung Bu Siti",
  "address": "Bekasi Timur",
  "products": []
}
```

---

## 3. Nearby Stores
❌ No Bearer Token

```
GET /stores/nearby?lat=-6.2&lng=106.9
```

---

# 🍔 PRODUCT

## 1. Get Products by Store
❌ No Bearer Token

```
GET /stores/{id}/products
```

**Response**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Nasi Goreng",
      "price": 15000,
      "stock": 100,
      "description": "Nasi goreng spesial",
      "image": "products/image.jpg",
      "is_available": true
    }
  ]
}
```

---

## 2. Search Products
❌ No Bearer Token

```
GET /products/search?q={keyword}
```

**Query Parameter**
| Parameter | Type | Description |
|-----------|------|-------------|
| q | string | Keyword pencarian nama produk |

**Response**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Nasi Goreng",
      "price": 15000,
      "stock": 100,
      "is_available": true,
      "store": {
        "id": 1,
        "name": "Warung Bu Siti"
      }
    }
  ]
}
```

---

## 3. Create Product
✅ Bearer Token

```
POST /products
```

> 📎 Gunakan `multipart/form-data` jika upload gambar

**Request Body**
| Field | Type | Required | Keterangan |
|-------|------|----------|------------|
| store_id | integer | ✅ | ID toko milik seller |
| name | string | ✅ | Nama produk |
| price | integer | ✅ | Harga produk |
| stock | integer | ✅ | Stok produk |
| description | string | ❌ | Deskripsi produk |
| image | file | ❌ | Gambar produk (jpg/jpeg/png, max 2MB) |

**Response**
```json
{
  "success": true,
  "message": "Product created",
  "data": {
    "id": 1,
    "store_id": 1,
    "name": "Nasi Goreng",
    "price": 15000,
    "stock": 100,
    "description": "Nasi goreng spesial",
    "image": "products/image.jpg",
    "is_available": true
  }
}
```

---

## 4. Update Product
✅ Bearer Token

```
PUT /products/{id}
```

> 📎 Gunakan `multipart/form-data` jika update gambar

**Request Body**
| Field | Type | Required | Keterangan |
|-------|------|----------|------------|
| name | string | ❌ | Nama produk |
| price | integer | ❌ | Harga produk |
| stock | integer | ❌ | Stok produk |
| description | string | ❌ | Deskripsi produk |
| image | file | ❌ | Gambar baru (otomatis hapus gambar lama) |
| is_available | boolean | ❌ | Status ketersediaan produk |

**Response**
```json
{
  "success": true,
  "message": "Product updated",
  "data": {
    "id": 1,
    "name": "Nasi Goreng Spesial",
    "price": 18000,
    "stock": 80,
    "is_available": true
  }
}
```

---

## 5. Delete Product
✅ Bearer Token

```
DELETE /products/{id}
```

**Response**
```json
{
  "success": true,
  "message": "Product deleted"
}
```

---

# 🛒 CART

## 1. Get Cart
✅ Bearer Token

```
GET /cart
```

**Response**
```json
[
  {
    "id": 1,
    "product_id": 1,
    "quantity": 2,
    "product": {
      "name": "Nasi Goreng",
      "price": 15000
    }
  }
]
```

---

## 2. Add to Cart
✅ Bearer Token

```
POST /cart
```

**Request Body**
```json
{
  "product_id": 1,
  "quantity": 2
}
```

---

## 3. Update Quantity
✅ Bearer Token

```
PUT /cart/{id}
```

**Request Body**
```json
{
  "quantity": 3
}
```

---

## 4. Delete Cart Item
✅ Bearer Token

```
DELETE /cart/{id}
```

---

# 💰 ORDER

## 1. Checkout
✅ Bearer Token

```
POST /orders
```

**Request Body**
| Field | Type | Required | Keterangan |
|-------|------|----------|------------|
| store_id | integer | ✅ | ID toko yang dipesan |
| order_type | string | ✅ | `pickup` atau `delivery` |
| buyer_lat | float | ❌ | Latitude pembeli (wajib jika `delivery`) |
| buyer_lng | float | ❌ | Longitude pembeli (wajib jika `delivery`) |

> 🔹 `buyer_lat` dan `buyer_lng` diperlukan jika `order_type = delivery` untuk menghitung ongkir otomatis (Rp 2.000/km).
> Platform fee sebesar **Rp 2.000** dikenakan di setiap order.

**Request Body (Pickup)**
```json
{
  "store_id": 1,
  "order_type": "pickup"
}
```

**Request Body (Delivery)**
```json
{
  "store_id": 1,
  "order_type": "delivery",
  "buyer_lat": -6.2,
  "buyer_lng": 106.9
}
```

**Response**
```json
{
  "success": true,
  "message": "Order created",
  "data": {
    "id": 1,
    "buyer_id": 1,
    "store_id": 1,
    "order_type": "pickup",
    "status": "pending",
    "product_total": 30000,
    "delivery_fee": 0,
    "platform_fee": 2000,
    "total_price": 32000
  }
}
```

---

## 2. Get Orders
✅ Bearer Token

```
GET /orders
```

**Response**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "store_id": 1,
      "order_type": "pickup",
      "status": "pending",
      "product_total": 30000,
      "delivery_fee": 0,
      "platform_fee": 2000,
      "total_price": 32000,
      "items": []
    }
  ]
}
```

---

## 3. Get Order Detail
✅ Bearer Token

```
GET /orders/{id}
```

**Response**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "store_id": 1,
    "order_type": "delivery",
    "status": "pending",
    "product_total": 30000,
    "delivery_fee": 6000,
    "platform_fee": 2000,
    "total_price": 38000,
    "items": [
      {
        "id": 1,
        "product_id": 1,
        "quantity": 2,
        "price": 15000,
        "product": {
          "name": "Nasi Goreng",
          "image": "products/image.jpg"
        }
      }
    ]
  }
}
```

---

## 4. Accept Order
✅ Bearer Token (Seller only)

```
POST /orders/{id}/accept
```

> 🔒 Hanya seller pemilik toko yang bisa menerima order.

**Response**
```json
{
  "success": true,
  "message": "Order accepted"
}
```

---

## 5. Complete Order
✅ Bearer Token (Seller only)

```
POST /orders/{id}/complete
```

**Response**
```json
{
  "success": true,
  "message": "Order completed"
}
```

---

## 6. Cancel Order
✅ Bearer Token (Buyer atau Seller)

```
POST /orders/{id}/cancel
```

> 🔹 Cancel hanya bisa dilakukan ketika status masih `pending` atau `accepted`.
> 🔒 Hanya buyer pemilik order atau seller pemilik toko yang dapat cancel.

**Response**
```json
{
  "message": "Order cancelled"
}
```

---

# 🔄 ORDER STATUS

| Status | Deskripsi |
|--------|----------|
| `pending` | Order baru dibuat, menunggu konfirmasi seller |
| `accepted` | Seller sudah menerima order |
| `completed` | Order selesai |
| `cancelled` | Order dibatalkan |

---

# 💬 CHAT

## 1. Create or Get Conversation
✅ Bearer Token

```
POST /conversations
```

> 🔹 Jika conversation antara buyer dan seller untuk toko tersebut sudah ada, endpoint ini mengembalikan conversation yang sudah ada (tidak membuat duplikat).

**Request Body**
```json
{
  "store_id": 1
}
```

**Response**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "buyer_id": 2,
    "seller_id": 1,
    "store_id": 1,
    "created_at": "2026-03-30T08:00:00.000000Z"
  }
}
```

---

## 2. Get Conversations (Inbox)
✅ Bearer Token

```
GET /conversations
```

> 🔹 Menampilkan semua conversation milik user (baik sebagai buyer maupun seller).

**Response**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "buyer_id": 2,
      "seller_id": 1,
      "store_id": 1,
      "created_at": "2026-03-30T08:00:00.000000Z"
    }
  ]
}
```

---

## 3. Get Messages
✅ Bearer Token

```
GET /conversations/{id}/messages
```

> 🔒 Hanya buyer atau seller dalam conversation yang dapat mengakses pesan.

**Response**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "conversation_id": 1,
      "sender_id": 2,
      "message": "Halo, masih ada stok?",
      "created_at": "2026-03-30T08:01:00.000000Z"
    },
    {
      "id": 2,
      "conversation_id": 1,
      "sender_id": 1,
      "message": "Masih ada, silakan order!",
      "created_at": "2026-03-30T08:02:00.000000Z"
    }
  ]
}
```

---

## 4. Send Message
✅ Bearer Token

```
POST /messages
```

**Request Body**
```json
{
  "conversation_id": 1,
  "message": "Halo, masih ada stok?"
}
```

**Response**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "conversation_id": 1,
    "sender_id": 2,
    "message": "Halo, masih ada stok?",
    "created_at": "2026-03-30T08:01:00.000000Z"
  }
}
```

---

# ⚠️ ERROR FORMAT

```json
{
  "message": "Error message"
}
```

---

# 🚀 NOTES

- Gunakan Bearer Token untuk endpoint yang bertanda ✅
- Jangan kirim price dari frontend saat checkout
- Pastikan handle error 401 di frontend
- Gunakan endpoint ini sebagai contract antara frontend & backend
- Untuk order `delivery`, kirim `buyer_lat` dan `buyer_lng` agar ongkir dihitung otomatis
- Platform fee **Rp 2.000** selalu dikenakan di setiap order