<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Status Theme (Terhubung ke Dashboard & localStorage)
const isNavbarDark = ref(false)
const username = ref(localStorage.getItem('username') || localStorage.getItem('user') || 'Admin')
const profileImage = ref(localStorage.getItem('profileImage') || '')

const syncProfileState = () => {
  const savedUsername = localStorage.getItem('username') || localStorage.getItem('user')
  if (savedUsername) {
    username.value = savedUsername
  }

  profileImage.value = localStorage.getItem('profileImage') || ''
}

onMounted(() => {
  // 1. Cek nilai awal tema dari localStorage
  const savedTheme = localStorage.getItem('admin-theme-mode')
  if (savedTheme) {
    isNavbarDark.value = savedTheme === 'dark'
  }

  syncProfileState()

  // 2. Dengarkan perubahan tema secara realtime saat di-switch di Dashboard
  const handleThemeChange = (event) => {
    if (event.detail?.dark !== undefined) {
      isNavbarDark.value = event.detail.dark
    } else {
      isNavbarDark.value = localStorage.getItem('admin-theme-mode') === 'dark'
    }
  }

  const handleStorageUpdate = () => {
    syncProfileState()
  }

  window.addEventListener('admin-theme-change', handleThemeChange)
  window.addEventListener('storage', handleStorageUpdate)
})

// Logo Path & Fallback
const logoCemilku = ref('/images/cemilku-logo.png')

const handleLogoError = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
}

// Data Produk Sample
const products = ref([])

const handleLogout = () => {
  localStorage.clear()
  router.push('/login')
}
</script>

<template>
  <div class="dashboard-layout" :class="{ 'navbar-dark': isNavbarDark }">
    <!-- SIDEBAR KIRI -->
    <aside class="sidebar">
      <div class="sidebar-brand" @click="router.push('/')">
        <div class="brand-mark">
          <img
            :src="logoCemilku"
            alt="Logo Cemilku"
            class="cemilku-logo-img"
            draggable="false"
            @error="handleLogoError"
          />
        </div>
        <div class="brand-info">
          <span class="brand-text">Cemilku</span>
          <small>SNACK STORE</small>
        </div>
      </div>

      <nav class="sidebar-menu">
        <div class="menu-category">MAIN</div>
        <router-link to="/admin" class="menu-item">
          <span class="menu-icon">📊</span> Dashboard
        </router-link>

        <div class="menu-category">KELOLA TOKO</div>
        <router-link to="/admin/produk" class="menu-item active">
          <span class="menu-icon">🍿</span> Produk
        </router-link>
        
        <router-link to="/admin/kategori" class="menu-item">
          <span class="menu-icon">🏷️</span> Kategori
        </router-link>

        <router-link to="/admin/order" class="menu-item">
          <span class="menu-icon">📑</span> Order
        </router-link>

        <router-link to="/admin/order-item" class="menu-item">
          <span class="menu-icon">📋</span> Order Item
        </router-link>

        <div class="menu-category">SISTEM</div>
        <router-link to="/admin/pengaturan" class="menu-item">
          <span class="menu-icon">⚙️</span> Pengaturan
        </router-link>
        
        <a href="#" @click.prevent="handleLogout" class="menu-item logout">
          <span class="menu-icon">🚪</span> Keluar
        </a>
      </nav>
    </aside>

    <!-- KONTEN UTAMA -->
    <div class="main-wrapper">
      <!-- TOP NAVBAR / HEADER (Tombol switch tema dihapus dari sini) -->
      <header class="topbar">
        <div class="search-box">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Cari produk..." />
        </div>

        <div class="topbar-right">
          <button class="icon-btn">🔔</button>

          <button class="user-profile-pill" type="button" @click="router.push('/profile')">
            <div class="user-avatar">
              <img
                v-if="profileImage"
                :src="profileImage"
                alt="Foto Profil"
                class="profile-avatar-img"
                @error="(e) => { e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png' }"
              />
              <span v-else>{{ username.charAt(0).toUpperCase() }}</span>
            </div>
            <div class="user-profile-meta">
              <span>Profil</span>
              <strong>{{ username }}</strong>
            </div>
          </button>
        </div>
      </header>

      <!-- CONTENT BODY -->
      <main class="content-body">
        <!-- HERO BANNER -->
        <div class="dashboard-banner">
          <div class="banner-content">
            <div>
              <h1>Kelola Produk</h1>
              <p class="banner-sub">Tambah, edit, atau hapus produk makanan ringan Cemilku.</p>
            </div>
            <button class="btn-primary-action" @click="router.push('/admin/produk/tambah')">
              <span class="btn-icon">+</span> Tambah Produk Baru
            </button>
          </div>
        </div>

        <!-- TABLE CARD -->
        <div v-if="products.length" class="table-card">
          <div class="table-header">
            <h3>Daftar Produk Cemilku</h3>
          </div>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama Produk</th>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Stok</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in products" :key="item.id">
                  <td class="font-bold">#{{ item.id }}</td>
                  <td class="font-bold">{{ item.name }}</td>
                  <td>{{ item.category }}</td>
                  <td><span class="price-tag">{{ item.price }}</span></td>
                  <td>{{ item.stock }} pcs</td>
                  <td>
                    <button class="btn-sm btn-edit">Edit</button>
                    <button class="btn-sm btn-delete">Hapus</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div v-else class="empty-state">
          <p>Belum ada data</p>
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* SEMBUNYIKAN FLOATING BUTTON WA APABILA DIPANGGIL GLOBAL */
:deep(.whatsapp-float),
:deep(.wa-float),
:deep(.floating-wa),
:deep(a[href*="wa.me"]),
:deep(a[href*="whatsapp.com"]) {
  display: none !important;
}

