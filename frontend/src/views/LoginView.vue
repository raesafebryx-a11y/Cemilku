<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const router = useRouter()

// Logo Path & Fallback
const logoCemilku = ref('/images/cemilku-logo.png')

const handleLogoError = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
}

const loginUsername = ref('')
const loginPassword = ref('')
const showPassword = ref(false)
const errorMessage = ref('')

const handleLogin = () => {
  errorMessage.value = ''

  // 1. Validasi input kosong
  if (!loginUsername.value || !loginPassword.value) {
    errorMessage.value = 'Username dan password wajib diisi.'
    Swal.fire({
      icon: 'warning',
      title: 'Data Belum Lengkap',
      text: 'Harap isi username dan password Anda terlebih dahulu.',
      confirmButtonColor: '#2563eb',
      background: '#ffffff',
      color: '#1e293b'
    })
    return
  }

  const inputUser = loginUsername.value.trim()
  const inputPass = loginPassword.value.trim()

  // 2. Ambil data database lokal dari proses register (jika ada)
  const registeredUsers = JSON.parse(localStorage.getItem('registeredUsers')) || []
  
  // Cari apakah user terdaftar di localStorage
  const foundUser = registeredUsers.find(
    (u) => (u.username === inputUser || u.email === inputUser) && u.password === inputPass
  )

  let role = 'user'
  let loggedInName = inputUser

  if (foundUser) {
    // Jika ditemukan di database pendaftaran lokal
    role = foundUser.role || 'user'
    loggedInName = foundUser.username || foundUser.email
  } else {
    // 3. Fallback ke akun default (Admin & User biasa)
    const isAdminDefault = (inputUser.toLowerCase() === 'admin@cemilku.com' && inputPass === 'password') ||
                           (inputUser.toLowerCase().includes('admin') && inputPass === 'password')

    if (isAdminDefault) {
      role = 'admin'
    } else if (!inputUser.toLowerCase().includes('admin')) {
      // Jika bukan admin dan diinput manual secara sah
      role = 'user'
    } else {
      errorMessage.value = 'Username atau password salah.'
      Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: 'Periksa kembali username dan password Anda.',
        confirmButtonColor: '#2563eb',
        background: '#ffffff',
        color: '#1e293b'
      })
      return
    }
  }

  // 4. Simpan status sesi ke localStorage
  localStorage.setItem('isLoggedIn', 'true')
  localStorage.setItem('userRole', role)
  localStorage.setItem('role', role)
  localStorage.setItem('username', loggedInName)
  localStorage.setItem('user', loggedInName)

  // 5. Notifikasi Berhasil
  Swal.fire({
    icon: 'success',
    title: 'Login Berhasil! 🎉',
    text: `Selamat datang kembali, ${loggedInName}!`,
    timer: 1800,
    showConfirmButton: false,
    timerProgressBar: true,
    background: '#ffffff',
    color: '#1e293b'
  }).then(() => {
    router.push('/')
  })
}
</script>

