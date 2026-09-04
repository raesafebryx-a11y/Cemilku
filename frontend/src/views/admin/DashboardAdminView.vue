<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Data Ringkasan Stat
const stats = ref([
  { id: 1, label: 'Total Produk', value: '18', sub: '2 Kategori', icon: '📦' },
  { id: 2, label: 'Pesanan Masuk', value: '132', sub: '28 Selesai', icon: '🛒' },
  { id: 3, label: 'Pelanggan', value: '45', sub: '12 Aktif', icon: '👥' },
  { id: 4, label: 'Konversi WA', value: '86%', sub: '↑ 5% minggu ini', icon: '💬' }
])

// Data Tabel Produk Sample
const products = ref([
  { id: 1, name: 'Basreng Pedas Jeruk', category: 'Basreng', price: 'Rp 15.000', stock: 45, status: 'Tersedia' },
  { id: 2, name: 'Keripik Singkong Balado', category: 'Keripik', price: 'Rp 12.000', stock: 12, status: 'Stok Menipis' },
  { id: 3, name: 'Makaroni Pedas Bawang', category: 'Makaroni', price: 'Rp 10.000', stock: 80, status: 'Tersedia' },
  { id: 4, name: 'Seblak Kering Bantat', category: 'Keripik', price: 'Rp 12.000', stock: 0, status: 'Habis' }
])

const handleLogout = () => {
  localStorage.clear()
  router.push('/login')
}
</script>

<template>
  <div class="dashboard-layout">
    <!-- SIDEBAR KIRI -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <div class="brand-mark">C</div>
        <span class="brand-text">Cemilku UI</span>
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
            <button class="btn-primary-action">+ Tambah Produk</button>
          </div>
        </div>

        <!-- STAT CARDS -->
        <div class="stats-grid">
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
        <div class="table-card">
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
      </main>
    </div>
  </div>
</template>

<style scoped>
/* LAYOUT BASE */
.dashboard-layout {
  display: flex;
  min-height: 100vh;
  background-color: #0f0f0f;
  color: #ffffff;
  font-family: system-ui, -apple-system, sans-serif;
}

/* SIDEBAR KIRI */
.sidebar {
  width: 250px;
  background-color: #18181b;
  border-right: 1px solid #27272a;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
}

.sidebar-brand {
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid #27272a;
}

.brand-mark {
  background: #ea580c;
  color: #ffffff;
  font-weight: 800;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: grid;
  place-items: center;
}

.brand-text {
  font-weight: 700;
  font-size: 18px;
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
  color: #71717a;
  padding: 12px 12px 4px 12px;
  letter-spacing: 0.5px;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  color: #a1a1aa;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
  border-radius: 8px;
  transition: all 0.2s;
}

.menu-item:hover, .menu-item.router-link-active {
  background-color: #27272a;
  color: #ffffff;
}

.menu-item.router-link-active.active,
.menu-item.active {
  background-color: #ea580c;
  color: #ffffff;
}

.menu-item.logout {
  color: #ef4444;
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
  background-color: #18181b;
  border-bottom: 1px solid #27272a;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 28px;
}

.search-box {
  display: flex;
  align-items: center;
  gap: 8px;
  background-color: #27272a;
  padding: 8px 14px;
  border-radius: 8px;
  width: 300px;
}

.search-box input {
  background: transparent;
  border: none;
  outline: none;
  color: #ffffff;
  font-size: 13px;
  width: 100%;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 16px;
}

.icon-btn {
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 16px;
}

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: #ea580c;
  color: #ffffff;
  display: grid;
  place-items: center;
  font-weight: 700;
}

/* CONTENT BODY */
.content-body {
  padding: 0 28px 28px 28px;
}

/* DASHBOARD BANNER BAR */
.dashboard-banner {
  background: linear-gradient(135deg, #27272a 0%, #18181b 100%);
  border: 1px solid #3f3f46;
  border-radius: 12px;
  padding: 28px;
  margin-top: 24px;
  padding-bottom: 60px;
}

.banner-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.banner-content h1 {
  font-size: 22px;
  font-weight: 700;
  margin: 0;
}

.btn-primary-action {
  background-color: #ea580c;
  color: #ffffff;
  border: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-primary-action:hover {
  background-color: #c2410c;
}

/* STAT CARDS */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-top: -40px;
  padding: 0 12px;
}

.stat-card {
  background-color: #18181b;
  border: 1px solid #27272a;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
}

.stat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.stat-label {
  font-size: 13px;
  color: #a1a1aa;
  font-weight: 600;
}

.stat-icon-wrapper {
  background-color: #27272a;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: grid;
  place-items: center;
  font-size: 14px;
}

.stat-value {
  font-size: 28px;
  font-weight: 800;
  margin: 12px 0 4px 0;
}

.stat-sub {
  font-size: 12px;
  color: #71717a;
}

/* TABLE CARD */
.table-card {
  background-color: #18181b;
  border: 1px solid #27272a;
  border-radius: 12px;
  margin-top: 24px;
  overflow: hidden;
}

.table-header {
  padding: 20px 24px;
  border-bottom: 1px solid #27272a;
}

.table-header h3 {
  margin: 0;
  font-size: 16px;
  font-weight: 700;
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
  background-color: #27272a;
  color: #a1a1aa;
  padding: 14px 24px;
  font-weight: 600;
  font-size: 12px;
  text-transform: uppercase;
}

td {
  padding: 16px 24px;
  border-bottom: 1px solid #27272a;
  color: #d4d4d8;
}

.font-bold {
  font-weight: 600;
  color: #ffffff;
}

/* STATUS BADGES */
.status-badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
}

.status-success {
  background-color: rgba(34, 197, 94, 0.15);
  color: #4ade80;
}

.status-warning {
  background-color: rgba(234, 179, 8, 0.15);
  color: #facc15;
}

.status-danger {
  background-color: rgba(239, 68, 68, 0.15);
  color: #f87171;
}
</style>