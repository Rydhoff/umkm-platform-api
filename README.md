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

# 📘 UMKM Platform API Documentation

Base URL: http://localhost:8000/api

------------------------------------------------------------------------

# 🔐 AUTHORIZATION

Gunakan header berikut untuk endpoint yang membutuhkan token:

Authorization: Bearer {token}

------------------------------------------------------------------------

# 🔐 AUTH

## 1. Register

❌ No Bearer Token

POST /register

Request Body: { "name": "Ridho Darmawan", "email": "ridho@mail.com",
"password": "password123", "password_confirmation": "password123",
"role": "buyer" }

Response (201): { "message": "Register success", "user": { "id": 1,
"name": "Ridho Darmawan", "email": "ridho@mail.com", "role": "buyer" } }

------------------------------------------------------------------------

## 2. Login

❌ No Bearer Token

POST /login

Request Body: { "email": "ridho@mail.com", "password": "password123" }

Response: { "token": "1\|xxxxx", "user": { "id": 1, "name": "Ridho
Darmawan", "role": "buyer" } }

------------------------------------------------------------------------

## 3. Profile

✅ Bearer Token

GET /profile

------------------------------------------------------------------------

## 4. Logout

✅ Bearer Token

POST /logout

------------------------------------------------------------------------

# 🏪 STORE

## 1. Create Store

✅ Bearer Token (Seller)

POST /stores

Request Body: { "name": "Warung Bu Siti", "description": "Makanan
rumahan enak", "address": "Bekasi Timur", "latitude": -6.2345,
"longitude": 106.9921 }

Response: { "message": "Store created", "data": { "id": 1, "name":
"Warung Bu Siti", "owner_id": 2 } }

------------------------------------------------------------------------

## 2. Get Store Detail

❌ No Bearer Token

GET /stores/{id}

Response: { "id": 1, "name": "Warung Bu Siti", "address": "Bekasi
Timur", "products": \[ { "id": 1, "name": "Nasi Goreng", "price": 15000,
"image_url": "http://localhost:8000/storage/products/xxx.jpg" } \] }

------------------------------------------------------------------------

## 3. Nearby Stores

❌ No Bearer Token

GET /stores/nearby?lat=-6.2&lng=106.9

------------------------------------------------------------------------

# 🍔 PRODUCT

## 1. Get Products by Store

❌ No Bearer Token

GET /stores/{id}/products

------------------------------------------------------------------------

## 2. Create Product

✅ Bearer Token

POST /products

Content-Type: multipart/form-data

Form Data: - store_id (text) - name (text) - description (text) - price
(text) - stock (text) - image (file)

Response: { "success": true, "message": "Product created", "data": {
"id": 1, "name": "Nasi Goreng", "price": 15000, "image":
"products/xxx.jpg", "image_url":
"http://localhost:8000/storage/products/xxx.jpg" } }

------------------------------------------------------------------------

## 3. Update Product

✅ Bearer Token

PUT /products/{id}

Catatan: Bisa kirim ulang field image untuk update gambar

------------------------------------------------------------------------

## 4. Delete Product

✅ Bearer Token

DELETE /products/{id}

------------------------------------------------------------------------

# 🛒 CART

## 1. Get Cart

✅ Bearer Token

GET /cart

Response: \[ { "id": 1, "product_id": 1, "quantity": 2, "product": {
"name": "Nasi Goreng", "price": 15000, "image_url":
"http://localhost:8000/storage/products/xxx.jpg" } }\]

------------------------------------------------------------------------

## 2. Add to Cart

✅ Bearer Token

POST /cart

Request Body: { "product_id": 1, "quantity": 2 }

------------------------------------------------------------------------

## 3. Update Quantity

✅ Bearer Token

PUT /cart/{id}

Request Body: { "quantity": 3 }

------------------------------------------------------------------------

## 4. Delete Cart Item

✅ Bearer Token

DELETE /cart/{id}

------------------------------------------------------------------------

# 💰 ORDER

## 1. Checkout

✅ Bearer Token

POST /orders

Request Body: { "store_id": 1, "order_type": "pickup", "notes": "Tanpa
pedas" }

Response: { "message": "Order created", "order_id": 1, "status":
"pending" }

------------------------------------------------------------------------

## 2. Get Orders

GET /orders

------------------------------------------------------------------------

## 3. Get Order Detail

GET /orders/{id}

Response: { "id": 1, "status": "pending", "items": \[ { "product_name":
"Nasi Goreng", "quantity": 2, "price": 15000, "subtotal": 30000 } \],
"total_price": 30000 }

------------------------------------------------------------------------

## 4. Accept Order

POST /orders/{id}/accept

------------------------------------------------------------------------

## 5. Complete Order

POST /orders/{id}/complete

------------------------------------------------------------------------

## 6. Cancel Order

POST /orders/{id}/cancel

------------------------------------------------------------------------

# 🔄 ORDER STATUS

pending accepted completed cancelled

------------------------------------------------------------------------

# ⚠️ ERROR FORMAT

General: { "message": "Error message" }

Validation: { "message": "Validation failed", "errors": { "email":
\["Email already taken"\] } }

------------------------------------------------------------------------

# 📊 STATUS CODE

200 OK 201 Created 401 Unauthorized 403 Forbidden 404 Not Found 422
Validation Error

------------------------------------------------------------------------

# 🚀 NOTES

-   Gunakan Bearer Token untuk endpoint yang bertanda ✅
-   Product menggunakan upload file (multipart/form-data)
-   Gunakan image_url untuk menampilkan gambar di frontend
-   Jangan kirim price dari frontend saat checkout
-   Handle error 401 di frontend
