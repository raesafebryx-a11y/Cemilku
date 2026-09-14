<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const router = useRouter()

// Logo Path & Fallback
const logoCemilku = ref('/images/cemilku-logo.png')
const handleLogoError = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
}

// State Admin & Profil Gambar (Default menggunakan logo Cemilku jika belum diset)
const adminAvatar = ref(logoCemilku.value)
const fileInputRef = ref(null)

const loadAdminData = () => {
  const savedAvatar = localStorage.getItem('adminAvatar')
  if (savedAvatar) {
    adminAvatar.value = savedAvatar
  } else {
    // Jika belum ada foto profil kustom, gunakan logo utama sebagai default avatar
    adminAvatar.value = logoCemilku.value
  }
}

onMounted(() => {
  loadAdminData()
})

// Trigger klik input file tersembunyi
const triggerImageUpload = () => {
  fileInputRef.value.click()
}

// Proses file gambar (ubah avatar admin)
const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 2 * 1024 * 1024) {
      Swal.fire({
        icon: 'warning',
        title: 'Ukuran Terlalu Besar',
        text: 'Pilih gambar dengan ukuran maksimal 2MB.',
        confirmButtonColor: '#ea580c',
        background: '#1e293b',
        color: '#fff'
      })
      return
    }

    const reader = new FileReader()
    reader.onload = (e) => {
      const base64Image = e.target.result
      adminAvatar.value = base64Image
      localStorage.setItem('adminAvatar', base64Image)

      Swal.fire({
        icon: 'success',
        title: 'Foto Profil Diperbarui',
        text: 'Avatar admin berhasil diubah dan tersimpan di semua halaman!',
        timer: 1500,
        showConfirmButton: false,
        background: '#1e293b',
        color: '#fff'
      })
    }
    reader.readAsDataURL(file)
  }
}