/* LAYOUT BASE */
.dashboard-layout {
  --page-bg: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
  --sidebar-bg: rgba(255, 255, 255, 0.8);
  --sidebar-border: rgba(226, 232, 240, 0.9);
  --topbar-bg: rgba(255, 255, 255, 0.75);
  --topbar-border: rgba(226, 232, 240, 0.9);
  --surface: rgba(255, 255, 255, 0.9);
  --text: #0f172a;
  --muted: #64748b;
  --nav-text: #475569;
  --nav-hover: #eff6ff;
  --nav-active: linear-gradient(135deg, #2563eb, #3b82f6);
  --panel-border: rgba(226, 232, 240, 0.9);
  display: flex;
  min-height: 100vh;
  background: var(--page-bg);
  color: var(--text);
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  position: relative;
}

.dashboard-layout.navbar-dark {
  --page-bg: linear-gradient(180deg, #0f172a 0%, #111827 100%);
  --sidebar-bg: rgba(15, 23, 42, 0.85);
  --sidebar-border: rgba(51, 65, 85, 0.9);
  --topbar-bg: rgba(15, 23, 42, 0.8);
  --topbar-border: rgba(51, 65, 85, 0.9);
  --surface: rgba(15, 23, 42, 0.8);
  --text: #f8fafc;
  --muted: #cbd5e1;
  --nav-text: #cbd5e1;
  --nav-hover: rgba(59, 130, 246, 0.12);
  --nav-active: linear-gradient(135deg, #1d4ed8, #3b82f6);
  --panel-border: rgba(51, 65, 85, 0.9);
}

/* SIDEBAR KIRI */
.sidebar {
  width: 250px;
  background: var(--sidebar-bg);
  border-right: 1px solid var(--sidebar-border);
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  backdrop-filter: blur(14px);
}

.sidebar-brand {
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid rgba(226, 232, 240, 0.9);
  cursor: pointer;
  transition: opacity 0.2s ease;
}

.sidebar-brand:hover {
  opacity: 0.9;
}

.brand-mark {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 3px;
  flex-shrink: 0;
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.18);
}

.cemilku-logo-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}

.brand-info {
  display: flex;
  flex-direction: column;
}

.brand-text {
  font-weight: 800;
  font-size: 1.3rem;
  color: var(--text);
  letter-spacing: -0.05em;
  line-height: 1.1;
}

.brand-info small {
  font-size: 0.58rem;
  letter-spacing: 0.16em;
  color: #2563eb;
  font-weight: 700;
  text-transform: uppercase;
  margin-top: 2px;
}

.sidebar-menu {
  padding: 20px 12px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.menu-category {
  font-size: 10px;
  font-weight: 700;
  color: var(--muted);
  padding: 12px 12px 4px 12px;
  letter-spacing: 0.5px;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  color: var(--nav-text);
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
  border-radius: 10px;
  transition: all 0.2s ease;
}

.menu-item:hover,
.menu-item.router-link-active {
  background: var(--nav-hover);
  color: var(--text);
}

.menu-item.router-link-active.active,
.menu-item.active {
  background: var(--nav-active);
  color: #ffffff;
  box-shadow: 0 8px 16px rgba(37, 99, 235, 0.18);
}

.menu-item.logout {
  color: #dc2626;
}

/* MAIN WRAPPER */
.main-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow-x: hidden;
}

/* TOPBAR */
.topbar {
  height: 64px;
  background: var(--topbar-bg);
  border-bottom: 1px solid var(--topbar-border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 28px;
  backdrop-filter: blur(14px);
}

.search-box {
  display: flex;
  align-items: center;
  gap: 8px;
  background: rgba(148, 163, 184, 0.06);
  border: 1px solid var(--panel-border);
  padding: 8px 14px;
  border-radius: 10px;
  width: 300px;
}

.search-box input {
  background: transparent;
  border: none;
  outline: none;
  color: var(--text);
  font-size: 13px;
  width: 100%;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 16px;
}

.icon-btn {
  background: rgba(148, 163, 184, 0.08);
  border: 1px solid var(--panel-border);
  border-radius: 10px;
  cursor: pointer;
  font-size: 16px;
  width: 36px;
  height: 36px;
  display: grid;
  place-items: center;
  color: var(--text);
}

.user-profile-pill {
  display: flex;
  align-items: center;
  gap: 10px;
  background: rgba(37, 99, 235, 0.08);
  border: 1px solid rgba(59, 130, 246, 0.2);
  border-radius: 999px;
  padding: 5px 12px 5px 6px;
  cursor: pointer;
  transition: all 0.2s ease;
  color: var(--text);
}

.user-profile-pill:hover {
  background: rgba(37, 99, 235, 0.12);
  transform: translateY(-1px);
}

.user-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  display: grid;
  place-items: center;
  font-weight: 700;
  flex-shrink: 0;
  overflow: hidden;
}

.profile-avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.user-profile-meta {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  line-height: 1.2;
}

.user-profile-meta span {
  font-size: 10px;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.user-profile-meta strong {
  font-size: 12px;
  font-weight: 700;
}

/* CONTENT BODY */
.content-body {
  padding: 0 28px 28px 28px;
}

/* DASHBOARD BANNER BAR */
.dashboard-banner {
  background: linear-gradient(135deg, rgba(191, 219, 254, 0.9), rgba(239, 246, 255, 0.9));
  border: 1px solid rgba(191, 219, 254, 0.9);
  border-radius: 16px;
  padding: 28px;
  margin-top: 24px;
  box-shadow: 0 12px 24px rgba(37, 99, 235, 0.08);
}

.dashboard-layout.navbar-dark .dashboard-banner {
  background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.9));
  border-color: rgba(51, 65, 85, 0.9);
}

