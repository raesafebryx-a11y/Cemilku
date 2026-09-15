<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const router = useRouter()

// Status Auth & User Info
const isLoggedIn = ref(false)
const userRole = ref('')
const username = ref('')
const email = ref('')
const phone = ref('')
const address = ref('')
const joinedDate = ref('')

// State Foto Profil (Tersimpan di localStorage)
const profileImage = ref(localStorage.getItem('profileImage') || '')

// Dark Mode State
const isDarkMode = ref(false)

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light')
}

// Logo Path & Fallback
const logoCemilku = ref('/images/cemilku-logo.png')

const handleLogoError = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
}

// Cek status login, profil & tema saat komponen dimuat
const checkAuthStatus = () => {
  const loggedIn = localStorage.getItem('isLoggedIn') === 'true'
  if (!loggedIn) {
    // Jika belum login, alihkan ke halaman login
    router.push('/login')
    return
  }

  isLoggedIn.value = true
  userRole.value = localStorage.getItem('userRole') || localStorage.getItem('role') || 'user'
  username.value = localStorage.getItem('username') || localStorage.getItem('user') || 'Pengguna Cemilku'
  email.value = localStorage.getItem('email') || `${username.value.toLowerCase().replace(/\s+/g, '')}@example.com`
  phone.value = localStorage.getItem('phone') || '+62 812-3456-7890'
  address.value = localStorage.getItem('address') || 'Jl. Raya Camilan No. 123, Bandung, Jawa Barat'
  joinedDate.value = localStorage.getItem('joinedDate') || 'Januari 2026'
  
  // Cek mode gelap yang tersimpan
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme === 'dark') {
    isDarkMode.value = true
  }
}

onMounted(() => {
  checkAuthStatus()
})

// Fungsi Ubah Foto Profil (Base64 storage)
const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (!file) return

  // Validasi ukuran gambar (Maksimal 2MB)
  if (file.size > 2 * 1024 * 1024) {
    const isDark = isDarkMode.value
    Swal.fire({
      icon: 'error',
      title: 'Ukuran Gambar Terlalu Besar',
      text: 'Maksimal ukuran foto profil adalah 2MB.',
      background: isDark ? '#1e293b' : '#ffffff',
      color: isDark ? '#f8fafc' : '#1e293b'
    })
    return
  }

  // Membaca file gambar dan mengonversi ke URL Base64
  const reader = new FileReader()
  reader.onload = (e) => {
    profileImage.value = e.target.result
    localStorage.setItem('profileImage', profileImage.value)
    
    const isDark = isDarkMode.value
    Swal.fire({
      icon: 'success',
      title: 'Foto Profil Diperbarui ✨',
      text: 'Foto profil Anda berhasil diubah.',
      timer: 1800,
      showConfirmButton: false,
      background: isDark ? '#1e293b' : '#ffffff',
      color: isDark ? '#f8fafc' : '#1e293b'
    })
  }
  reader.readAsDataURL(file)
}

// Fungsi Edit Profil Sederhana
const isEditing = ref(false)
const editForm = ref({
  username: '',
  phone: '',
  address: ''
})

const startEdit = () => {
  editForm.value = {
    username: username.value,
    phone: phone.value,
    address: address.value
  }
  isEditing.value = true
}

const saveProfile = () => {
  username.value = editForm.value.username
  phone.value = editForm.value.phone
  address.value = editForm.value.address

  localStorage.setItem('username', username.value)
  localStorage.setItem('user', username.value)
  localStorage.setItem('phone', phone.value)
  localStorage.setItem('address', address.value)

  isEditing.value = false

  const isDark = isDarkMode.value
  Swal.fire({
    icon: 'success',
    title: 'Profil Diperbarui ✨',
    text: 'Informasi profil Anda berhasil disimpan.',
    timer: 1800,
    showConfirmButton: false,
    background: isDark ? '#1e293b' : '#ffffff',
    color: isDark ? '#f8fafc' : '#1e293b'
  })
}

