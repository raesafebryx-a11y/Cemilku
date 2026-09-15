<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const router = useRouter()

// Status Auth & User Info
const isLoggedIn = ref(false)
const userRole = ref('')
const username = ref('')

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

const checkAuthStatus = () => {
  isLoggedIn.value = localStorage.getItem('isLoggedIn') === 'true'
  userRole.value = localStorage.getItem('userRole') || localStorage.getItem('role') || 'user'
  username.value = localStorage.getItem('username') || localStorage.getItem('user') || ''
  
  // Cek mode gelap dari localStorage
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme === 'dark') {
    isDarkMode.value = true
  }
}

// Inisialisasi Peta Leaflet
let mapInstance = null

const initMap = async () => {
  await nextTick()
  
  const mapElement = document.getElementById('map')
  if (!mapElement) return

  if (mapInstance) {
    mapInstance.remove()
  }

  // Koordinat Lokasi Toko (Bandung)
  const storeLat = -6.9175
  const storeLng = 107.6191

  mapInstance = L.map('map').setView([storeLat, storeLng], 14)

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(mapInstance)

  const customIcon = L.icon({
    iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
    iconSize: [38, 38],
    iconAnchor: [19, 38],
    popupAnchor: [0, -34]
  })

  L.marker([storeLat, storeLng], { icon: customIcon })
    .addTo(mapInstance)
    .bindPopup('<b>Cemilku Snack Store</b><br>Pusat Jajanan Nusantara.')
    .openPopup()

  setTimeout(() => {
    mapInstance.invalidateSize()
  }, 250)
}

onMounted(() => {
  checkAuthStatus()
  initMap()
})

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
  <div class="page-wrapper" :class="{ 'dark-mode': isDarkMode }">
    <!-- BACKGROUND AMBIENT GLOW -->
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
          <button class="nav-btn active" @click="router.push('/tentang-kami')">
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

            <!-- BUTTON PROFIL DISAMAKAN DENGAN HOME (TANPA GAMBAR) -->
            <button class="btn-profile" @click="router.push('/profile')">
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

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <div class="container">
        
        <!-- HERO PAGE HEADER -->
        <section class="page-hero">
          <div class="hero-badge">
            <span class="pulse-dot"></span>
            Tentang Cemilku Store
          </div>
          <h1 class="page-title">
            Menghadirkan Kelezatan Jajanan<br>
            <span class="gradient-text">Nusantara Kualitas Terbaik</span>
          </h1>
          <p class="page-desc">
            Cemilku Snack Store hadir sebagai solusi terbaik bagi Anda pencinta camilan renyah, gurih, dan manis khas Nusantara dengan penyajian modern & higienis.
          </p>
        </section>

        <!-- ABOUT CONTENT CARDS -->
        <section class="about-grid">
          <div class="info-card">
            <div class="card-icon-box">🎯</div>
            <h3>Visi Kami</h3>
            <p>
              Menjadi brand jajanan lokal nomor satu di Indonesia yang dikenal karena keaslian rasa, kebersihan kemasan, dan kemudahan akses pembelian online.
            </p>
          </div>

          <div class="info-card">
            <div class="card-icon-box">🚀</div>
            <h3>Misi Utama</h3>
            <p>
              Menyediakan pilihan camilan khas berkualitas tinggi, mendukung mitra pengolah lokal, serta memberikan pelayanan pengiriman terbaik dan cepat.
            </p>
          </div>

          <div class="info-card">
            <div class="card-icon-box">⭐</div>
            <h3>Keunggulan Mutu</h3>
            <p>
              Setiap produk dikemas menggunakan bahan bersertifikasi pangan (food-grade), menjamin kebersihan dan kesegaran rasa hingga sampai ke tangan Anda.
            </p>
          </div>
        </section>

        <!-- STATS BANNER -->
        <section class="stats-banner">
          <div class="stat-item">
            <strong>100%</strong>
            <span>Bahan Higienis</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <strong>50+</strong>
            <span>Varian Rasa</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <strong>10.000+</strong>
            <span>Pelanggan Puas</span>
          </div>
        </section>

        <!-- MAPS / LOKASI SECTION -->
        <section class="map-section">
          <div class="section-header">
            <div class="hero-badge">📍 LOKASI KAMI</div>
            <h2>Kunjungi Toko Fisik Kami</h2>
            <p class="page-desc">Temukan outlet utama kami langsung atau gunakan petunjuk arah pada peta di bawah ini.</p>
          </div>
          <div class="map-card">
            <div id="map"></div>
          </div>
        </section>

      </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
      <div class="container footer-content">
        <div class="footer-grid">
          <div class="footer-brand-block">
            <div class="footer-brand">
              <div class="brand-mark small">
                <img :src="logoCemilku" alt="Logo" class="cemilku-logo-img" @error="handleLogoError" />
              </div>
              <span class="brand-text">Cemilku</span>
            </div>
            <p class="footer-desc">
              Pusat camilan renyah dan nikmat dengan pengiriman cepat, kemasan aman, dan rasa yang selalu bikin nagih.
            </p>
            <div class="social-row">
              <a href="#" aria-label="Instagram">📸</a>
              <a href="#" aria-label="Facebook">📘</a>
              <a href="#" aria-label="WhatsApp">💬</a>
            </div>
          </div>

          <div class="footer-column">
            <h4>Menu</h4>
            <ul>
              <li><a href="#">Produk</a></li>
              <li><a href="#">Tentang Kami</a></li>
              <li><a href="#">Kontak</a></li>
              <li><a href="#">Bantuan</a></li>
            </ul>
          </div>

          <div class="footer-column">
            <h4>Kontak</h4>
            <ul class="contact-list">
              <li>📍 Jl. Camilan Lezat No. 12, Bandung, Jawa Barat</li>
              <li>📞 089619869600</li>
              <li>✉️ admin@cemilku.com</li>
              <li>🕘 Senin - Minggu, 08.00 - 21.00</li>
            </ul>
          </div>
        </div>

        <div class="footer-bottom">
          <small>© 2026 Cemilku Snack Store. All rights reserved.</small>
          <div class="footer-meta">
            <span>Privacy Policy</span>
            <span>Terms</span>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
