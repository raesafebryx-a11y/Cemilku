<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Logo Cemilku
const logoCemilku = '/images/cemilku-logo.png'

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
const goToAdmin = () => router.push('/admin')

// Fungsi Kontak WhatsApp
const openWhatsApp = () => {
  const phone = '6281234567890'
  const message = 'Halo Cemilku, saya ingin bertanya mengenai produk.'
  const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`
  window.open(url, '_blank')
}
</script>

<template>
  <div class="home-wrapper">
    <!-- NAVBAR UNIFORM -->
    <header class="navbar">
      <div class="container nav-content">
        <div class="brand-logo" @click="router.push('/')">
          <div class="brand-mark logo-image-wrapper">
            <img
              :src="logoCemilku"
              alt="Logo Cemilku"
              class="cemilku-logo"
              draggable="false"
            />
          </div>
          <div class="brand-info">
            <span class="brand-text">Cemilku</span>
            <small>Snack Store</small>
          </div>
        </div>

        <nav class="main-navigation">
          <router-link to="/" class="nav-link">Produk</router-link>
          <router-link to="/tentang-kami" class="nav-link">Tentang Kami</router-link>
          <router-link to="/kontak" class="nav-link active">Kontak</router-link>
          <router-link v-if="isAdmin" to="/admin" class="nav-link nav-admin-btn">Dashboard</router-link>
        </nav>

        <div class="user-menu">
          <template v-if="isLoggedIn">
            <div class="user-profile">
              <div class="user-avatar">
                {{ currentUser.charAt(0).toUpperCase() }}
              </div>
              <div class="user-information">
                <span class="user-greeting">Selamat datang</span>
                <strong>{{ currentUser }}</strong>
              </div>
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

    <!-- HERO SECTION KONTAK -->
    <section class="hero-section">
      <div class="container hero-container">
        <div class="hero-content">
          <span class="hero-label">HUBUNGI KAMI</span>
          <h1 class="hero-title">
            Ada yang ingin<br>
            <span>ditanyakan?</span>
          </h1>
          <p class="hero-subtitle">
            Jangan ragu untuk menghubungi kami. Kami siap membantu kamu mengenai produk, pemesanan, atau pertanyaan lainnya.
          </p>

          <div class="hero-actions">
            <button @click="openWhatsApp" class="btn-primary hero-button">
              Chat WhatsApp
            </button>
            <button v-if="isAdmin" @click="goToAdmin" class="btn-secondary hero-button">
              Kelola Admin
            </button>
          </div>
        </div>

        <div class="hero-visual">
          <div class="hero-product-card">
            <div class="product-placeholder">
              <div class="hero-logo-circle">
                <img
                  :src="logoCemilku"
                  alt="Cemilku Support"
                  class="hero-cemilku-logo"
                  draggable="false"
                />
              </div>
            </div>
            <div class="hero-product-info">
              <span>LAYANAN PELANGGAN</span>
              <h3>Fast Response CS</h3>
              <div class="hero-product-bottom">
                <strong>08.00 - 21.00 WIB</strong>
                <span>Setiap Hari</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CONTACT CARDS GRID SECTION -->
    <main class="container catalog-section">
      <div class="catalog-header">
        <div>
          <span class="section-label">SALURAN KOMUNIKASI</span>
          <h2 class="section-title">Pilih cara menghubungi kami</h2>
          <p class="section-subtitle">
            Pilih metode yang paling nyaman untuk terhubung dengan tim Cemilku.
          </p>
        </div>
      </div>

      <div class="contact-card-grid">
        <!-- Card WhatsApp -->
        <div class="contact-info-card">
          <div class="card-icon-wrapper">💬</div>
          <h3>WhatsApp Chat</h3>
          <p>Respon cepat untuk pertanyaan ketersediaan stok, pesanan khusus, dan bantuan langsung.</p>
          <button class="btn-card-action" @click="openWhatsApp">
            Kirim Pesan WA →
          </button>
        </div>

        <!-- Card Email -->
        <div class="contact-info-card">
          <div class="card-icon-wrapper">✉️</div>
          <h3>Email Support</h3>
          <p>Kirimkan masukan, kerja sama bisnis, atau pertanyaan resmi melalui alamat email kami.</p>
          <a href="mailto:cemilku@example.com" class="btn-card-action outline">
            Kirim Email →
          </a>
        </div>

        <!-- Card Jam Operasional -->
        <div class="contact-info-card">
          <div class="card-icon-wrapper">🕐</div>
          <h3>Jam Operasional</h3>
          <p>Layanan operasional toko dan pengiriman paket pesanan pelanggan.</p>
          <div class="time-badge">
            <span>Senin – Sabtu</span>
            <strong>08.00 – 21.00 WIB</strong>
          </div>
        </div>
      </div>
    </main>

    <!-- TRUST SECTION -->
    <section class="trust-section">
      <div class="container trust-grid">
        <div class="trust-item">
          <div class="trust-number">01</div>
          <div>
            <h3>Respon Cepat</h3>
            <p>Tim kami siap membalas pesan Anda dengan sigap pada jam operasional.</p>
          </div>
        </div>

        <div class="trust-item">
          <div class="trust-number">02</div>
          <div>
            <h3>Bantuan Pemesanan</h3>
            <p>Mengalami kendala saat order? Kami siap membimbing hingga transaksi selesai.</p>
          </div>
        </div>

        <div class="trust-item">
          <div class="trust-number">03</div>
          <div>
            <h3>Kritik & Saran</h3>
            <p>Masukan Anda sangat berharga untuk meningkatkan kualitas produk dan layanan kami.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER UNIFORM -->
    <footer class="footer">
      <div class="container footer-content">
        <div class="footer-brand">
          <div class="brand-mark logo-image-wrapper footer-logo">
            <img
              :src="logoCemilku"
              alt="Logo Cemilku"
              class="cemilku-logo"
              draggable="false"
            />
          </div>
          <div>
            <strong>Cemilku</strong>
            <span>Snack Store</span>
          </div>
        </div>

        <div class="footer-description">
          Platform pemesanan camilan lokal dengan pengalaman belanja yang sederhana dan terpercaya.
        </div>

        <div class="footer-copy">
          © 2026 Cemilku. All rights reserved.
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
/* NAV DASHBOARD BUTTON */
.nav-admin-btn {
  background-color: #ea580c;
  color: #ffffff !important;
  padding: 6px 14px;
  border-radius: 8px;
  font-weight: 700;
  transition: background 0.2s;
}

.nav-admin-btn:hover {
  background-color: #c2410c;
}

/* CONTACT CARD GRID */
.contact-card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
  margin-top: 12px;
}

.contact-info-card {
  background: #ffffff;
  border: 1px solid #f1f5f9;
  border-radius: 16px;
  padding: 32px 24px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
  transition: transform 0.2s, box-shadow 0.2s;
}

.contact-info-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
}

.card-icon-wrapper {
  font-size: 32px;
  background-color: #fff7ed;
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: grid;
  place-items: center;
  margin-bottom: 20px;
}

.contact-info-card h3 {
  font-size: 20px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 10px 0;
}

.contact-info-card p {
  font-size: 14px;
  color: #64748b;
  line-height: 1.6;
  margin: 0 0 24px 0;
  flex: 1;
}

.btn-card-action {
  background-color: #ea580c;
  color: #ffffff;
  border: none;
  padding: 12px 20px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
  text-align: center;
  width: 100%;
  transition: background 0.2s;
}

.btn-card-action:hover {
  background-color: #c2410c;
}

.btn-card-action.outline {
  background-color: transparent;
  color: #ea580c;
  border: 2px solid #ea580c;
}

.btn-card-action.outline:hover {
  background-color: #fff7ed;
}

.time-badge {
  background-color: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 12px 16px;
  border-radius: 10px;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.time-badge span {
  font-size: 12px;
  color: #64748b;
  font-weight: 600;
}

.time-badge strong {
  font-size: 16px;
  color: #ea580c;
  font-weight: 700;
}
</style>