// Fungsi Logout dengan SweetAlert2
const handleLogout = () => {
  const isDark = isDarkMode.value
  Swal.fire({
    title: 'Konfirmasi Keluar',
    text: 'Apakah Anda yakin ingin keluar dari akun ini?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#2563eb',
    cancelButtonColor: '#94a3b8',
    confirmButtonText: 'Ya, Keluar',
    cancelButtonText: 'Batal',
    background: isDark ? '#1e293b' : '#ffffff',
    color: isDark ? '#f8fafc' : '#1e293b'
  }).then((result) => {
    if (result.isConfirmed) {
      localStorage.removeItem('isLoggedIn')
      localStorage.removeItem('userRole')
      localStorage.removeItem('role')
      localStorage.removeItem('username')
      localStorage.removeItem('user')

      isLoggedIn.value = false
      username.value = ''
      userRole.value = ''

      Swal.fire({
        icon: 'success',
        title: 'Berhasil Keluar 👋',
        text: 'Anda telah keluar dari akun.',
        timer: 1800,
        showConfirmButton: false,
        timerProgressBar: true,
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f8fafc' : '#1e293b'
      }).then(() => {
        router.push('/')
        window.location.reload()
      })
    }
  })
}
</script>

<template>
  <div class="profile-wrapper" :class="{ 'dark-mode': isDarkMode }">
    <!-- DEKORASI GELOMBANG & ORNAMEN BIRU -->
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>
    <div class="bg-shape bg-shape-3"></div>

    <!-- NAVBAR GLASSMORPHISM -->
    <header class="navbar">
      <div class="container nav-content">
        <!-- BRAND LOGO -->
        <div class="brand-logo" @click="router.push('/')">
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

        <!-- NAV NAVIGATION MENU -->
        <nav class="nav-links">
          <button class="nav-btn" @click="router.push('/')">
            Produk
          </button>
          <button class="nav-btn" @click="router.push('/tentang-kami')">
            Tentang Kami
          </button>
          <button class="nav-btn" @click="router.push('/kontak')">
            Kontak
          </button>
          <button 
            v-if="userRole === 'admin'" 
            class="nav-btn btn-dashboard" 
            @click="router.push('/admin')"
          >
            Dashboard
          </button>
        </nav>

        <!-- AUTH BUTTONS & THEME TOGGLE SECTION -->
        <div class="nav-auth">
          <!-- TOGGLE DARK MODE BUTTON -->
          <button class="btn-theme-toggle" @click="toggleDarkMode" :title="isDarkMode ? 'Mode Terang' : 'Mode Gelap'">
            <span v-if="isDarkMode">☀️</span>
            <span v-else>🌙</span>
          </button>

          <template v-if="isLoggedIn">
            <div class="user-greeting">
              <span class="greet-label">Selamat datang,</span>
              <strong class="user-email">{{ username }}</strong>
            </div>

            <!-- BUTTON PROFIL (AKTIF) -->
            <button class="btn-profile active" @click="router.push('/profile')">
              👤 Profil
            </button>

            <button class="btn-logout" @click="handleLogout">
              Keluar
            </button>
          </template>

          <template v-else>
            <button class="btn-outline" @click="router.push('/login')">
              Masuk
            </button>
            <button class="btn-primary" @click="router.push('/register')">
              Daftar
            </button>
          </template>
        </div>
      </div>
    </header>

    <!-- MAIN PROFILE SECTION -->
    <main class="main-content">
      <div class="container">
        
        <div class="profile-card-container">
          <!-- HEADER CARD -->
          <div class="profile-header-card">
            <!-- AVATAR BOX & UBAH FOTO PROFIL -->
            <div class="avatar-wrapper">
              <div class="avatar-box">
                <img v-if="profileImage" :src="profileImage" alt="Foto Profil" class="profile-img" />
                <span v-else>{{ username ? username.charAt(0).toUpperCase() : 'U' }}</span>
              </div>

              <!-- Input file tersembunyi untuk foto -->
              <input
                type="file"
                ref="fileInput"
                accept="image/*"
                class="file-input-hidden"
                @change="handleImageUpload"
              />

              <!-- Tombol Ubah Foto -->
              <button 
                class="btn-change-photo" 
                @click="$refs.fileInput.click()" 
                title="Ubah Foto Profil"
              >
                📷
              </button>

              <span class="role-badge">{{ userRole.toUpperCase() }}</span>
            </div>
            
            <div class="user-main-info">
              <h1>{{ username }}</h1>
              <p>{{ email }}</p>
              <small>Anggota sejak: {{ joinedDate }}</small>
            </div>
          </div>

          <!-- DETAIL INFORMASI & EDIT FORM -->
          <div class="profile-body-card">
            <div class="body-header">
              <h2>Informasi Akun</h2>
              <button v-if="!isEditing" class="btn-edit" @click="startEdit">
                ✏️ Edit Profil
              </button>
            </div>

            <!-- TAMPILAN VIEW DATA -->
            <div v-if="!isEditing" class="info-grid">
              <div class="info-item">
                <label>Nama Pengguna</label>
                <p>{{ username }}</p>
              </div>

              <div class="info-item">
                <label>Alamat Email</label>
                <p>{{ email }}</p>
              </div>

              <div class="info-item">
                <label>Nomor Telepon</label>
                <p>{{ phone }}</p>
              </div>

              <div class="info-item full-width">
                <label>Alamat Pengiriman</label>
                <p>{{ address }}</p>
              </div>
            </div>

            <!-- TAMPILAN FORM EDIT DATA -->
            <form v-else @submit.prevent="saveProfile" class="edit-form">
              <div class="form-group">
                <label>Nama Pengguna</label>
                <input v-model="editForm.username" type="text" required />
              </div>

              <div class="form-group">
                <label>Nomor Telepon</label>
                <input v-model="editForm.phone" type="text" required />
              </div>

              <div class="form-group full-width">
                <label>Alamat Pengiriman</label>
                <textarea v-model="editForm.address" rows="3" required></textarea>
              </div>

              <div class="form-actions">
                <button type="button" class="btn-cancel" @click="isEditing = false">
                  Batal
                </button>
                <button type="submit" class="btn-save">
                  Simpan Perubahan
                </button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </main>

    <!-- FOOTER LIGHT ICE BLUE -->
    <footer class="footer">
      <div class="container footer-content">
        <div class="footer-top">
          <div class="footer-brand">
            <div class="brand-mark small">
              <img :src="logoCemilku" alt="Logo" class="cemilku-logo-img" @error="handleLogoError" />
            </div>
            <span class="brand-text">Cemilku</span>
          </div>
          <p class="footer-desc">Pusat camilan renyah dan nikmat dengan pengiriman praktis ke seluruh Indonesia.</p>
        </div>
        <div class="footer-bottom">
          <small>© 2026 Cemilku Snack Store. All rights reserved.</small>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
