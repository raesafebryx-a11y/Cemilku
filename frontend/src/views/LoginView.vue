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
const errorMessage = ref('')

const handleLogin = () => {
  errorMessage.value = ''

  if (!loginUsername.value || !loginPassword.value) {
    errorMessage.value = 'Username dan password wajib diisi.'

    Swal.fire({
      icon: 'warning',
      title: 'Data Belum Lengkap',
      text: 'Harap isi username dan password Anda terlebih dahulu.',
      confirmButtonColor: '#2563eb',
      cancelButtonColor: '#94a3b8',
      background: '#ffffff',
      color: '#1e293b'
    })
    return
  }

  // Tentukan role berdasarkan input username/email
  const isUserAdmin = loginUsername.value.toLowerCase().includes('admin')
  const role = isUserAdmin ? 'admin' : 'user'

  // Simpan data autentikasi & role ke localStorage
  localStorage.setItem('isLoggedIn', 'true')
  localStorage.setItem('userRole', role)
  localStorage.setItem('role', role)
  localStorage.setItem('username', loginUsername.value)
  localStorage.setItem('user', loginUsername.value)

  // Pop-up Notifikasi Berhasil
  Swal.fire({
    icon: 'success',
    title: 'Login Berhasil! 🎉',
    text: `Selamat datang kembali, ${loginUsername.value}!`,
    timer: 1800,
    showConfirmButton: false,
    timerProgressBar: true,
    confirmButtonColor: '#2563eb',
    background: '#ffffff',
    color: '#1e293b'
  }).then(() => {
    router.push('/')
  })
}

const fillDemoAdmin = () => {
  // 1. Isi input form otomatis
  loginUsername.value = 'admin@cemilku.com'
  loginPassword.value = 'admin123'
  errorMessage.value = ''

  // 2. Simpan status auth & role admin ke localStorage
  localStorage.setItem('isLoggedIn', 'true')
  localStorage.setItem('userRole', 'admin')
  localStorage.setItem('role', 'admin')
  localStorage.setItem('username', 'admin@cemilku.com')
  localStorage.setItem('user', 'admin@cemilku.com')

  // Notifikasi Demo Admin Berhasil
  Swal.fire({
    icon: 'success',
    title: 'Akses Admin Ditentukan! 🛡️',
    text: 'Berhasil masuk menggunakan akun Demo Admin.',
    timer: 1800,
    showConfirmButton: false,
    timerProgressBar: true,
    confirmButtonColor: '#2563eb',
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
                placeholder="pelanggan atau admin@cemilku.com"
              />
            </div>

            <div class="auth-form-group">
              <label>Password</label>
              <input
                v-model="loginPassword"
                type="password"
                placeholder="Masukkan password"
              />
            </div>

            <div v-if="errorMessage" class="auth-error">
              {{ errorMessage }}
            </div>

            <button type="submit" class="btn-primary auth-submit glow-on-hover">
              Masuk ke Akun
            </button>
          </form>

          <!-- Demo Admin Box -->
          <div class="demo-card">
            <div class="demo-info">
              <span class="demo-title">🛡️ Demo Admin</span>
              <small>Gunakan akun berikut untuk mencoba dashboard.</small>
            </div>
            <button class="btn-auto" type="button" @click="fillDemoAdmin">
              Isi Otomatis
            </button>
          </div>

          <div class="demo-account">
            <strong>admin@cemilku.com</strong>
            <span>admin123</span>
          </div>

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

.demo-card {
  margin-top: 24px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 14px 18px;
  border-radius: 14px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.demo-info {
  display: flex;
  flex-direction: column;
}

.demo-title {
  font-weight: 700;
  font-size: 0.85rem;
  color: #0f172a;
}

.demo-info small {
  font-size: 0.75rem;
  color: #64748b;
  margin-top: 2px;
}

.btn-auto {
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 700;
  color: #2563eb;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-auto:hover {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  color: #ffffff;
  border-color: transparent;
}

.demo-account {
  display: flex;
  justify-content: space-between;
  font-size: 0.8rem;
  margin-top: 10px;
  padding: 0 6px;
}

.demo-account strong {
  color: #2563eb;
}

.demo-account span {
  color: #64748b;
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