<template>
  <div class="auth-wrapper">
    <!-- BACKGROUND GLOW EFFECT -->
    <div class="hero-bg-glow"></div>

    <!-- NAVBAR UNIFORM GLASSMORPHISM -->
    <header class="navbar">
      <div class="container nav-content">
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

        <button class="btn-outline" @click="router.push('/')">
          Kembali
        </button>
      </div>
    </header>

    <!-- MAIN SECTION -->
    <main class="auth-main">
      <div class="container auth-container">
        <!-- Section Left: Intro & Benefits -->
        <div class="auth-intro">
          <span class="hero-label">✨ SELAMAT DATANG</span>

          <h1 class="hero-title">
            Masuk untuk Melanjutkan <br>
            <span class="gradient-text">Pemesanan Anda.</span>
          </h1>

          <p class="hero-subtitle">
            Nikmati pengalaman pemesanan camilan yang lebih mudah, cepat, dan terorganisir bersama Cemilku.
          </p>

          <div class="auth-benefit">
            <div class="benefit-item">
              <div class="benefit-icon">01</div>
              <div>
                <strong>Produk Pilihan</strong>
                <span>Berbagai camilan terfavorit kualitas terbaik.</span>
              </div>
            </div>

            <div class="benefit-item">
              <div class="benefit-icon">02</div>
              <div>
                <strong>Checkout Sistem Langsung</strong>
                <span>Pesanan tercatat otomatis di sistem dashboard admin.</span>
              </div>
            </div>

            <div class="benefit-item">
              <div class="benefit-icon">03</div>
              <div>
                <strong>Akun Pelanggan</strong>
                <span>Kelola dan pantau pesanan Anda dengan praktis.</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Section Right: Form Card 3D Glassmorphism -->
        <div class="auth-card hover-lift">
          <div class="card-glass-shine"></div>

          <div class="auth-card-header">
            <span class="card-badge">LOGIN</span>
            <h2>Masuk Akun</h2>
            <p>Silakan masukkan akun Anda untuk melanjutkan.</p>
          </div>

          <form @submit.prevent="handleLogin">
            <div class="auth-form-group">
              <label>Username atau Email</label>
              <input
                v-model="loginUsername"
                type="text"
                placeholder="Masukkan username atau email"
              />
            </div>

            <div class="auth-form-group">
              <label>Password</label>
              <div class="password-input-wrap">
                <input
                  v-model="loginPassword"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Masukkan password"
                />
                <button
                  type="button"
                  class="toggle-password"
                  @click="showPassword = !showPassword"
                  :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                >
                  {{ showPassword ? 'Sembunyikan' : 'Lihat' }}
                </button>
              </div>
            </div>

            <div v-if="errorMessage" class="auth-error">
              {{ errorMessage }}
            </div>

            <button type="submit" class="btn-primary auth-submit glow-on-hover">
              Masuk ke Akun
            </button>
          </form>

          <div class="auth-divider">
            <span class="line"></span>
            <p>atau</p>
            <span class="line"></span>
          </div>

          <p class="auth-switch">
            Belum memiliki akun?
            <router-link to="/register">Daftar sekarang</router-link>
          </p>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
.auth-wrapper {
  min-height: 100vh;
  background-color: #f6f8fb;
  color: #1e293b;
  position: relative;
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  display: flex;
  flex-direction: column;
  overflow-x: hidden;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  width: 100%;
  box-sizing: border-box;
}

.auth-wrapper::before,
.auth-wrapper::after {
  content: '';
  position: absolute;
  border-radius: 50%;
  filter: blur(140px);
  pointer-events: none;
  z-index: 0;
}

.auth-wrapper::before {
  width: 520px;
  height: 520px;
  top: -120px;
  right: -80px;
  background: radial-gradient(circle, rgba(147, 197, 253, 0.45) 0%, rgba(191, 219, 254, 0.15) 70%);
}

.auth-wrapper::after {
  width: 480px;
  height: 480px;
  left: -100px;
  bottom: 150px;
  background: radial-gradient(circle, rgba(96, 165, 250, 0.3) 0%, rgba(224, 242, 254, 0.1) 70%);
}

.navbar {
  position: sticky;
  top: 0;
  z-index: 50;
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  background: rgba(255, 255, 255, 0.75);
  border-bottom: 1px solid rgba(226, 232, 240, 0.8);
}

.nav-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  height: 76px;
  position: relative;
  z-index: 1;
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
  display: block;
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

.auth-main {
  flex: 1;
  display: flex;
  align-items: center;
  padding: 60px 0;
  position: relative;
  z-index: 1;
}

.auth-container {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 60px;
  align-items: center;
}

.hero-label {
  display: inline-flex;
  align-items: center;
  background: #eff6ff;
  color: #2563eb;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: 7px 14px;
  border-radius: 20px;
  border: 1px solid #bfdbfe;
}

.hero-title {
  font-size: 3rem;
  font-weight: 800;
  line-height: 1.15;
  margin: 20px 0 16px;
  letter-spacing: -0.03em;
  color: #0f172a;
}