/* GLOBAL & BASE STYLES */
.profile-wrapper {
  min-height: 100vh;
  background-color: #f6f8fb;
  color: #1e293b;
  position: relative;
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  display: flex;
  flex-direction: column;
  overflow-x: hidden;
  scroll-behavior: smooth;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  width: 100%;
  box-sizing: border-box;
}

/* SOFT ICE BLUE BACKGROUND SHAPES */
.bg-shape {
  position: absolute;
  border-radius: 50%;
  filter: blur(140px);
  pointer-events: none;
  z-index: 0;
  opacity: 0.7;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.bg-shape-1 {
  top: -120px;
  right: -80px;
  width: 550px;
  height: 550px;
  background: radial-gradient(circle, rgba(147, 197, 253, 0.45) 0%, rgba(191, 219, 254, 0.15) 70%);
}

.bg-shape-2 {
  top: 350px;
  left: -120px;
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(96, 165, 250, 0.3) 0%, rgba(224, 242, 254, 0.1) 70%);
}

.bg-shape-3 {
  bottom: 100px;
  right: 15%;
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(186, 230, 253, 0.4) 0%, rgba(240, 249, 255, 0.05) 70%);
}

/* NAVBAR GLASSMORPHISM */
.navbar {
  position: sticky;
  top: 0;
  z-index: 50;
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  background: rgba(255, 255, 255, 0.75);
  border-bottom: 1px solid rgba(226, 232, 240, 0.8);
  transition: all 0.3s ease;
}

