<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const loginUsername = ref('')
const loginPassword = ref('')
const errorMessage = ref('')

const handleLogin = () => {
  errorMessage.value = ''

  if (!loginUsername.value || !loginPassword.value) {
    errorMessage.value = 'Username dan password wajib diisi.'
    return
  }

  // Tentukan role berdasarkan input username/email
  const isUserAdmin = loginUsername.value.toLowerCase().includes('admin')
  const role = isUserAdmin ? 'admin' : 'user'

  // Simpan data autentikasi & role ke localStorage
  localStorage.setItem('isLoggedIn', 'true')
  localStorage.setItem('userRole', role) // Sesuai router guard 'userRole'
  localStorage.setItem('role', role)
  localStorage.setItem('username', loginUsername.value)
  localStorage.setItem('user', loginUsername.value)

  router.push('/')
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

  // 3. Otomatis redirect ke halaman Home (Navbar akan membaca status Admin)
  router.push('/')
}
</script>

<template>
  <div class="auth-page">
    <!-- Navbar Header -->
    <header class="auth-navbar">
      <div class="auth-nav-container">
        <div class="brand-logo" @click="router.push('/')">
          <div class="brand-mark">C</div>
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

    <!-- Main Content -->
    <main class="auth-main">
      <section class="auth-layout">
        <!-- Section Left: Intro & Benefits -->
        <div class="auth-intro">
          <span class="section-label">SELAMAT DATANG</span>

          <h1>Masuk untuk melanjutkan pemesanan Anda.</h1>

          <p>
            Nikmati pengalaman pemesanan camilan yang lebih mudah, cepat, dan
            terorganisir bersama Cemilku.
          </p>

          <div class="auth-benefit">
            <div class="benefit-item">
              <div class="benefit-icon">01</div>
              <div>
                <strong>Produk pilihan</strong>
                <span>Berbagai camilan favorit tersedia.</span>
              </div>
            </div>

            <div class="benefit-item">
              <div class="benefit-icon">02</div>
              <div>
                <strong>Checkout praktis</strong>
                <span>Pesanan langsung diteruskan ke WhatsApp.</span>
              </div>
            </div>

            <div class="benefit-item">
              <div class="benefit-icon">03</div>
              <div>
                <strong>Akun pelanggan</strong>
                <span>Kelola pesanan dengan lebih mudah.</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Section Right: Form Card -->
        <div class="auth-card">
          <div class="auth-card-header">
            <span class="section-label">LOGIN</span>
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

            <button type="submit" class="auth-submit">
              Masuk ke Akun
            </button>
          </form>

          <!-- Demo Admin Box -->
          <div class="demo-card">
            <div class="demo-info">
              <span class="demo-title">Demo Admin</span>
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
      </section>
    </main>

    <footer class="auth-footer">
      <div class="container">
        © 2026 Cemilku Snack Store
      </div>
    </footer>
  </div>
</template>

<style scoped>
.auth-page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #fafafa;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  color: #1e293b;
}

/* Navbar */
.auth-navbar {
  background: #ffffff;
  border-bottom: 1px solid #f1f5f9;
  padding: 16px 0;
}
.auth-nav-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.brand-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
}
.brand-mark {
  background: #f97316;
  color: white;
  font-weight: 800;
  font-size: 20px;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.brand-info {
  display: flex;
  flex-direction: column;
}
.brand-text {
  font-weight: 700;
  font-size: 18px;
  color: #0f172a;
  line-height: 1.1;
}
.brand-info small {
  font-size: 10px;
  letter-spacing: 0.5px;
  color: #64748b;
  font-weight: 600;
}
.btn-outline {
  background: transparent;
  border: 1px solid #cbd5e1;
  padding: 8px 18px;
  border-radius: 8px;
  font-weight: 600;
  color: #334155;
  cursor: pointer;
  transition: all 0.2s;
}
.btn-outline:hover {
  background: #f8fafc;
}

/* Main Layout */
.auth-main {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 24px;
}
.auth-layout {
  max-width: 1100px;
  width: 100%;
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 60px;
  align-items: center;
}

/* Intro Section */
.auth-intro {
  text-align: left;
}
.section-label {
  color: #ea580c;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 1px;
}
.auth-intro h1 {
  font-size: 40px;
  font-weight: 800;
  color: #0f172a;
  margin: 12px 0;
  line-height: 1.2;
}
.auth-intro p {
  color: #64748b;
  font-size: 15px;
  line-height: 1.6;
  margin-bottom: 32px;
}

/* Benefits */
.auth-benefit {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.benefit-item {
  display: flex;
  align-items: center;
  gap: 16px;
}
.benefit-icon {
  background: #fff7ed;
  color: #ea580c;
  font-weight: 700;
  font-size: 13px;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #ffedd5;
}
.benefit-item strong {
  display: block;
  font-size: 14px;
  color: #1e293b;
}
.benefit-item span {
  font-size: 13px;
  color: #64748b;
}

/* Card Section */
.auth-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 36px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
  border: 1px solid #f1f5f9;
  text-align: left;
}
.auth-card-header h2 {
  font-size: 26px;
  font-weight: 800;
  color: #0f172a;
  margin: 6px 0;
}
.auth-card-header p {
  color: #64748b;
  font-size: 14px;
  margin-bottom: 24px;
}

/* Form */
.auth-form-group {
  margin-bottom: 18px;
}
.auth-form-group label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #334155;
  margin-bottom: 8px;
}
.auth-form-group input {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  font-size: 14px;
  box-sizing: border-box;
  outline: none;
  transition: border-color 0.2s;
}
.auth-form-group input:focus {
  border-color: #ea580c;
}
.auth-error {
  color: #dc2626;
  font-size: 13px;
  margin-bottom: 12px;
}
.auth-submit {
  width: 100%;
  background: #ea580c;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}
.auth-submit:hover {
  background: #c2410c;
}

/* Demo Card Box */
.demo-card {
  margin-top: 20px;
  background: #f8fafc;
  border: 1px solid #f1f5f9;
  padding: 12px 16px;
  border-radius: 10px;
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
  font-size: 13px;
  color: #0f172a;
}
.demo-info small {
  font-size: 11px;
  color: #64748b;
}
.btn-auto {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  color: #334155;
  cursor: pointer;
  transition: all 0.2s;
}
.btn-auto:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
}

.demo-account {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  margin-top: 8px;
  padding: 0 4px;
}
.demo-account strong {
  color: #ea580c;
}
.demo-account span {
  color: #94a3b8;
}

/* Divider & Footer Switch */
.auth-divider {
  display: flex;
  align-items: center;
  margin: 20px 0;
}
.auth-divider .line {
  flex: 1;
  height: 1px;
  background: #e2e8f0;
}
.auth-divider p {
  padding: 0 12px;
  font-size: 12px;
  color: #94a3b8;
  margin: 0;
}
.auth-switch {
  text-align: center;
  font-size: 13px;
  color: #64748b;
  margin: 0;
}
.auth-switch a {
  color: #ea580c;
  font-weight: 700;
  text-decoration: none;
}

/* Page Footer */
.auth-footer {
  text-align: center;
  padding: 20px 0;
  font-size: 13px;
  color: #94a3b8;
  border-top: 1px solid #f1f5f9;
  background: #ffffff;
}

@media (max-width: 900px) {
  .auth-layout {
    grid-template-columns: 1fr;
    gap: 40px;
  }
}
</style>