.gradient-text {
  background: linear-gradient(135deg, #1d4ed8, #3b82f6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-subtitle {
  color: #64748b;
  font-size: 1.05rem;
  line-height: 1.65;
  margin-bottom: 32px;
}

.auth-benefit {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.benefit-item {
  display: flex;
  align-items: center;
  gap: 16px;
  background: rgba(255, 255, 255, 0.8);
  border: 1px solid #e2e8f0;
  padding: 14px 18px;
  border-radius: 16px;
  transition: all 0.3s ease;
}

.benefit-item:hover {
  background: #ffffff;
  border-color: #cbd5e1;
  transform: translateX(4px);
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
}

.benefit-icon {
  background: #eff6ff;
  color: #2563eb;
  font-weight: 800;
  font-size: 0.85rem;
  width: 42px;
  height: 42px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #bfdbfe;
  flex-shrink: 0;
}

.benefit-item strong {
  display: block;
  font-size: 0.95rem;
  color: #0f172a;
  margin-bottom: 2px;
}

.benefit-item span {
  font-size: 0.85rem;
  color: #64748b;
}

.auth-card {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(226, 232, 240, 0.9);
  border-radius: 28px;
  padding: 40px;
  box-shadow: 0 20px 40px -15px rgba(37, 99, 235, 0.07);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.auth-card-header {
  margin-bottom: 24px;
}

.card-badge {
  color: #2563eb;
  font-size: 0.75rem;
  font-weight: 800;
  letter-spacing: 0.15em;
  display: block;
  margin-bottom: 6px;
}

.auth-card-header h2 {
  font-size: 1.8rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 6px;
}

.auth-card-header p {
  color: #64748b;
  font-size: 0.9rem;
}

.auth-form-group {
  margin-bottom: 20px;
}

.auth-form-group label {
  display: block;
  font-size: 0.85rem;
  font-weight: 700;
  color: #334155;
  margin-bottom: 8px;
}

.password-input-wrap {
  position: relative;
  width: 100%;
}

.auth-form-group input {
  width: 100%;
  padding: 14px 16px;
  background: #ffffff;
  border: 1.5px solid #e2e8f0;
  border-radius: 12px;
  font-size: 0.95rem;
  color: #0f172a;
  box-sizing: border-box;
  outline: none;
  transition: all 0.3s ease;
}

.auth-form-group input::placeholder {
  color: #94a3b8;
}

.auth-form-group input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
  background: #ffffff;
}

.password-input-wrap input {
  padding-right: 92px;
}

.toggle-password {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  border: none;
  background: transparent;
  color: #2563eb;
  font-size: 0.75rem;
  font-weight: 700;
  cursor: pointer;
  padding: 6px 8px;
  border-radius: 8px;
}

.toggle-password:hover {
  background: #eff6ff;
}

.auth-error {
  color: #dc2626;
  font-size: 0.85rem;
  margin-bottom: 16px;
  font-weight: 600;
  background: #fef2f2;
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #fecaca;
}

.btn-outline {
  height: 42px;
  padding: 0 20px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 700;
  background: #ffffff;
  color: #334155;
  border: 1px solid #cbd5e1;
  cursor: pointer;
  transition: all 0.25s ease;
}

.btn-outline:hover {
  border-color: #3b82f6;
  color: #2563eb;
  background: #f0f7ff;
}

.btn-primary {
  width: 100%;
  height: 48px;
  border-radius: 12px;
  font-size: 0.95rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
  transition: all 0.25s ease;
}

.btn-primary:hover {
  transform: translateY(-1.5px);
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
}

.auth-divider {
  display: flex;
  align-items: center;
  margin: 24px 0;
}

.auth-divider .line {
  flex: 1;
  height: 1px;
  background: #e2e8f0;
}

.auth-divider p {
  padding: 0 14px;
  font-size: 0.8rem;
  color: #64748b;
  margin: 0;
}

.auth-switch {
  text-align: center;
  font-size: 0.85rem;
  color: #64748b;
  margin: 0;
}

.auth-switch a {
  color: #2563eb;
  font-weight: 700;
  text-decoration: none;
  transition: color 0.2s;
}

.auth-switch a:hover {
  color: #1d4ed8;
  text-decoration: underline;
}

@media (max-width: 900px) {
  .auth-container {
    grid-template-columns: 1fr;
    gap: 40px;
  }

  .hero-title {
    font-size: 2.3rem;
  }
}
</style>