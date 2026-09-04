<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import '../style.css'

const router = useRouter()

// Logo Cemilku
const logoCemilku = '/images/cemilku-logo.png'

// Status Otentikasi
const isLoggedIn = ref(false)
const currentUser = ref('')
const currentRole = ref('')

const isAdmin = computed(() => currentRole.value === 'admin')

// Toast State
const toast = ref({
  show: false,
  message: '',
  type: 'success'
})

const showToast = (message, type = 'success') => {
  toast.value = { show: true, message, type }
  setTimeout(() => {
    toast.value.show = false
  }, 3000)
}

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
  showToast('Anda berhasil keluar dari akun')
}

const goToLogin = () => router.push('/login')
const goToRegister = () => router.push('/register')
const goToHome = () => router.push('/')
const goToAdmin = () => router.push('/admin')
</script>

<template>
  <div class="home-wrapper">
    <!-- TOAST NOTIFICATION -->
    <Transition name="toast">
      <div v-if="toast.show" :class="['toast-notification', toast.type]">
        <span class="toast-indicator"></span>
        <div>
          <strong>{{ toast.type === 'success' ? 'Berhasil' : 'Perhatian' }}</strong>
          <p>{{ toast.message }}</p>
        </div>
      </div>
    </Transition>

    <!-- NAVBAR UNIFORM -->
    <header class="navbar">
      <div class="container nav-content">
        <div class="brand-logo" @click="goToHome">
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
          <router-link to="/tentang-kami" class="nav-link active">Tentang Kami</router-link>
          <router-link to="/kontak" class="nav-link">Kontak</router-link>
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

    <!-- HERO SECTION TENTANG KAMI -->
    <section class="hero-section">
      <div class="container hero-container">
        <div class="hero-content">
          <span class="hero-label">TENTANG CEMILKU</span>
          <h1 class="hero-title">
            Cemilan sederhana,<br>
            <span>rasa luar biasa.</span>
          </h1>
          <p class="hero-subtitle">
            Cemilku hadir untuk menemani setiap momen dengan berbagai pilihan cemilan yang enak, praktis, dan cocok dinikmati kapan saja.
          </p>

          <div class="hero-actions">
            <button @click="goToHome" class="btn-primary hero-button">
              Lihat Produk
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
                  alt="Cemilku Story"
                  class="hero-cemilku-logo"
                  draggable="false"
                />
              </div>
            </div>
            <div class="hero-product-info">
              <span>PROFIL TOKO</span>
              <h3>Cemilku Snack Store</h3>
              <div class="hero-product-bottom">
                <strong>Teman Ngemil</strong>
                <span>Terpercaya</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PROFIL / SIAPA KAMI SECTION -->
    <main class="container catalog-section">
      <div class="about-card-banner">
        <div class="about-card-content">
          <span class="section-label">SIAPA KAMI?</span>
          <h2>Teman terbaik di setiap waktu santaimu</h2>
          <p>
            Cemilku adalah toko cemilan lokal yang menyediakan berbagai macam makanan ringan favorit. Kami berkomitmen untuk menghadirkan makanan ringan berkonsep higienis, renyah, dan bercita rasa khas.
          </p>
          <p>
            Mulai dari basreng pedas daun jeruk, keripik balado, makaroni gurih, hingga kudapan manis, seluruh produk diproses untuk menjaga mutu kualitas terbaik hingga sampai ke tangan Anda.
          </p>
        </div>
      </div>
    </main>

    <!-- TRUST & VALUES SECTION -->
    <section class="trust-section">
      <div class="container trust-grid">
        <div class="trust-item">
          <div class="trust-number">01</div>
          <div>
            <h3>Kualitas Pilihan</h3>
            <p>Bahan baku dipilih dengan ketat serta diproses secara higienis untuk rasa yang selalu konsisten.</p>
          </div>
        </div>

        <div class="trust-item">
          <div class="trust-number">02</div>
          <div>
            <h3>Pemesanan Praktis</h3>
            <p>Proses belanja ringkas tanpa ribet, langsung terhubung ke layanan pelanggan via WhatsApp.</p>
          </div>
        </div>

        <div class="trust-item">
          <div class="trust-number">03</div>
          <div>
            <h3>Kepuasan Pelanggan</h3>
            <p>Jaminan kemasan aman dan respon pelayanan ramah menjadi fokus prioritas kami.</p>
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

/* ABOUT CARD BANNER STYLING */
.about-card-banner {
  background: #ffffff;
  border: 1px solid #f1f5f9;
  border-radius: 16px;
  padding: 40px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
}

.about-card-content h2 {
  font-size: 28px;
  font-weight: 800;
  color: #0f172a;
  margin: 12px 0 16px 0;
}

.about-card-content p {
  color: #64748b;
  font-size: 15px;
  line-height: 1.7;
  margin-bottom: 12px;
}

.about-card-content p:last-child {
  margin-bottom: 0;
}
</style>