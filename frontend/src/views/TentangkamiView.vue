<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import '../style.css'

const router = useRouter()

// Logo Path & Fallback
const logoCemilku = ref('/images/cemilku-logo.png')

const handleLogoError = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
}

// Status Otentikasi
const isLoggedIn = ref(false)
const currentUser = ref('')
const currentRole = ref('')

const isAdmin = computed(() => currentRole.value === 'admin')

const checkAuth = () => {
  const user = localStorage.getItem('user') || localStorage.getItem('username')
  const role = localStorage.getItem('userRole') || localStorage.getItem('role')
  const authStatus = localStorage.getItem('isLoggedIn') === 'true'

  if (user || authStatus) {
    isLoggedIn.value = true
    currentUser.value = user || 'Admin'
    currentRole.value = role || 'user'
  }
}

onMounted(() => {
  checkAuth()
})

const handleLogout = () => {
  localStorage.removeItem('user')
  localStorage.removeItem('username')
  localStorage.removeItem('role')
  localStorage.removeItem('userRole')
  localStorage.removeItem('isLoggedIn')

  isLoggedIn.value = false
  currentUser.value = ''
  currentRole.value = ''
  router.push('/login')
}

const goToLogin = () => router.push('/login')
const goToRegister = () => router.push('/register')
const goToHome = () => router.push('/')

// FUNGSI CHAT VIA WHATSAPP (Nomor: 089619869600 -> 6289619869600)
const openWhatsApp = () => {
  const phone = '6289619869600'
  const text = 'Halo Cemilku, saya mau tanya-tanya cemilan ya!'
  window.open(`https://api.whatsapp.com/send/?phone=${phone}&text=${encodeURIComponent(text)}`, '_blank')
}
</script>

<template>
  <div class="about-wrapper">
    <!-- NAVBAR UNIFORM GLASSMORPHISM -->
    <header class="navbar">
      <div class="container nav-content">
        <div class="brand-logo" @click="goToHome">
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
            <small>Snack Store</small>
          </div>
        </div>

        <nav class="main-navigation">
          <router-link to="/" class="nav-link">Produk</router-link>
          <router-link to="/tentang-kami" class="nav-link active">Tentang Kami</router-link>
          <router-link to="/kontak" class="nav-link">Kontak</router-link>
          <router-link v-if="isAdmin" to="/admin" class="nav-link nav-admin-btn">Dashboard</router-link>
        </nav>

        <div class="user-menu">
          <template v-if="isLoggedIn">
            <div class="user-badge">
              <span class="user-greeting">Selamat datang</span>
              <strong>{{ currentUser }}</strong>
            </div>
            <button @click="handleLogout" class="btn-logout">Keluar</button>
          </template>

          <template v-else>
            <button @click="goToLogin" class="btn-outline">Masuk</button>
            <button @click="goToRegister" class="btn-primary">Daftar</button>
          </template>
        </div>
      </div>
    </header>

    <!-- HERO ABOUT PREMIUM WITH GLOW EFFECTS -->
    <section class="about-hero">
      <div class="hero-bg-glow"></div>
      <div class="container hero-container">
        <div class="hero-content">
          <span class="hero-label">✨ TENTANG CEMILKU STORE</span>
          <h1 class="hero-title">
            Mengenal Lebih Dekat<br>
            <span class="gradient-text">Cemilku Snack Store</span>
          </h1>
          <p class="hero-subtitle">
            Kami hadir untuk menyajikan camilan berkualitas dengan cita rasa khas lokal yang selalu renyah, gurih, dan siap menemani setiap momen spesial Anda.
          </p>
          
          <div class="hero-actions">
            <button @click="openWhatsApp" class="btn-primary hero-button glow-on-hover">
              <svg class="btn-wa-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor">
                <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3 18.6-68.1-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.9-186.6 184.9zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
              </svg>
              Hubungi Kami via WhatsApp
            </button>
          </div>
        </div>

        <div class="hero-visual">
          <div class="hero-card-3d">
            <div class="card-glass-shine"></div>
            <div class="hero-logo-circle Pulse">
              <img
                :src="logoCemilku"
                alt="Cemilku Logo"
                class="cemilku-logo-img"
                draggable="false"
                @error="handleLogoError"
              />
            </div>
            <h3 class="brand-title">Cemilku Store</h3>
            <p class="brand-tagline">Camilan Pilihan Keluarga</p>
            <div class="card-footer-badge">
              <span>⭐ Top Snack Choice 2026</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- HIGHLIGHT STATS BAR -->
    <section class="container stats-container">
      <div class="stats-card">
        <div class="stat-item">
          <h2>100%</h2>
          <p>Bahan Lokal Alami</p>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
          <h2>50+</h2>
          <p>Varian Camilan</p>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
          <h2>10k+</h2>
          <p>Pelanggan Setia</p>
        </div>
      </div>
    </section>

    <!-- STORY SECTION (VISI & MISI) -->
    <section class="container story-section">
      <div class="story-grid">
        <div class="story-card hover-lift">
          <div class="icon-wrapper">🚀</div>
          <span class="story-badge">VISI KAMI</span>
          <h2>Menjadi Pelopor Camilan Lokal Berkualitas</h2>
          <p>
            Cemilku bertekad menghadirkan aneka jajanan ringan yang diolah secara bersih, higienis, dan terus mempertahankan cita rasa khas nusantara hingga sampai di tangan pelanggan.
          </p>
        </div>

        <div class="story-card hover-lift">
          <div class="icon-wrapper">💎</div>
          <span class="story-badge">MISI KAMI</span>
          <h2>Layanan Terbaik & Kepuasan Pelanggan</h2>
          <p>
            Memberikan kenyamanan pemesanan melalui platform digital modern serta memastikan setiap pengiriman dilapisi kemasan aman dan tiba tepat waktu.
          </p>
        </div>
      </div>
    </section>

    <!-- FOOTER UNIFORM -->
    <footer class="footer">
      <div class="container footer-content">
        <div class="footer-brand">
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
            <small>Snack Store</small>
          </div>
        </div>

        <div class="footer-description">
          Platform pemesanan camilan lokal dengan pengalaman belanja yang sederhana, aman, dan terpercaya.
        </div>

        <div class="footer-copy">
          © 2026 Cemilku Store. All rights reserved.
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.about-wrapper {
  min-height: 100vh;
  background: #0f1219;
  color: #f8fafc;
  scroll-behavior: smooth;
  position: relative;
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  overflow-x: hidden;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
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
}

