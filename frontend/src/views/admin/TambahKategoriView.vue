<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// =========================================================
// TEMA SYSTEM (DIBACA DARI LOCALSTORAGE & EVENT LISTENER)
// =========================================================
const isAdminDark = ref(localStorage.getItem('admin-theme-mode') === 'dark')

onMounted(() => {
  const handleThemeChange = (event) => {
    const nextDark = event.detail?.dark ?? localStorage.getItem('admin-theme-mode') === 'dark'
    isAdminDark.value = nextDark
  }

  window.addEventListener('admin-theme-change', handleThemeChange)
})

// Logo Path & Fallback
const logoCemilku = ref('/images/cemilku-logo.png')

const handleLogoError = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
}

// =========================================================
// FORM KATEGORI
// =========================================================
const form = ref({
  name: '',
  description: ''
})

const buildAutoCategoryData = () => {
  let categories = []
  try {
    categories = JSON.parse(localStorage.getItem('cemilku_categories') || '[]')
  } catch (e) {
    categories = []
  }

  const baseName = 'Kategori Baru'
  const usedNames = new Set(categories.map((cat) => cat.name).filter(Boolean))

  let candidate = baseName
  let counter = 1
  while (usedNames.has(candidate)) {
    candidate = `${baseName} ${counter}`
    counter += 1
  }

  const createdAt = new Date().toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })

  return {
    name: candidate,
    description: `Kategori otomatis dibuat pada ${createdAt}.`
  }
}

onMounted(() => {
  const autoCategory = buildAutoCategoryData()
  form.value.name = autoCategory.name
  form.value.description = autoCategory.description
})

// =========================================================
// SIMPAN KATEGORI
// =========================================================
const handleSubmit = () => {
  const autoCategory = buildAutoCategoryData()
  form.value.name = autoCategory.name
  form.value.description = autoCategory.description

  let categories = []
  try {
    categories = JSON.parse(localStorage.getItem('cemilku_categories') || '[]')
  } catch (e) {
    categories = []
  }

  const newCategory = {
    id: Date.now(),
    name: form.value.name,
    description: form.value.description
  }

  categories.push(newCategory)

  localStorage.setItem('cemilku_categories', JSON.stringify(categories))

  alert('Kategori berhasil ditambahkan secara otomatis!')
  router.push('/admin/kategori')
}

// =========================================================
// KEMBALI
// =========================================================
const handleCancel = () => {
  router.push('/admin/kategori')
}

// =========================================================
// LOGOUT
// =========================================================
const handleLogout = () => {
  localStorage.clear()
  router.push('/login')
}
</script>

<template>
  <div class="dashboard-layout" :class="{ 'navbar-dark': isAdminDark }">
    <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <aside class="sidebar">
      <!-- BRAND CEMILKU -->
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

      <!-- MENU SIDEBAR -->
      <nav class="sidebar-menu">
        <div class="menu-category">MAIN</div>
        <router-link to="/admin" class="menu-item">
          <span class="menu-icon">📊</span>
          <span>Dashboard</span>
        </router-link>

        <div class="menu-category">KELOLA TOKO</div>
        <router-link to="/admin/produk" class="menu-item">
          <span class="menu-icon">🍿</span>
          <span>Produk</span>
        </router-link>

        <router-link to="/admin/kategori" class="menu-item active">
          <span class="menu-icon">🏷️</span>
          <span>Kategori</span>
        </router-link>

        <router-link to="/admin/order" class="menu-item">
          <span class="menu-icon">📑</span>
          <span>Order</span>
        </router-link>

        <router-link to="/admin/order-item" class="menu-item">
          <span class="menu-icon">📋</span>
          <span>Order Item</span>
        </router-link>

        <div class="menu-category">SISTEM</div>
        <router-link to="/admin/pengaturan" class="menu-item">
          <span class="menu-icon">⚙️</span>
          <span>Pengaturan</span>
        </router-link>

        <!-- LOGOUT -->
        <a href="#" class="menu-item logout" @click.prevent="handleLogout">
          <span class="menu-icon">🚪</span>
          <span>Keluar</span>
        </a>
      </nav>
    </aside>

    <!-- =====================================================
         MAIN WRAPPER
    ====================================================== -->
    <div class="main-wrapper">
      <!-- TOPBAR -->
      <header class="topbar">
        <div class="search-box">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Cari kategori..." />
        </div>

        <div class="topbar-right">
          <button type="button" class="icon-btn" title="Notifikasi">🔔</button>
          <div class="user-avatar">A</div>
        </div>
      </header>

      <!-- CONTENT -->
      <main class="content-body">
        <!-- BANNER -->
        <section class="dashboard-banner">
          <div class="banner-content">
            <div class="banner-left">
              <div class="breadcrumb">
                <span>Kategori</span>
                <b>›</b>
                <strong>Tambah Kategori</strong>
              </div>
              <h1>Tambah Kategori Baru</h1>
              <p class="banner-sub">
                Atur dan kelompokkan menu snack untuk mempermudah pencarian produk.
              </p>
            </div>

            <!-- KEMBALI -->
            <button type="button" class="btn-back" @click="handleCancel">
              <span>←</span> Kembali
            </button>
          </div>
        </section>

        <!-- FORM CARD -->
        <section class="form-card">
          <!-- HEADER FORM -->
          <div class="form-header">
            <div>
              <h3>Informasi Kategori</h3>
              <p>Nama dan deskripsi kategori akan dibuat otomatis oleh sistem.</p>
            </div>

            <!-- IKON KATEGORI -->
            <div class="form-icon">🏷️</div>
          </div>

          <!-- FORM -->
          <form class="product-form" @submit.prevent="handleSubmit">
            <!-- RINGKASAN -->
            <div v-if="form.name || form.description" class="product-summary">
              <div class="summary-title">Ringkasan Kategori</div>

              <div class="summary-grid">
                <div class="summary-item">
                  <span>Nama Kategori</span>
                  <strong>{{ form.name || '-' }}</strong>
                </div>

                <div class="summary-item full-summary">
                  <span>Deskripsi</span>
                  <strong>{{ form.description || 'Tidak ada deskripsi' }}</strong>
                </div>
              </div>
            </div>

            <!-- BUTTON -->
            <div class="form-actions">
              <button type="button" class="btn-cancel" @click="handleCancel">
                Batal
              </button>

              <button type="submit" class="btn-save">
                <span>✓</span> Simpan Kategori
              </button>
            </div>
          </form>
        </section>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* =========================================================
   CEMILKU ADMIN - DYNAMIC THEME VARIABLES
