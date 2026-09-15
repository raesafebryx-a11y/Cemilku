<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const isNavbarDark = ref(false)

// Cek dan pasang tema saat Dashboard dimuat / di-mount
onMounted(() => {
  const savedTheme = localStorage.getItem('admin-theme-mode')
  if (savedTheme) {
    isNavbarDark.value = savedTheme === 'dark'
  }

  // Listener jika terjadi perubahan event dari window
  const handleThemeChange = (event) => {
    if (event.detail?.dark !== undefined) {
      isNavbarDark.value = event.detail.dark
    }
  }

  window.addEventListener('admin-theme-change', handleThemeChange)
})

// Fungsi Toggle Switch Tema (Hanya Ada di Dashboard)
const toggleNavbarTheme = () => {
  isNavbarDark.value = !isNavbarDark.value
  
  // 1. Simpan status ke localStorage agar halaman lain (Produk, Kategori, dll) bisa membaca
  localStorage.setItem('admin-theme-mode', isNavbarDark.value ? 'dark' : 'light')

  // 2. Trigger Custom Event untuk komunikasi real-time antar halaman
  window.dispatchEvent(
    new CustomEvent('admin-theme-change', {
      detail: { dark: isNavbarDark.value }
    })
  )
}

// Logo Path & Fallback
const logoCemilku = ref('/images/cemilku-logo.png')

const handleLogoError = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
}

// Data Ringkasan Stat
const stats = ref([])

// Data Tabel Produk Sample
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
        <router-link to="/admin" class="menu-item active">
          <span class="menu-icon">📊</span> Dashboard
        </router-link>

        <div class="menu-category">KELOLA TOKO</div>
        <router-link to="/admin/produk" class="menu-item">
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
      <!-- TOP NAVBAR / HEADER -->
      <header class="topbar">
        <div class="search-box">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Cari produk atau pesanan..." />
        </div>

        <div class="topbar-right">
          <!-- HANYA DASHBOARD YANG MEMILIKI TOMBOL SWITCH INI -->
          <button class="theme-toggle" @click="toggleNavbarTheme" aria-label="Toggle navbar theme">
            {{ isNavbarDark ? '☀️' : '🌙' }}
          </button>
          <button class="icon-btn">🔔</button>
          <div class="user-avatar">A</div>
        </div>
      </header>

      <!-- CONTENT BODY -->
      <main class="content-body">
        <!-- HERO BANNER -->
        <div class="dashboard-banner">
          <div class="banner-content">
            <h1>Dashboard Overview</h1>
          </div>
        </div>

        <!-- STAT CARDS -->
        <div class="stats-grid" v-if="stats.length">
          <div v-for="stat in stats" :key="stat.id" class="stat-card">
            <div class="stat-header">
              <span class="stat-label">{{ stat.label }}</span>
              <div class="stat-icon-wrapper">{{ stat.icon }}</div>
            </div>
            <div class="stat-value">{{ stat.value }}</div>
            <div class="stat-sub">{{ stat.sub }}</div>
          </div>
        </div>

        <!-- TABLE SECTION -->
        <div class="table-card" v-if="products.length">
          <div class="table-header">
            <h3>Daftar Produk Cemilku</h3>
          </div>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Nama Produk</th>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Stok</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in products" :key="item.id">
                  <td class="font-bold">{{ item.name }}</td>
                  <td>{{ item.category }}</td>
                  <td>{{ item.price }}</td>
                  <td>{{ item.stock }} pcs</td>
                  <td>
                    <span 
                      class="status-badge"
                      :class="{
                        'status-success': item.status === 'Tersedia',
                        'status-warning': item.status === 'Stok Menipis',
                        'status-danger': item.status === 'Habis'
                      }"
                    >
                      {{ item.status }}
                    </span>
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

.main-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow-x: hidden;
}

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

.theme-toggle,
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

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  display: grid;
  place-items: center;
  font-weight: 700;
}

.content-body {
  padding: 0 28px 28px 28px;
}

.dashboard-banner {
  background: linear-gradient(135deg, rgba(191, 219, 254, 0.9), rgba(239, 246, 255, 0.9));
  border: 1px solid rgba(191, 219, 254, 0.9);
  border-radius: 16px;
  padding: 28px;
  margin-top: 24px;
  padding-bottom: 60px;
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

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-top: -40px;
  padding: 0 12px;
}

.stat-card {
  background: var(--surface);
  border: 1px solid var(--panel-border);
  border-radius: 14px;
  padding: 20px;
  box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.08);
}

.stat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.stat-label {
  font-size: 13px;
  color: var(--muted);
  font-weight: 600;
}

.stat-icon-wrapper {
  background: #eff6ff;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: grid;
  place-items: center;
  font-size: 14px;
  border: 1px solid #bfdbfe;
}

.stat-value {
  font-size: 28px;
  font-weight: 800;
  margin: 12px 0 4px 0;
  color: var(--text);
}

.stat-sub {
  font-size: 12px;
  color: var(--muted);
}

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

.status-badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
}

.status-success {
  background: rgba(34, 197, 94, 0.12);
  color: #15803d;
}

.status-warning {
  background: rgba(250, 204, 21, 0.14);
  color: #a16207;
}

.status-danger {
  background: rgba(239, 68, 68, 0.12);
  color: #b91c1c;
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