// Fungsi Logout dengan Konfirmasi SweetAlert2
const handleLogout = () => {
  Swal.fire({
    title: 'Konfirmasi Keluar',
    text: 'Apakah Anda yakin ingin keluar dari akun admin?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#ea580c',
    cancelButtonColor: '#475569',
    confirmButtonText: 'Ya, Keluar',
    cancelButtonText: 'Batal',
    background: '#1e293b',
    color: '#fff'
  }).then((result) => {
    if (result.isConfirmed) {
      localStorage.removeItem('isLoggedIn')
      localStorage.removeItem('userRole')
      localStorage.removeItem('role')
      localStorage.removeItem('username')
      localStorage.removeItem('user')

      Swal.fire({
        icon: 'success',
        title: 'Berhasil Keluar 👋',
        text: 'Anda telah keluar dari sesi admin.',
        timer: 1800,
        showConfirmButton: false,
        timerProgressBar: true,
        background: '#1e293b',
        color: '#fff'
      }).then(() => {
        router.push('/')
        window.location.reload()
      })
    }
  })
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
        <router-link to="/admin" class="menu-item" exact-active-class="active">
          <span class="menu-icon">📊</span> Dashboard
        </router-link>

        <div class="menu-category">KELOLA TOKO</div>
        <router-link to="/admin/produk" class="menu-item" active-class="active">
          <span class="menu-icon">🍿</span> Produk
        </router-link>
        
        <router-link to="/admin/kategori" class="menu-item" active-class="active">
          <span class="menu-icon">🏷️</span> Kategori
        </router-link>

        <router-link to="/admin/order" class="menu-item" active-class="active">
          <span class="menu-icon">📑</span> Order
        </router-link>

        <router-link to="/admin/order-item" class="menu-item" active-class="active">
          <span class="menu-icon">📋</span> Order Item
        </router-link>

        <div class="menu-category">SISTEM</div>
        <router-link to="/admin/pengaturan" class="menu-item" active-class="active">
          <span class="menu-icon">⚙️</span> Pengaturan
        </router-link>
        
        <a href="#" @click.prevent="handleLogout" class="menu-item logout">
          <span class="menu-icon">🚪</span> Keluar
        </a>
      </nav>
    </aside>

    <!-- KONTEN UTAMA -->
    <div class="main-wrapper">
      <!-- TOP NAVBAR -->
      <header class="topbar">
        <div class="search-box">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Cari produk atau pesanan..." />
        </div>

        <div class="topbar-right">
          <button class="icon-btn-rounded" title="Notifikasi">
            🔔
          </button>
          
          <!-- INPUT FILE TERSEMBUNYI -->
          <input 
            type="file" 
            ref="fileInputRef" 
            @change="handleImageChange" 
            accept="image/*" 
            style="display: none;" 
          />

          <!-- AVATAR GAMBAR (SELALU MENAMPILKAN LOGO / GAMBAR) -->
          <div 
            class="user-avatar-container" 
            @click="triggerImageUpload" 
            title="Klik untuk mengubah foto profil admin"
          >
            <img 
              :src="adminAvatar" 
              alt="Avatar Admin" 
              class="avatar-img-preview" 
              @error="handleLogoError"
            />
            <div class="avatar-overlay-hint">📷</div>
          </div>
        </div>
      </header>

      <!-- SLOT HALAMAN ADMIN -->
      <main class="content-body">
        <router-view />
      </main>
    </div>
  </div>
</template>

<style scoped>
.dashboard-layout {
  display: flex;
  min-height: 100vh;
  background-color: #0b0c10;
  color: #f3f4f6;
  font-family: Inter, system-ui, -apple-system, sans-serif;
  position: relative;
}

.sidebar {
  width: 240px;
  background-color: #12151c;
  border-right: 1px solid #1f2430;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
}

.sidebar-brand {
  padding: 18px 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid #1f2430;
  cursor: pointer;
}

.brand-mark {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: linear-gradient(135deg, #f97316, #ea580c);
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

.brand-text {
  font-weight: 800;
  font-size: 1.25rem;
  color: #ffffff;
  letter-spacing: -0.04em;
  line-height: 1.1;
}

.brand-info small {
  font-size: 0.55rem;
  letter-spacing: 0.16em;
  color: #9ca3af;
  font-weight: 600;
  text-transform: uppercase;
  margin-top: 2px;
}

.sidebar-menu {
  padding: 16px 12px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.menu-category {
  font-size: 10px;
  font-weight: 700;
  color: #4b5563;
  padding: 14px 12px 6px 12px;
  letter-spacing: 0.6px;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 11px 14px;
  color: #9ca3af;
  text-decoration: none;
  font-size: 13.5px;
  font-weight: 500;
  border-radius: 10px;
  transition: all 0.25s ease;
}

.menu-item:hover {
  background-color: #1a1f2c;
  color: #ffffff;
  transform: translateX(3px);
}

.menu-item.active {
  background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
  color: #ffffff;
  font-weight: 600;
  box-shadow: 0 4px 14px rgba(234, 88, 12, 0.35);
}

.menu-item.logout {
  color: #f87171;
}

.menu-item.logout:hover {
  background-color: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.main-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.topbar {
  height: 64px;
  background-color: #12151c;
  border-bottom: 1px solid #1f2430;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 28px;
}

.search-box {
  display: flex;
  align-items: center;
  gap: 10px;
  background-color: #1a1e29;
  border: 1px solid #282f42;
  padding: 8px 14px;
  border-radius: 8px;
  width: 320px;
}

.search-icon {
  font-size: 13px;
  opacity: 0.6;
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
  gap: 12px;
}

.icon-btn-rounded {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  background-color: #1a1e29;
  border: 1px solid #282f42;
  color: #9ca3af;
  display: grid;
  place-items: center;
  cursor: pointer;
  font-size: 14px;
}

.user-avatar-container {
  position: relative;
  width: 34px;
  height: 34px;
  border-radius: 50%;
  cursor: pointer;
  overflow: hidden;
  border: 2px solid #f97316;
  background-color: #1a1e29;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.user-avatar-container:hover {
  transform: scale(1.08);
  box-shadow: 0 0 12px rgba(249, 115, 22, 0.5);
}

.avatar-img-preview {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.avatar-overlay-hint {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  opacity: 0;
  transition: opacity 0.2s ease;
}

.user-avatar-container:hover .avatar-overlay-hint {
  opacity: 1;
}

.content-body {
  padding: 24px 28px;
  flex: 1;
}
</style>