========================================================= */
.dashboard-layout {
  --page-bg: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
  --sidebar-bg: rgba(255, 255, 255, 0.82);
  --sidebar-border: rgba(226, 232, 240, 0.95);
  --topbar-bg: rgba(255, 255, 255, 0.78);
  --topbar-border: rgba(226, 232, 240, 0.95);
  --surface: rgba(255, 255, 255, 0.9);
  --input-bg: #ffffff;
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
}

.dashboard-layout.navbar-dark {
  --page-bg: linear-gradient(180deg, #0f172a 0%, #111827 100%);
  --sidebar-bg: rgba(15, 23, 42, 0.85);
  --sidebar-border: rgba(51, 65, 85, 0.9);
  --topbar-bg: rgba(15, 23, 42, 0.8);
  --topbar-border: rgba(51, 65, 85, 0.9);
  --surface: rgba(15, 23, 42, 0.82);
  --input-bg: #1e293b;
  --text: #f8fafc;
  --muted: #cbd5e1;
  --nav-text: #cbd5e1;
  --nav-hover: rgba(59, 130, 246, 0.12);
  --nav-active: linear-gradient(135deg, #1d4ed8, #3b82f6);
  --panel-border: rgba(51, 65, 85, 0.9);
}

/* =========================================================
   SIDEBAR
========================================================= */
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
  color: var(--text);
  font-size: 1.3rem;
  font-weight: 800;
  letter-spacing: -0.05em;
  line-height: 1.1;
}

.brand-info small {
  margin-top: 2px;
  color: #2563eb;
  font-size: 0.58rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
}

.sidebar-menu {
  display: flex;
  flex-direction: column;
  gap: 4px;
  padding: 20px 12px;
}

.menu-category {
  padding: 12px 12px 4px;
  color: var(--muted);
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.5px;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 10px;
  color: var(--nav-text);
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
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

/* =========================================================
   MAIN WRAPPER & TOPBAR
========================================================= */
.main-wrapper {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
  overflow-x: hidden;
}

.topbar {
  height: 64px;
  min-height: 64px;
  padding: 0 28px;
  background: var(--topbar-bg);
  border-bottom: 1px solid var(--topbar-border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  backdrop-filter: blur(14px);
}

.search-box {
  width: 300px;
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 14px;
  background: rgba(148, 163, 184, 0.06);
  border: 1px solid var(--panel-border);
  border-radius: 10px;
}

.search-icon {
  font-size: 13px;
  color: var(--muted);
}

.search-box input {
  width: 100%;
  padding: 0;
  border: none;
  outline: none;
  background: transparent;
  color: var(--text);
  font-size: 13px;
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

/* =========================================================
   CONTENT & BANNER
========================================================= */
.content-body {
  width: 100%;
  box-sizing: border-box;
  padding: 0 28px 40px;
}

.dashboard-banner {
  margin-top: 24px;
  padding: 28px;
  background: var(--surface);
  border: 1px solid var(--panel-border);
  border-radius: 16px;
  box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.05);
}

.banner-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

.banner-left {
  min-width: 0;
}

.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 8px;
  color: var(--muted);
  font-size: 12px;
}

.breadcrumb b {
  color: #2563eb;
  font-size: 16px;
  font-weight: 500;
}

.breadcrumb strong {
  color: var(--text);
  font-weight: 600;
}

.banner-content h1 {
  margin: 0;
  color: var(--text);
  font-size: 22px;
  font-weight: 800;
}

.banner-sub {
  margin: 6px 0 0;
  color: var(--muted);
  font-size: 13px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border: 1px solid var(--panel-border);
  border-radius: 10px;
  background: var(--surface);
  color: var(--text);
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-back:hover {
  background: var(--nav-hover);
  transform: translateX(-2px);
}

/* =========================================================
   FORM CARD & INPUTS
========================================================= */
.form-card {
  margin-top: 24px;
  padding: 28px;
  background: var(--surface);
  border: 1px solid var(--panel-border);
  border-radius: 16px;
}

.form-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 26px;
  padding-bottom: 22px;
  border-bottom: 1px solid var(--panel-border);
}

.form-header h3 {
  margin: 0;
  color: var(--text);
  font-size: 17px;
  font-weight: 800;
}

.form-header p {
  margin: 5px 0 0;
  color: var(--muted);
  font-size: 13px;
}

.form-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: rgba(37, 99, 235, 0.1);
  border: 1px solid rgba(37, 99, 235, 0.2);
  display: grid;
  place-items: center;
  font-size: 21px;
}

.product-form {
  width: 100%;
}

.form-group {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.form-group.full {
  margin-bottom: 22px;
}

.form-group label {
  margin-bottom: 8px;
  color: var(--text);
  font-size: 13px;
  font-weight: 600;
}

.form-group label span {
  color: #ef4444;
  margin-left: 2px;
}

.form-group input,
.form-group textarea {
  width: 100%;
  box-sizing: border-box;
  padding: 11px 14px;
  border: 1px solid var(--panel-border);
  border-radius: 10px;
  outline: none;
  background: var(--input-bg);
  color: var(--text);
  font-family: inherit;
  font-size: 13px;
  transition: all 0.2s ease;
}

.form-group input::placeholder,
.form-group textarea::placeholder {
  color: var(--muted);
}

.form-group textarea {
  min-height: 100px;
  resize: vertical;
  line-height: 1.6;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

.form-group small {
  margin-top: 6px;
  color: var(--muted);
  font-size: 11px;
  line-height: 1.4;
}

/* =========================================================
   SUMMARY & ACTIONS
========================================================= */
.product-summary {
  margin-bottom: 24px;
  padding: 18px;
  background: rgba(148, 163, 184, 0.05);
  border: 1px solid var(--panel-border);
  border-radius: 12px;
}

.summary-title {
  margin-bottom: 16px;
  color: var(--text);
  font-size: 13px;
  font-weight: 700;
}

.summary-grid {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 12px;
}

.summary-item {
  min-width: 0;
  padding: 14px;
  background: var(--surface);
  border: 1px solid var(--panel-border);
  border-radius: 10px;
}

.summary-item span {
  display: block;
  margin-bottom: 6px;
  color: var(--muted);
  font-size: 11px;
}

.summary-item strong {
  display: block;
  overflow: hidden;
  color: var(--text);
  font-size: 13px;
  font-weight: 700;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.form-actions {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;
  padding-top: 24px;
  border-top: 1px solid var(--panel-border);
}

.btn-cancel {
  padding: 11px 20px;
  border: 1px solid var(--panel-border);
  border-radius: 10px;
  background: var(--surface);
  color: var(--text);
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-cancel:hover {
  background: var(--nav-hover);
}

.btn-save {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 11px 24px;
  border: none;
  border-radius: 10px;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
  transition: all 0.25s ease;
}

.btn-save:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
}

.btn-save:active {
  transform: translateY(0);
}

/* =========================================================
   RESPONSIVE
========================================================= */
@media (max-width: 1000px) {
  .sidebar {
    width: 220px;
  }
}

@media (max-width: 800px) {
  .summary-grid {
    grid-template-columns: 1fr;
  }
  .banner-content {
    align-items: flex-start;
    flex-direction: column;
  }
  .btn-back {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 700px) {
  .sidebar {
    display: none;
  }
  .topbar {
    padding: 0 16px;
  }
  .search-box {
    width: 220px;
  }
  .content-body {
    padding: 0 16px 30px;
  }
  .dashboard-banner {
    padding: 20px;
  }
  .form-card {
    padding: 20px;
  }
  .form-actions {
    flex-direction: column-reverse;
    align-items: stretch;
  }
  .btn-cancel,
  .btn-save {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 450px) {
  .search-box {
    width: 160px;
  }
  .topbar-right {
    gap: 8px;
  }
  .dashboard-banner {
    padding: 18px;
  }
  .form-card {
    padding: 16px;
  }
  .banner-content h1 {
    font-size: 20px;
  }
}
</style>