.brand-info small {
  display: block;
  margin-top: 1px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.6rem;
}

.main-navigation {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 6px;
  border-radius: 14px;
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.08);
}

.nav-link {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.85rem;
  font-weight: 600;
  padding: 8px 18px;
  border-radius: 10px;
  text-decoration: none;
  transition: all 0.3s ease;
}

.nav-link:hover,
.nav-link.active {
  color: #fff;
  background: linear-gradient(135deg, rgba(255, 126, 95, 0.3), rgba(254, 180, 123, 0.2));
  transform: translateY(-1px);
}

.nav-admin-btn {
  background: linear-gradient(135deg, #ff7e5f, #ea580c);
  color: #ffffff !important;
  padding: 8px 16px;
  border-radius: 10px;
  font-weight: 700;
  box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-badge {
  display: flex;
  flex-direction: column;
  line-height: 1.2;
}

.user-greeting {
  color: rgba(255, 255, 255, 0.5);
  font-size: 0.65rem;
}

.user-badge strong {
  color: #fff;
  font-size: 0.85rem;
}

/* BUTTON STYLES */
.btn-outline,
.btn-primary,
.btn-logout {
  height: 42px;
  padding: 0 20px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-outline {
  background: rgba(255, 255, 255, 0.05);
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.15);
}

.btn-outline:hover {
  background: rgba(255, 255, 255, 0.12);
  transform: translateY(-2px);
}

.btn-primary {
  background: linear-gradient(135deg, #ff7e5f, #ea580c);
  color: #fff;
  box-shadow: 0 6px 20px rgba(234, 88, 12, 0.35);
}

.btn-primary:hover {
  background: linear-gradient(135deg, #ff6b4a, #c2410c);
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(234, 88, 12, 0.5);
}

.btn-wa-icon {
  width: 18px;
  height: 18px;
}

.btn-logout {
  background: rgba(239, 68, 68, 0.15);
  color: #fecaca;
  border: 1px solid rgba(248, 113, 113, 0.3);
}

.btn-logout:hover {
  background: rgba(239, 68, 68, 0.25);
  color: #fff;
}

/* HERO ABOUT WITH NEON GLOW */
.about-hero {
  position: relative;
  padding: 100px 0 80px;
  overflow: hidden;
}

.hero-bg-glow {
  position: absolute;
  top: -20%;
  left: 50%;
  transform: translateX(-50%);
  width: 600px;
  height: 400px;
  background: radial-gradient(circle, rgba(255, 126, 95, 0.15) 0%, rgba(15, 18, 25, 0) 70%);
  filter: blur(80px);
  pointer-events: none;
}

.hero-container {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: 50px;
  align-items: center;
}

.hero-label {
  color: #ff7e5f;
  font-size: 0.8rem;
  font-weight: 800;
  letter-spacing: 0.2em;
  background: rgba(255, 126, 95, 0.1);
  padding: 6px 14px;
  border-radius: 30px;
  border: 1px solid rgba(255, 126, 95, 0.2);
}

.hero-title {
  font-size: 3.2rem;
  font-weight: 800;
  line-height: 1.15;
  margin: 20px 0 16px;
  letter-spacing: -0.03em;
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
  max-width: 500px;
}

.hero-visual {
  display: flex;
  justify-content: center;
}

/* 3D MODERN CARD */
.hero-card-3d {
  position: relative;
  background: rgba(255, 255, 255, 0.03);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 40px 30px;
  border-radius: 28px;
  text-align: center;
  width: 100%;
  max-width: 320px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
  transition: transform 0.4s ease;
}

.hero-card-3d:hover {
  transform: translateY(-8px) rotate(1deg);
}

.hero-logo-circle {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ff7e5f, #ea580c);
  padding: 16px;
  margin: 0 auto 20px;
  box-shadow: 0 10px 30px rgba(234, 88, 12, 0.4);
}

.brand-title {
  font-size: 1.4rem;
  font-weight: 800;
  color: #fff;
  margin-bottom: 4px;
}

.brand-tagline {
  font-size: 0.85rem;
  color: #94a3b8;
  margin-bottom: 20px;
}

.card-footer-badge span {
  font-size: 0.75rem;
  background: rgba(255, 126, 95, 0.15);
  color: #feb47b;
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: 700;
  border: 1px solid rgba(255, 126, 95, 0.3);
}

/* STATS SECTION */
.stats-container {
  margin-top: -30px;
  margin-bottom: 50px;
  position: relative;
  z-index: 10;
}

.stats-card {
  background: rgba(255, 255, 255, 0.02);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 20px;
  padding: 24px 40px;
  display: flex;
  justify-content: space-around;
  align-items: center;
}

.stat-item {
  text-align: center;
}

.stat-item h2 {
  font-size: 2.2rem;
  font-weight: 800;
  background: linear-gradient(135deg, #ff7e5f, #feb47b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 4px;
}

.stat-item p {
  color: #94a3b8;
  font-size: 0.85rem;
  font-weight: 600;
}

.stat-divider {
  width: 1px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
}

/* STORY SECTION (VISI & MISI) */
.story-section {
  padding: 40px 0 80px;
}

.story-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 30px;
}

.story-card {
  background: rgba(255, 255, 255, 0.02);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 24px;
  padding: 40px;
  transition: all 0.3s ease;
  position: relative;
}

.hover-lift:hover {
  transform: translateY(-6px);
  background: rgba(255, 255, 255, 0.04);
  border-color: rgba(255, 126, 95, 0.3);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
}

.icon-wrapper {
  font-size: 2rem;
  margin-bottom: 16px;
}

.story-badge {
  color: #ff7e5f;
  font-size: 0.75rem;
  font-weight: 800;
  letter-spacing: 0.15em;
}

.story-card h2 {
  font-size: 1.4rem;
  font-weight: 800;
  color: #fff;
  margin: 8px 0 12px;
}

.story-card p {
  color: #94a3b8;
  font-size: 0.95rem;
  line-height: 1.6;
}

/* FLOATING WHATSAPP WITH RIPPLE EFFECT */
.floating-wa-btn {
  position: fixed;
  bottom: 28px;
  right: 28px;
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #25d366, #128c7e);
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
  z-index: 999;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  text-decoration: none;
}

.floating-wa-btn:hover {
  transform: scale(1.1) translateY(-4px);
  box-shadow: 0 10px 30px rgba(37, 211, 102, 0.6);
}

.wa-icon {
  width: 32px;
  height: 32px;
}

/* FOOTER */
.footer {
  background: #090b0f;
  border-top: 1px solid rgba(255, 255, 255, 0.06);
  padding: 50px 0;
  color: #fff;
}

.footer-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 18px;
  text-align: center;
}

.footer-brand {
  display: flex;
  align-items: center;
  gap: 12px;
}

.footer-description {
  color: #64748b;
  font-size: 0.9rem;
  max-width: 440px;
  line-height: 1.5;
}

.footer-copy {
  color: #475569;
  font-size: 0.8rem;
}

@media (max-width: 768px) {
  .hero-container,
  .story-grid {
    grid-template-columns: 1fr;
  }
  .hero-title {
    font-size: 2.3rem;
  }
  .stats-card {
    flex-direction: column;
    gap: 20px;
  }
  .stat-divider {
    width: 60%;
    height: 1px;
  }
}
</style>