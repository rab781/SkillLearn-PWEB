# 📊 SYSTEM ARCHITECTURE DIAGRAM
## SkillLearn Platform

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          SKILLEARN SYSTEM ARCHITECTURE                      │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────┐    ┌──────────────────────┐    ┌─────────────────────┐
│    PRESENTATION     │    │     APPLICATION      │    │       DATA          │
│      LAYER          │    │       LAYER          │    │      LAYER          │
├─────────────────────┤    ├──────────────────────┤    ├─────────────────────┤
│                     │    │                      │    │                     │
│ 🌐 Blade Templates  │◄──►│ 🎯 Controllers      │◄──►│ 📊 MySQL Database   │
│   • customer.blade  │    │   • VidioController  │    │   • users           │
│   • admin.blade     │    │   • AuthController   │    │   • vidio           │
│   • videos.blade    │    │   • BookmarkControl  │    │   • kategori        │
│                     │    │                      │    │   • feedback        │
│ 🎨 Styling         │    │ 🔧 Models            │    │   • bookmark        │
│   • Tailwind CSS    │    │   • User             │    │   • riwayat_tonton  │
│   • Custom CSS      │    │   • Vidio            │    │                     │
│   • Responsive      │    │   • Kategori         │    │ 🔄 Eloquent ORM     │
│                     │    │   • Bookmark         │    │   • Relationships   │
│ ⚡ JavaScript       │    │   • RiwayatTonton    │    │   • Migrations      │
│   • AJAX Calls      │    │                      │    │   • Seeders         │
│   • DOM Manipulation│    │ 🛡️ Middleware        │    │                     │
│   • Event Handlers  │    │   • Authentication   │    │ 🔒 Security         │
│                     │    │   • CSRF Protection  │    │   • Foreign Keys    │
│                     │    │   • Role Check       │    │   • Constraints     │
└─────────────────────┘    └──────────────────────┘    └─────────────────────┘
          │                           │                           │
          │                           │                           │
          ▼                           ▼                           ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                                 ROUTING                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│ 🌐 WEB ROUTES (routes/web.php)     📡 API ROUTES (routes/api.php)          │
│   • /dashboard                       • GET /api/videos                     │
│   • /videos/{id}                     • POST /api/bookmarks                 │
│   • /login                           • GET /api/dashboard                  │
│   • /register                        • POST /api/feedbacks                │
│                                      • POST /api/watch-history             │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                              EXTERNAL INTEGRATIONS                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│ 🔧 DEVELOPMENT TOOLS          📚 FRONTEND LIBRARIES                        │
│   • Vite (Build Tool)           • SweetAlert2 (Notifications)              │
│   • Composer (PHP Dependencies) • Axios (HTTP Client)                      │
│   • NPM (Node Dependencies)     • Tailwind CSS (Styling)                   │
│                                                                             │
│ 🧪 TESTING TOOLS              🔒 SECURITY TOOLS                           │
│   • PHPUnit (Backend Testing)   • Laravel Sanctum (API Auth)               │
│   • Faker (Test Data)           • CSRF Protection                          │
│   • Database Factories          • Input Validation                         │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

## 🔄 REQUEST FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                            HYBRID REQUEST FLOW                             │
└─────────────────────────────────────────────────────────────────────────────┘

📱 USER BROWSER
    │
    ├─ 1️⃣ INITIAL PAGE REQUEST (Server-Side)
    │   │
    │   ▼
    │ 🌐 WEB ROUTE (/dashboard)
    │   │
    │   ▼
    │ 🎯 CONTROLLER (Direct Call)
    │   │
    │   ▼
    │ 📊 DATABASE QUERY
    │   │
    │   ▼
    │ 🎨 BLADE TEMPLATE + DATA
    │   │
    │   ▼
    │ 📄 COMPLETE HTML PAGE
    │
    └─ 2️⃣ DYNAMIC INTERACTIONS (Client-Side)
        │
        ▼
      ⚡ AJAX REQUEST (/api/endpoint)
        │
        ▼
      📡 API ROUTE
        │
        ▼
      🛡️ MIDDLEWARE (Auth + CSRF)
        │
        ▼
      🎯 CONTROLLER METHOD
        │
        ▼
      📊 DATABASE OPERATION
        │
        ▼
      📦 JSON RESPONSE
        │
        ▼
      🎨 DOM UPDATE (No Page Refresh)

```

## 📊 DATABASE SCHEMA (ERD)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                            DATABASE RELATIONSHIPS                           │
└─────────────────────────────────────────────────────────────────────────────┘

                    ┌─────────────────┐
                    │     KATEGORI    │
                    ├─────────────────┤
                    │ PK kategori_id  │
                    │    kategori     │
                    │    created_at   │
                    │    updated_at   │
                    └─────────────────┘
                            │
                            │ 1:N
                            ▼
    ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
    │      USER       │    │      VIDIO      │    │    FEEDBACK     │
    ├─────────────────┤    ├─────────────────┤    ├─────────────────┤
    │ PK users_id     │    │ PK vidio_id     │◄──┤ PK feedback_id  │
    │    nama_lengkap │    │    nama         │    │ FK users_id     │
    │    email        │    │    deskripsi    │    │ FK vidio_id     │
    │    password     │    │    url          │    │    isi_feedback │
    │    role         │    │    gambar       │    │    rating       │
    │    created_at   │    │    jumlah_tayang│    │    tanggal      │
    │    updated_at   │    │ FK kategori_id  │    │    created_at   │
    └─────────────────┘    │    created_at   │    │    updated_at   │
            │              │    updated_at   │    └─────────────────┘
            │              └─────────────────┘
            │                       │
            │                       │
            │ 1:N                   │ N:1
            ▼                       ▼
    ┌─────────────────┐    ┌─────────────────┐
    │    BOOKMARK     │    │ RIWAYAT_TONTON  │
    ├─────────────────┤    ├─────────────────┤
    │ PK bookmark_id  │    │ PK id_riwayat_  │
    │ FK users_id     │    │       tonton    │
    │ FK vidio_id     │    │ FK id_pengguna  │
    │    created_at   │    │ FK id_video     │
    │    updated_at   │    │    waktu_ditonton│
    └─────────────────┘    │    durasi_tonton│
                           │    persentase_  │
                           │       progress  │
                           │    created_at   │
                           │    updated_at   │
                           └─────────────────┘

RELATIONSHIPS:
• User (1) ──→ (N) Bookmark
• User (1) ──→ (N) Feedback  
• User (1) ──→ (N) RiwayatTonton
• Vidio (1) ──→ (N) Bookmark
• Vidio (1) ──→ (N) Feedback
• Vidio (1) ──→ (N) RiwayatTonton
• Kategori (1) ──→ (N) Vidio
```