.nav-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  height: 76px;
}

.brand-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.brand-logo:hover {
  transform: translateY(-2px) scale(1.02);
}

.brand-mark {
  width: 42px;
  height: 42px;
  border-radius: 14px;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 6px;
  box-shadow: 0 8px 16px -4px rgba(37, 99, 235, 0.3);
}

.brand-mark.small {
  width: 34px;
  height: 34px;
  border-radius: 10px;
}

.cemilku-logo-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}

.brand-text {
  color: #0f172a;
  font-size: 1.35rem;
  font-weight: 800;
  letter-spacing: -0.03em;
  line-height: 1;
}

.brand-info small {
  display: block;
  margin-top: 3px;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: #2563eb;
  font-size: 0.6rem;
  font-weight: 800;
}

/* NAVIGATION MENU */
.nav-links {
  display: flex;
  align-items: center;
  gap: 4px;
  background: rgba(241, 245, 249, 0.7);
  padding: 5px;
  border-radius: 14px;
  border: 1px solid #e2e8f0;
}

.nav-btn {
  background: transparent;
  border: none;
  color: #64748b;
  padding: 8px 18px;
  font-size: 0.85rem;
  font-weight: 600;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-btn:hover {
  color: #2563eb;
  background: rgba(255, 255, 255, 0.5);
}

.btn-dashboard {
  background: #2563eb !important;
  color: #ffffff !important;
}

.btn-dashboard:hover {
  background: #1d4ed8 !important;
  box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
}

/* USER AUTH & THEME BUTTONS */
.nav-auth {
  display: flex;
  align-items: center;
  gap: 12px;
}

.btn-theme-toggle {
  background: rgba(241, 245, 249, 0.8);
  border: 1px solid #e2e8f0;
  width: 38px;
  height: 38px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 1.1rem;
  transition: all 0.25s ease;
}

.btn-theme-toggle:hover {
  transform: scale(1.08);
}

.user-greeting {
  display: flex;
  flex-direction: column;
  text-align: right;
  line-height: 1.2;
}

.greet-label {
  font-size: 0.65rem;
  color: #64748b;
}

.user-email {
  font-size: 0.85rem;
  color: #1e293b;
  font-weight: 700;
}

.btn-profile {
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  color: #2563eb;
  padding: 8px 16px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.25s ease;
}

.btn-profile:hover,
.btn-profile.active {
  background: #2563eb;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

.btn-logout {
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #dc2626;
  padding: 8px 18px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.25s ease;
}

.btn-logout:hover {
  background: #dc2626;
  color: #ffffff;
  box-shadow: 0 6px 14px rgba(220, 38, 38, 0.2);
}

.btn-outline {
  background: #ffffff;
  border: 1px solid #cbd5e1;
  color: #334155;
  padding: 8px 20px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.25s ease;
}

.btn-outline:hover {
  border-color: #3b82f6;
  color: #2563eb;
  background: #f0f7ff;
}

.btn-primary {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  border: none;
  color: #ffffff;
  padding: 8px 20px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
  transition: all 0.25s ease;
}

.btn-primary:hover {
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
  transform: translateY(-1.5px);
}

/* MAIN CONTENT SECTION */
.main-content {
  flex: 1;
  padding: 50px 0 80px;
  position: relative;
  z-index: 1;
}

/* PROFILE CARD DESIGN */
.profile-card-container {
  max-width: 800px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.profile-header-card {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(226, 232, 240, 0.9);
  border-radius: 24px;
  padding: 32px;
  display: flex;
  align-items: center;
  gap: 24px;
  box-shadow: 0 10px 30px -10px rgba(37, 99, 235, 0.08);
}

.avatar-wrapper {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}

.avatar-box {
  width: 96px;
  height: 96px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  font-size: 2.6rem;
  font-weight: 800;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
  overflow: hidden;
}

.profile-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.file-input-hidden {
  display: none;
}

.btn-change-photo {
  position: absolute;
  top: 0;
  right: -4px;
  background: #2563eb;
  color: #ffffff;
  border: 2px solid #ffffff;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  font-size: 0.85rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  transition: transform 0.2s ease, background-color 0.2s ease;
  z-index: 2;
}

.btn-change-photo:hover {
  transform: scale(1.12);
  background: #1d4ed8;
}

.role-badge {
  position: absolute;
  bottom: -6px;
  left: 50%;
  transform: translateX(-50%);
  background: #10b981;
  color: #ffffff;
  font-size: 0.65rem;
  font-weight: 800;
  padding: 2px 10px;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
  white-space: nowrap;
  z-index: 2;
}

.user-main-info h1 {
  font-size: 1.6rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0 0 4px 0;
}

.user-main-info p {
  color: #64748b;
  margin: 0 0 6px 0;
  font-size: 0.95rem;
}

.user-main-info small {
  color: #94a3b8;
  font-size: 0.8rem;
}

/* PROFILE BODY CARD */
.profile-body-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 24px;
  padding: 32px;
  box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.03);
}

.body-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.body-header h2 {
  font-size: 1.3rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
}

.btn-edit {
  background: #f1f5f9;
  border: 1px solid #cbd5e1;
  color: #334155;
  padding: 8px 16px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.25s ease;
}

.btn-edit:hover {
  background: #2563eb;
  color: #ffffff;
  border-color: #2563eb;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.info-item {
  background: #f8fafc;
  padding: 16px;
  border-radius: 14px;
  border: 1px solid #f1f5f9;
}

.info-item.full-width {
  grid-column: span 2;
}

.info-item label {
  display: block;
  font-size: 0.75rem;
  font-weight: 700;
  color: #64748b;
  margin-bottom: 4px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.info-item p {
  margin: 0;
  font-size: 0.95rem;
  font-weight: 600;
  color: #0f172a;
}

/* EDIT FORM */
.edit-form {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group.full-width {
  grid-column: span 2;
}

.form-group label {
  font-size: 0.8rem;
  font-weight: 700;
  color: #475569;
}

.form-group input,
.form-group textarea {
  padding: 12px 14px;
  border: 1.5px solid #cbd5e1;
  border-radius: 12px;
  font-size: 0.9rem;
  color: #0f172a;
  outline: none;
  font-family: inherit;
  transition: all 0.25s ease;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.form-actions {
  grid-column: span 2;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 10px;
}

.btn-cancel {
  background: #f1f5f9;
  border: 1px solid #cbd5e1;
  color: #475569;
  padding: 10px 20px;
  border-radius: 12px;
  font-weight: 700;
  cursor: pointer;
}

.btn-save {
  background: #2563eb;
  border: none;
  color: #ffffff;
  padding: 10px 20px;
  border-radius: 12px;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

/* FOOTER */
.footer {
  border-top: 1px solid #e2e8f0;
  padding: 44px 0 24px;
  background: #ffffff;
  margin-top: auto;
  transition: all 0.3s ease;
}

.footer-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 20px;
}

.footer-top {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}

.footer-brand {
  display: flex;
  align-items: center;
  gap: 8px;
}

.footer-desc {
  color: #64748b;
  font-size: 0.875rem;
  max-width: 440px;
  margin: 0;
}

.footer-bottom {
  border-top: 1px solid #f1f5f9;
  width: 100%;
  padding-top: 18px;
}

.footer-bottom small {
  color: #94a3b8;
  font-size: 0.75rem;
}

/* ============================================================ */
/* DARK MODE STYLES                                             */
/* ============================================================ */
.dark-mode {
  background-color: #0f172a !important;
  color: #f8fafc !important;
}

.dark-mode .bg-shape-1 {
  background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, rgba(15, 23, 42, 0.1) 70%);
}

.dark-mode .bg-shape-2 {
  background: radial-gradient(circle, rgba(29, 78, 216, 0.2) 0%, rgba(15, 23, 42, 0.05) 70%);
}

.dark-mode .navbar {
  background: rgba(15, 23, 42, 0.85) !important;
  border-bottom-color: rgba(51, 65, 85, 0.8) !important;
}

.dark-mode .brand-text {
  color: #f8fafc !important;
}

.dark-mode .nav-links {
  background: rgba(30, 41, 59, 0.7) !important;
  border-color: #334155 !important;
}

.dark-mode .nav-btn {
  color: #94a3b8 !important;
}

.dark-mode .nav-btn:hover {
  color: #60a5fa !important;
  background: rgba(51, 65, 85, 0.5) !important;
}

.dark-mode .btn-theme-toggle {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #f8fafc !important;
}

.dark-mode .user-email {
  color: #f8fafc !important;
}

.dark-mode .greet-label {
  color: #94a3b8 !important;
}

.dark-mode .btn-profile {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #60a5fa !important;
}

.dark-mode .btn-profile.active,
.dark-mode .btn-profile:hover {
  background: #2563eb !important;
  color: #ffffff !important;
}

.dark-mode .btn-outline {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #cbd5e1 !important;
}

.dark-mode .btn-outline:hover {
  background: #334155 !important;
  color: #60a5fa !important;
}

.dark-mode .profile-header-card {
  background: rgba(30, 41, 59, 0.8) !important;
  border-color: #334155 !important;
}

.dark-mode .btn-change-photo {
  border-color: #1e293b !important;
}

.dark-mode .user-main-info h1 {
  color: #f8fafc !important;
}

.dark-mode .user-main-info p {
  color: #94a3b8 !important;
}

.dark-mode .profile-body-card {
  background: #1e293b !important;
  border-color: #334155 !important;
}

.dark-mode .body-header h2 {
  color: #f8fafc !important;
}

.dark-mode .btn-edit {
  background: #334155 !important;
  border-color: #475569 !important;
  color: #f8fafc !important;
}

.dark-mode .info-item {
  background: #0f172a !important;
  border-color: #1e293b !important;
}

.dark-mode .info-item label {
  color: #64748b !important;
}

.dark-mode .info-item p {
  color: #f8fafc !important;
}

.dark-mode .form-group label {
  color: #cbd5e1 !important;
}

.dark-mode .form-group input,
.dark-mode .form-group textarea {
  background: #0f172a !important;
  border-color: #334155 !important;
  color: #f8fafc !important;
}

.dark-mode .btn-cancel {
  background: #334155 !important;
  border-color: #475569 !important;
  color: #cbd5e1 !important;
}

.dark-mode .footer {
  background: #0f172a !important;
  border-top-color: #1e293b !important;
}

.dark-mode .footer-desc {
  color: #94a3b8 !important;
}

.dark-mode .footer-bottom {
  border-top-color: #1e293b !important;
}

/* RESPONSIVE DESIGN */
@media (max-width: 768px) {
  .profile-header-card {
    flex-direction: column;
    text-align: center;
  }

  .info-grid,
  .edit-form {
    grid-template-columns: 1fr;
  }

  .info-item.full-width,
  .form-group.full-width,
  .form-actions {
    grid-column: span 1;
  }
}
</style>