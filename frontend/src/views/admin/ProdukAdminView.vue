<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Logo Path & Fallback
const logoCemilku = ref('/images/cemilku-logo.png')

const handleLogoError = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
}

// Data Produk
const products = ref([])

const handleLogout = () => {
  localStorage.clear()
  router.push('/login')
}
</script>

<template>
  <div class="dashboard-layout">
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
      <!-- TOP NAVBAR / HEADER -->
      <header class="topbar">
        <div class="search-box">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Cari produk..." />
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
  display: flex;
  min-height: 100vh;
  background-color: #0f0f0f;
  color: #ffffff;
  font-family: system-ui, -apple-system, sans-serif;
  position: relative;
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
  background: linear-gradient(135deg, #fb923c, #ea580c);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 3px;
  flex-shrink: 0;
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
  color: #ffffff;
  letter-spacing: -0.05em;
  line-height: 1.1;
}

.brand-info small {
  font-size: 0.58rem;
  letter-spacing: 0.16em;
  color: #a1a1aa;
  font-weight: 600;
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

.banner-sub {
  color: #a1a1aa;
  font-size: 13px;
  margin: 6px 0 0 0;
}

/* ANIMATED BUTTON PRIMARY ACTION */
.btn-primary-action {
  background: linear-gradient(135deg, #fb923c, #ea580c);
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(234, 88, 12, 0.25);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-icon {
  display: inline-block;
  font-size: 16px;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-primary-action:hover {
  background: linear-gradient(135deg, #f97316, #c2410c);
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 8px 20px rgba(234, 88, 12, 0.45);
}

.btn-primary-action:hover .btn-icon {
  transform: rotate(90deg);
}

.btn-primary-action:active {
  transform: translateY(-1px) scale(0.98);
  box-shadow: 0 4px 10px rgba(234, 88, 12, 0.3);
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

.price-tag {
  font-weight: 700;
  color: #ea580c;
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
  background-color: #0284c7;
  color: #ffffff;
}

.btn-delete {
  background-color: #dc2626;
  color: #ffffff;
}

.empty-state {
  margin-top: 24px;
  background-color: #18181b;
  border: 1px solid #27272a;
  border-radius: 12px;
  padding: 30px 20px;
  text-align: center;
  color: #a1a1aa;
}

.empty-state p {
  margin: 0;
  font-size: 14px;
  font-weight: 500;
}
</style>