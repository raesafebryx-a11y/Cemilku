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
    
    // Notifikasi Peringatan Input Kosong
    Swal.fire({
      icon: 'warning',
      title: 'Data Belum Lengkap',
      text: 'Harap isi username dan password Anda terlebih dahulu.',
      confirmButtonColor: '#ea580c',
      background: '#1e293b',
      color: '#fff'
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
    confirmButtonColor: '#ea580c',
    background: '#1e293b',
    color: '#fff'
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
    confirmButtonColor: '#ea580c',
    background: '#1e293b',
    color: '#fff'
  }).then(() => {
    // 3. Otomatis redirect ke halaman Home
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
  background: #0f1219;
  color: #f8fafc;
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

/* BACKGROUND GLOW EFFECT */
.hero-bg-glow {
  position: absolute;
  top: -10%;
  left: 50%;
  transform: translateX(-50%);
  width: 650px;
  height: 450px;
  background: radial-gradient(circle, rgba(255, 126, 95, 0.15) 0%, rgba(15, 18, 25, 0) 70%);
  filter: blur(90px);
  pointer-events: none;
}

/* NAVBAR GLASSMORPHISM */
.navbar {
  position: sticky;
  top: 0;
  z-index: 50;
  backdrop-filter: blur(16px);
  background: rgba(15, 18, 25, 0.82);
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.nav-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  height: 82px;
}

.brand-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.brand-logo:hover {
  transform: scale(1.05);
}

.brand-mark {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: linear-gradient(135deg, #ff7e5f, #feb47b);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 5px;
  box-shadow: 0 4px 15px rgba(255, 126, 95, 0.3);
}

.cemilku-logo-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}

.brand-text {
  color: #fff;
  font-size: 1.5rem;
  font-weight: 800;
  letter-spacing: -0.04em;
  display: block;
}

.brand-info small {
  display: block;
  margin-top: 1px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.6rem;
}

/* MAIN LAYOUT */
.auth-main {
  flex: 1;
  display: flex;
  align-items: center;
  padding: 60px 0;
  position: relative;
  z-index: 10;
}

.auth-container {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 60px;
  align-items: center;
}

/* INTRO SECTION */
.hero-label {
  color: #ff7e5f;
  font-size: 0.8rem;
  font-weight: 800;
  letter-spacing: 0.2em;
  background: rgba(255, 126, 95, 0.1);
  padding: 6px 14px;
  border-radius: 30px;
  border: 1px solid rgba(255, 126, 95, 0.2);
  display: inline-block;
}

.hero-title {
  font-size: 3rem;
  font-weight: 800;
  line-height: 1.2;
  margin: 20px 0 16px;
  letter-spacing: -0.03em;
  color: #fff;
}

.gradient-text {
  background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-subtitle {
  color: #94a3b8;
  font-size: 1.05rem;
  line-height: 1.6;
  margin-bottom: 32px;
}

/* BENEFITS LIST */
.auth-benefit {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.benefit-item {
  display: flex;
  align-items: center;
  gap: 16px;
  background: rgba(255, 255, 255, 0.02);
  border: 1px solid rgba(255, 255, 255, 0.06);
  padding: 14px 18px;
  border-radius: 16px;
  transition: all 0.3s ease;
}

.benefit-item:hover {
  background: rgba(255, 255, 255, 0.04);
  border-color: rgba(255, 126, 95, 0.3);
  transform: translateX(4px);
}

.benefit-icon {
  background: linear-gradient(135deg, rgba(255, 126, 95, 0.2), rgba(254, 180, 123, 0.1));
  color: #feb47b;
  font-weight: 800;
  font-size: 0.85rem;
  width: 42px;
  height: 42px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid rgba(255, 126, 95, 0.3);
  flex-shrink: 0;
}

.benefit-item strong {
  display: block;
  font-size: 0.95rem;
  color: #fff;
  margin-bottom: 2px;
}

.benefit-item span {
  font-size: 0.85rem;
  color: #94a3b8;
}

/* CARD SECTION GLASSMORPHISM */
.auth-card {
  background: rgba(255, 255, 255, 0.03);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 28px;
  padding: 40px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.auth-card-header {
  margin-bottom: 24px;
}

.card-badge {
  color: #ff7e5f;
  font-size: 0.75rem;
  font-weight: 800;
  letter-spacing: 0.15em;
  display: block;
  margin-bottom: 6px;
}

.auth-card-header h2 {
  font-size: 1.8rem;
  font-weight: 800;
  color: #fff;
  margin-bottom: 6px;
}

.auth-card-header p {
  color: #94a3b8;
  font-size: 0.9rem;
}

/* FORM CONTROLS */
.auth-form-group {
  margin-bottom: 20px;
}

.auth-form-group label {
  display: block;
  font-size: 0.85rem;
  font-weight: 700;
  color: #cbd5e1;
  margin-bottom: 8px;
}

.auth-form-group input {
  width: 100%;
  padding: 14px 16px;
  background: rgba(15, 18, 25, 0.6);
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 12px;
  font-size: 0.95rem;
  color: #fff;
  box-sizing: border-box;
  outline: none;
  transition: all 0.3s ease;
}

.auth-form-group input::placeholder {
  color: #64748b;
}

.auth-form-group input:focus {
  border-color: #ff7e5f;
  box-shadow: 0 0 15px rgba(255, 126, 95, 0.25);
  background: rgba(15, 18, 25, 0.8);
}

.auth-error {
  color: #f87171;
  font-size: 0.85rem;
  margin-bottom: 16px;
  font-weight: 600;
  background: rgba(239, 68, 68, 0.1);
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid rgba(239, 68, 68, 0.2);
}

/* BUTTONS */
.btn-outline {
  height: 42px;
  padding: 0 20px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 700;
  background: rgba(255, 255, 255, 0.05);
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.15);
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-outline:hover {
  background: rgba(255, 255, 255, 0.12);
  transform: translateY(-2px);
}

.btn-primary {
  width: 100%;
  height: 48px;
  border-radius: 12px;
  font-size: 0.95rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  background: linear-gradient(135deg, #ff7e5f, #ea580c);
  color: #fff;
  box-shadow: 0 6px 20px rgba(234, 88, 12, 0.35);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-primary:hover {
  background: linear-gradient(135deg, #ff6b4a, #c2410c);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(234, 88, 12, 0.5);
}

/* DEMO CARD BOX */
.demo-card {
  margin-top: 24px;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.08);
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
  color: #fff;
}

.demo-info small {
  font-size: 0.75rem;
  color: #94a3b8;
  margin-top: 2px;
}

.btn-auto {
  background: rgba(255, 126, 95, 0.15);
  border: 1px solid rgba(255, 126, 95, 0.3);
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 700;
  color: #feb47b;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-auto:hover {
  background: linear-gradient(135deg, #ff7e5f, #ea580c);
  color: #fff;
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
  color: #ff7e5f;
}

.demo-account span {
  color: #64748b;
}

/* DIVIDER & SWITCH */
.auth-divider {
  display: flex;
  align-items: center;
  margin: 24px 0;
}

.auth-divider .line {
  flex: 1;
  height: 1px;
  background: rgba(255, 255, 255, 0.1);
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
  color: #94a3b8;
  margin: 0;
}

.auth-switch a {
  color: #ff7e5f;
  font-weight: 700;
  text-decoration: none;
  transition: color 0.2s;
}

.auth-switch a:hover {
  color: #feb47b;
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