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