/* GLOBAL & BASE STYLES */
.page-wrapper {
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

.nav-btn.active {
  background: #ffffff;
  color: #2563eb;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
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
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.25s ease;
}

.btn-profile:hover {
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

/* MAIN CONTENT */
.main-content {
  flex: 1;
  padding: 50px 0 80px;
  position: relative;
  z-index: 1;
}

/* HERO PAGE HEADER */
.page-hero {
  text-align: center;
  max-width: 760px;
  margin: 0 auto 50px auto;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #eff6ff;
  color: #2563eb;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 6px 14px;
  border-radius: 20px;
  margin-bottom: 20px;
  border: 1px solid #bfdbfe;
  box-shadow: 0 2px 8px rgba(37, 99, 235, 0.06);
}

.pulse-dot {
  width: 7px;
  height: 7px;
  background-color: #2563eb;
  border-radius: 50%;
  box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
  animation: pulse 1.8s infinite;
}

@keyframes pulse {
  0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7); }
  70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(37, 99, 235, 0); }
  100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
}

.page-title {
  font-size: 2.8rem;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 16px;
  letter-spacing: -0.03em;
  color: #0f172a;
}

.gradient-text {
  background: linear-gradient(135deg, #1d4ed8, #3b82f6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.page-desc {
  color: #64748b;
  font-size: 1.05rem;
  line-height: 1.65;
  margin: 0 auto;
}

/* ABOUT GRID CARDS */
.about-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
  margin-bottom: 50px;
}

.info-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 24px;
  padding: 32px 28px;
  box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.04);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.info-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 35px -10px rgba(37, 99, 235, 0.12);
  border-color: #cbd5e1;
}

.card-icon-box {
  width: 56px;
  height: 56px;
  border-radius: 16px;
  background: #eff6ff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.6rem;
  margin-bottom: 20px;
  border: 1px solid #dbeafe;
}

.info-card h3 {
  font-size: 1.25rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0 0 10px 0;
}

.info-card p {
  color: #64748b;
  font-size: 0.925rem;
  line-height: 1.6;
  margin: 0;
}

/* STATS BANNER */
.stats-banner {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(226, 232, 240, 0.9);
  border-radius: 24px;
  padding: 30px;
  display: flex;
  align-items: center;
  justify-content: space-around;
  box-shadow: 0 12px 30px -10px rgba(37, 99, 235, 0.06);
  margin-bottom: 60px;
}

.stat-item {
  text-align: center;
}

.stat-item strong {
  display: block;
  font-size: 1.8rem;
  font-weight: 800;
  color: #2563eb;
}

.stat-item span {
  font-size: 0.875rem;
  color: #64748b;
  font-weight: 600;
}

.stat-divider {
  width: 1px;
  height: 40px;
  background: #e2e8f0;
}

/* MAP SECTION */
.map-section {
  margin-top: 20px;
  margin-bottom: 20px;
}

.section-header {
  text-align: center;
  max-width: 600px;
  margin: 0 auto 30px auto;
}

.section-header h2 {
  font-size: 2rem;
  font-weight: 800;
  color: #0f172a;
  margin: 10px 0;
}