.banner-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.banner-content h1 {
  font-size: 22px;
  font-weight: 800;
  margin: 0;
  color: var(--text);
}

.banner-sub {
  color: var(--muted);
  font-size: 13px;
  margin: 6px 0 0 0;
}

/* ANIMATED BUTTON PRIMARY ACTION */
.btn-primary-action {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.22);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-icon {
  display: inline-block;
  font-size: 16px;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-primary-action:hover {
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
}

.btn-primary-action:hover .btn-icon {
  transform: rotate(90deg);
}

.btn-primary-action:active {
  transform: translateY(-1px) scale(0.98);
  box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
}

/* TABLE CARD */
.table-card {
  background: var(--surface);
  border: 1px solid var(--panel-border);
  border-radius: 14px;
  margin-top: 24px;
  overflow: hidden;
}

.table-header {
  padding: 20px 24px;
  border-bottom: 1px solid rgba(226, 232, 240, 0.9);
}

.table-header h3 {
  margin: 0;
  font-size: 16px;
  font-weight: 800;
  color: var(--text);
}

.table-responsive {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
  font-size: 14px;
}

th {
  background: rgba(148, 163, 184, 0.06);
  color: var(--muted);
  padding: 14px 24px;
  font-weight: 700;
  font-size: 12px;
  text-transform: uppercase;
}

td {
  padding: 16px 24px;
  border-bottom: 1px solid var(--panel-border);
  color: var(--text);
}

.font-bold {
  font-weight: 700;
  color: var(--text);
}

.price-tag {
  font-weight: 700;
  color: #2563eb;
}

/* BUTTON ACTION SIZES */
.btn-sm {
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  margin-right: 6px;
  transition: opacity 0.2s, transform 0.1s;
}

.btn-sm:hover {
  opacity: 0.85;
  transform: translateY(-1px);
}

.btn-edit {
  background-color: #2563eb;
  color: #ffffff;
}

.btn-delete {
  background-color: #dc2626;
  color: #ffffff;
}

.empty-state {
  margin-top: 24px;
  background: var(--surface);
  border: 1px solid var(--panel-border);
  border-radius: 14px;
  padding: 30px 20px;
  text-align: center;
  color: var(--muted);
}

.empty-state p {
  margin: 0;
  font-size: 14px;
  font-weight: 600;
}
</style>