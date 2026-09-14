<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Logo Path & Fallback
const logoCemilku = ref('/images/cemilku-logo.png')

const handleLogoError = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
}

// Data Form Pengaturan Toko & Sistem
const settings = ref({
  storeName: 'Cemilku Snack Store',
  whatsappNumber: '6281234567890',
  adminEmail: 'admin@cemilku.com',
  address: 'Jl. Camilan Lezat No. 12, Bandung',
  enableWaOrder: true,
  autoStockReduction: true
})

const isSaving = ref(false)
const saveSuccess = ref(false)

const loadSettings = () => {
  const saved = localStorage.getItem('cemilkuSettings')

  if (!saved) return

  try {
    const parsed = JSON.parse(saved)
    settings.value = { ...settings.value, ...parsed }
  } catch (error) {
    console.error('Gagal memuat pengaturan tersimpan:', error)
  }
}

const handleSave = () => {
  isSaving.value = true
  saveSuccess.value = false

  setTimeout(() => {
    localStorage.setItem('cemilkuSettings', JSON.stringify(settings.value))

    isSaving.value = false
    saveSuccess.value = true

    setTimeout(() => {
      saveSuccess.value = false
    }, 3000)
  }, 800)
}

onMounted(() => {
  loadSettings()
})

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
        <router-link to="/admin/pengaturan" class="menu-item active">
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
          <input type="text" placeholder="Cari di pengaturan..." />
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
              <h1>Pengaturan Sistem</h1>
              <p class="banner-sub">Kelola informasi toko, kontak WhatsApp, dan preferensi aplikasi.</p>
            </div>
            <button 
              class="btn-primary-action" 
              :disabled="isSaving" 
              @click="handleSave"
            >
              <span class="btn-icon">💾</span> 
              {{ isSaving ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>
        </div>

        <!-- NOTIFIKASI SUKSES -->
        <div v-if="saveSuccess" class="alert-success">
          ✨ Pengaturan berhasil diperbarui!
        </div>

        <!-- SETTINGS FORM CARD -->
        <div class="form-card">
          <div class="card-header">
            <h3>Informasi Toko & Kontak</h3>
          </div>
          
          <form @submit.prevent="handleSave" class="settings-form">
            <div class="form-grid">
              <div class="form-group">
                <label>Nama Toko</label>
                <input 
                  type="text" 
                  v-model="settings.storeName" 
                  placeholder="Masukkan nama toko"
                />
              </div>

              <div class="form-group">
                <label>Nomor WhatsApp Admin (Format 62)</label>
                <input 
                  type="text" 
                  v-model="settings.whatsappNumber" 
                  placeholder="628xxxxxxxxxx"
                />
              </div>

              <div class="form-group">
                <label>Email Admin</label>
                <input 
                  type="email" 
                  v-model="settings.adminEmail" 
                  placeholder="admin@cemilku.com"
                />
              </div>

              <div class="form-group full-width">
                <label>Alamat Toko</label>
                <textarea 
                  rows="3" 
                  v-model="settings.address" 
                  placeholder="Alamat lengkap toko"
                ></textarea>
              </div>
            </div>

            <div class="divider"></div>

            <div class="card-header border-none padding-zero">
              <h3>Preferensi Pesanan</h3>
            </div>

            <div class="toggle-group">
              <div class="toggle-item">
                <div class="toggle-info">
                  <span class="toggle-title">Integrasi Order WhatsApp</span>
                  <span class="toggle-desc">Arahkan checkout pelanggan langsung ke aplikasi WhatsApp Admin.</span>
                </div>
                <label class="switch">
                  <input type="checkbox" v-model="settings.enableWaOrder" />
                  <span class="slider"></span>
                </label>
              </div>

              <div class="toggle-item">
                <div class="toggle-info">
                  <span class="toggle-title">Pengurangan Stok Otomatis</span>
                  <span class="toggle-desc">Kurangi stok produk secara otomatis saat pesanan dibuat.</span>
                </div>
                <label class="switch">
                  <input type="checkbox" v-model="settings.autoStockReduction" />
                  <span class="slider"></span>
                </label>
              </div>
            </div>
          </form>
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
  font-size: 15px;
}

.btn-primary-action:hover:not(:disabled) {
  background: linear-gradient(135deg, #f97316, #c2410c);
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 8px 20px rgba(234, 88, 12, 0.45);
}

.btn-primary-action:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* NOTIFIKASI */
.alert-success {
  margin-top: 16px;
  background-color: rgba(34, 197, 94, 0.15);
  color: #4ade80;
  border: 1px solid rgba(34, 197, 94, 0.3);
  padding: 12px 18px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
}

/* FORM CARD */
.form-card {
  background-color: #18181b;
  border: 1px solid #27272a;
  border-radius: 12px;
  margin-top: 24px;
  padding: 24px;
}

.card-header {
  border-bottom: 1px solid #27272a;
  padding-bottom: 16px;
  margin-bottom: 24px;
}

.card-header.border-none {
  border-bottom: none;
}

.card-header.padding-zero {
  padding-bottom: 0;
}

.card-header h3 {
  margin: 0;
  font-size: 16px;
  font-weight: 700;
}

.settings-form {
  display: flex;
  flex-direction: column;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group.full-width {
  grid-column: span 2;
}

.form-group label {
  font-size: 13px;
  font-weight: 600;
  color: #a1a1aa;
}

.form-group input,
.form-group textarea {
  background-color: #27272a;
  border: 1px solid #3f3f46;
  border-radius: 8px;
  padding: 10px 14px;
  color: #ffffff;
  font-size: 14px;
  outline: none;
  transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: #ea580c;
}

.divider {
  height: 1px;
  background-color: #27272a;
  margin: 28px 0;
}

/* TOGGLE SWITCHES */
.toggle-group {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-top: 16px;
}

.toggle-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #27272a;
  padding: 16px 20px;
  border-radius: 8px;
  border: 1px solid #3f3f46;
}

.toggle-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.toggle-title {
  font-size: 14px;
  font-weight: 600;
  color: #ffffff;
}

.toggle-desc {
  font-size: 12px;
  color: #a1a1aa;
}

/* SWITCH UI */
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #3f3f46;
  transition: 0.3s;
  border-radius: 24px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.3s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #ea580c;
}

input:checked + .slider:before {
  transform: translateX(20px);
}

@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .form-group.full-width {
    grid-column: span 1;
  }
}
</style>