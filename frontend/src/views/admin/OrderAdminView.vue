<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const isAdminDark = ref(localStorage.getItem('admin-theme-mode') === 'dark')
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
  syncProfileState()

  const handleThemeChange = (event) => {
    const nextDark = event.detail?.dark ?? localStorage.getItem('admin-theme-mode') === 'dark'
    isAdminDark.value = nextDark
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

// Data Orders
const orders = ref([])

const handleLogout = () => {
  localStorage.clear()
  router.push('/login')
}
</script>

<template>
  <div class="dashboard-layout" :class="{ 'navbar-dark': isAdminDark }">
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
        <router-link to="/admin/produk" class="menu-item">
          <span class="menu-icon">🍿</span> Produk
        </router-link>
        
        <router-link to="/admin/kategori" class="menu-item">
          <span class="menu-icon">🏷️</span> Kategori
        </router-link>

        <router-link to="/admin/order" class="menu-item active">
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
      <!-- TOP NAVBAR / HEADER -->
      <header class="topbar">
        <div class="search-box">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Cari transaksi ID/pelanggan..." />
        </div>

        <div class="topbar-right">
          <button class="icon-btn" title="Notifikasi">🔔</button>

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
              <h1>Kelola Pesanan</h1>
              <p class="banner-sub">Daftar seluruh transaksi yang dikirim pelanggan.</p>
            </div>
          </div>
        </div>

        <!-- TABLE CARD -->
        <div v-if="orders.length" class="table-card">
          <div class="table-header">
            <h3>Riwayat Transaksi Masuk</h3>
          </div>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>ID Order</th>
                  <th>Pelanggan</th>
                  <th>Tanggal</th>
                  <th>Total Tagihan</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in orders" :key="order.id">
                  <td class="font-bold">#{{ order.id }}</td>
                  <td class="font-bold">{{ order.customer }}</td>
                  <td>{{ order.date }}</td>
                  <td><span class="price-tag">{{ order.total }}</span></td>
                  <td>
                    <span class="status-badge" :class="order.status.toLowerCase()">
                      {{ order.status }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div v-else class="empty-state">
          <p>Belum ada data pesanan masuk</p>
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

/* LAYOUT BASE & DYNAMIC THEME VARIABLES */
.dashboard-layout {
  --page-bg: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
  --sidebar-bg: rgba(255, 255, 255, 0.82);
  --sidebar-border: rgba(226, 232, 240, 0.95);
  --topbar-bg: rgba(255, 255, 255, 0.78);
  --topbar-border: rgba(226, 232, 240, 0.95);
  --surface: rgba(255, 255, 255, 0.9);
  --table-header-bg: #f1f5f9;
  --input-bg: rgba(148, 163, 184, 0.06);
  --text: #0f172a;
  --muted: #64748b;
  --nav-text: #475569;
  --nav-hover: #eff6ff;
  --nav-active: linear-gradient(135deg, #2563eb, #3b82f6);
  --panel-border: rgba(226, 232, 240, 0.9);
  
  display: flex;
  min-height: 100vh;
  width: 100%;
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
  --surface: rgba(15, 23, 42, 0.82);
  --table-header-bg: #1e293b;
  --input-bg: #1e293b;
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
  min-height: 100vh;
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
  border-bottom: 1px solid var(--sidebar-border);
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

.menu-icon {
  width: 20px;
  min-width: 20px;
  text-align: center;
  font-size: 15px;
}

.menu-item.logout {
  color: #ef4444;
}

.menu-item.logout:hover {
  background-color: rgba(239, 68, 68, 0.1);
  color: #f87171;
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
  background: var(--input-bg);
  border: 1px solid var(--panel-border);
  padding: 8px 14px;
  border-radius: 10px;
  width: 300px;
}

.search-icon {
  font-size: 13px;
  color: var(--muted);
}

.search-box input {
  background: transparent;
  border: none;
  outline: none;
  color: var(--text);
  font-size: 13px;
  width: 100%;
}

.search-box input::placeholder {
  color: var(--muted);
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
  background: var(--surface);
  border: 1px solid var(--panel-border);
  border-radius: 16px;
  padding: 28px;
  margin-top: 24px;
  box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.05);
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

/* TABLE CARD */
.table-card {
  background: var(--surface);
  border: 1px solid var(--panel-border);
  border-radius: 16px;
  margin-top: 24px;
  overflow: hidden;
}

.table-header {
  padding: 20px 24px;
  border-bottom: 1px solid var(--panel-border);
}

.table-header h3 {
  margin: 0;
  font-size: 16px;
  font-weight: 700;
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
  background-color: var(--table-header-bg);
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
  font-weight: 600;
  color: var(--text);
}

.price-tag {
  font-weight: 700;
  color: #2563eb;
}

/* STATUS BADGES */
.status-badge {
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 700;
  display: inline-block;
}

.status-badge.selesai {
  background-color: rgba(34, 197, 94, 0.15);
  color: #16a34a;
  border: 1px solid rgba(34, 197, 94, 0.3);
}

.dashboard-layout.navbar-dark .status-badge.selesai {
  color: #4ade80;
}

.status-badge.pending {
  background-color: rgba(234, 179, 8, 0.15);
  color: #ca8a04;
  border: 1px solid rgba(234, 179, 8, 0.3);
}

.dashboard-layout.navbar-dark .status-badge.pending {
  color: #facc15;
}

.status-badge.diproses {
  background-color: rgba(56, 189, 248, 0.15);
  color: #0284c7;
  border: 1px solid rgba(56, 189, 248, 0.3);
}

.dashboard-layout.navbar-dark .status-badge.diproses {
  color: #38bdf8;
}

.empty-state {
  margin-top: 24px;
  background: var(--surface);
  border: 1px solid var(--panel-border);
  border-radius: 16px;
  padding: 30px 20px;
  text-align: center;
  color: var(--muted);
}

.empty-state p {
  margin: 0;
  font-size: 14px;
  font-weight: 500;
}

/* RESPONSIVE */
@media (max-width: 700px) {
  .sidebar {
    display: none;
  }
  .topbar {
    padding: 0 16px;
  }
  .content-body {
    padding: 0 16px 28px 16px;
  }
}
</style>