.map-card {
  border-radius: 24px;
  overflow: hidden;
  border: 1px solid #e2e8f0;
  box-shadow: 0 16px 35px -10px rgba(37, 99, 235, 0.08);
  position: relative;
  z-index: 1;
}

#map {
  height: 420px;
  width: 100%;
  z-index: 1;
  position: relative;
}

:deep(.leaflet-popup-content-wrapper) {
  background: #ffffff;
  color: #1e293b;
  border-radius: 12px;
  font-family: inherit;
}

:deep(.leaflet-popup-tip) {
  background: #ffffff;
}

/* FOOTER */
.footer {
  border-top: 1px solid #e2e8f0;
  padding: 52px 0 28px;
  background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
  margin-top: auto;
  transition: all 0.3s ease;
}

.footer-content {
  display: flex;
  flex-direction: column;
  gap: 26px;
}

.footer-grid {
  display: grid;
  grid-template-columns: 1.4fr 0.8fr 1fr;
  gap: 32px;
  align-items: start;
}

.footer-brand-block {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.footer-brand {
  display: flex;
  align-items: center;
  gap: 8px;
}

.footer-desc {
  color: #64748b;
  font-size: 0.875rem;
  line-height: 1.7;
  max-width: 420px;
  margin: 0;
  text-align: left;
}

.social-row {
  display: flex;
  align-items: center;
  gap: 10px;
}

.social-row a {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
  font-size: 1rem;
  transition: all 0.25s ease;
}

.social-row a:hover {
  background: #2563eb;
  color: #ffffff;
  transform: translateY(-2px);
}

.footer-column h4 {
  font-size: 0.92rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0 0 14px;
  text-align: left;
}

.footer-column ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.footer-column a,
.contact-list li {
  color: #64748b;
  font-size: 0.85rem;
  text-decoration: none;
  line-height: 1.6;
  text-align: left;
}

.footer-column a:hover {
  color: #2563eb;
}

.contact-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-bottom {
  border-top: 1px solid #e2e8f0;
  width: 100%;
  padding-top: 18px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

.footer-bottom small {
  color: #94a3b8;
  font-size: 0.75rem;
}

.footer-meta {
  display: flex;
  align-items: center;
  gap: 16px;
  color: #64748b;
  font-size: 0.75rem;
}

/* DARK MODE STYLES */
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

.dark-mode .nav-btn.active {
  background: #1e293b !important;
  color: #60a5fa !important;
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

.dark-mode .btn-profile:hover {
  background: #2563eb !important;
  color: #ffffff !important;
}

.dark-mode .btn-outline {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #cbd5e1 !important;
}

.dark-mode .page-title,
.dark-mode .section-header h2 {
  color: #f8fafc !important;
}

.dark-mode .page-desc {
  color: #94a3b8 !important;
}

.dark-mode .hero-badge {
  background: rgba(30, 41, 59, 0.8) !important;
  color: #60a5fa !important;
  border-color: #1e3a8a !important;
}

.dark-mode .info-card {
  background: #1e293b !important;
  border-color: #334155 !important;
}

.dark-mode .card-icon-box {
  background: #0f172a !important;
  border-color: #334155 !important;
}

.dark-mode .info-card h3 {
  color: #f8fafc !important;
}

.dark-mode .info-card p {
  color: #94a3b8 !important;
}

.dark-mode .stats-banner {
  background: rgba(30, 41, 59, 0.8) !important;
  border-color: #334155 !important;
}

.dark-mode .stat-item span {
  color: #94a3b8 !important;
}

.dark-mode .stat-divider {
  background: #334155 !important;
}

.dark-mode .map-card {
  border-color: #334155 !important;
}

.dark-mode .footer {
  background: linear-gradient(180deg, rgba(15, 23, 42, 1) 0%, rgba(15, 23, 42, 0.96) 100%) !important;
  border-top-color: #1e293b !important;
}

.dark-mode .footer-desc,
.dark-mode .footer-column a,
.dark-mode .contact-list li,
.dark-mode .footer-meta {
  color: #94a3b8 !important;
}

.dark-mode .footer-column h4 {
  color: #f8fafc !important;
}

.dark-mode .social-row a {
  background: rgba(30, 41, 59, 0.9) !important;
  color: #60a5fa !important;
}

.dark-mode .footer-bottom {
  border-top-color: #1e293b !important;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .page-title {
    font-size: 2.1rem;
  }

  .stats-banner {
    flex-direction: column;
    gap: 20px;
  }

  .stat-divider {
    width: 60px;
    height: 1px;
  }

  .footer-grid {
    grid-template-columns: 1fr;
  }

  #map {
    height: 320px;
  }
}

@media (max-width: 520px) {
  .footer-bottom {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>