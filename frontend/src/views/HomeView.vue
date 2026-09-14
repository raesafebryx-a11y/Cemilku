<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const router = useRouter()

// Status Auth & User Info
const isLoggedIn = ref(false)
const userRole = ref('')
const username = ref('')

// Logo Path & Fallback
const logoCemilku = ref('/images/cemilku-logo.png')

const handleLogoError = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
}

// Cek status login saat komponen dimuat
const checkAuthStatus = () => {
  isLoggedIn.value = localStorage.getItem('isLoggedIn') === 'true'
  userRole.value = localStorage.getItem('userRole') || localStorage.getItem('role') || 'user'
  username.value = localStorage.getItem('username') || localStorage.getItem('user') || ''
}

onMounted(() => {
  checkAuthStatus()
  // TODO: Panggil fungsi fetch data produk dari API backend di sini
  // fetchProductsFromAPI()
})

// Fungsi Logout dengan SweetAlert2
const handleLogout = () => {
  Swal.fire({
    title: 'Konfirmasi Keluar',
    text: 'Apakah Anda yakin ingin keluar dari akun ini?',
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
        background: '#1e293b',
        color: '#fff'
      }).then(() => {
        router.push('/')
        window.location.reload()
      })
    }
  })
}

// State Pencarian dan Filter Produk
const searchQuery = ref('')
const selectedCategory = ref('Semua')

// Data Produk dikosongkan agar terisi otomatis dari backend
const products = ref([])

// Filter Produk berdasarkan Kategori dan Search Query
const filteredProducts = computed(() => {
  return products.value.filter(product => {
    const matchesCategory = selectedCategory.value === 'Semua' || product.category === selectedCategory.value
    const matchesSearch = product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesCategory && matchesSearch
  })
})

// Fungsi Pemesanan via WhatsApp
const orderViaWhatsApp = (product) => {
  const phone = '6281234567890' // Ganti nomor WhatsApp tujuan
  const message = `Halo Cemilku, saya ingin memesan produk berikut:%0A%0A- *${product.name}* (Rp ${product.price.toLocaleString()})%0A%0AMohon info ketersediaannya ya!`
  window.open(`https://wa.me/${phone}?text=${message}`, '_blank')
}
</script>

<template>
  <div class="home-wrapper">
    <!-- BACKGROUND GLOW EFFECT -->
    <div class="hero-bg-glow"></div>

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
          <button class="nav-btn active" @click="router.push('/')">
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

        <!-- AUTH BUTTONS SECTION -->
        <div class="nav-auth">
          <template v-if="isLoggedIn">
            <div class="user-greeting">
              <span class="greet-label">Selamat datang</span>
              <strong class="user-email">{{ username }}</strong>
            </div>
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

    <!-- MAIN HERO & CATALOG SECTION -->
    <main class="main-content">
      <div class="container">
        
        <!-- HERO SECTION -->
        <section class="hero-section">
          <div class="hero-text-box">
            <span class="hero-tag">🔥 ANEKA CAMILAN TERFAVORIT</span>
            <h1 class="hero-title">
              Nikmati Sensasi Renyah <br>
              <span class="gradient-text">Cemilan Nusantara</span>
            </h1>
            <p class="hero-desc">
              Pilihan tepat camilan gurih, pedas, dan manis yang dikemas higienis. Siap diantar langsung ke rumah Anda.
            </p>

            <!-- SEARCH BAR -->
            <div class="search-box">
              <span class="search-icon">🔍</span>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari camilan favoritmu di sini..."
              />
            </div>
          </div>

          <!-- HERO BANNER CARD -->
          <div class="hero-banner-card hover-lift">
            <div class="banner-icon-box">
              <img
                :src="logoCemilku"
                alt="Cemilku Store"
                class="banner-logo-img"
                @error="handleLogoError"
              />
            </div>
            <h2>Cemilku Store</h2>
            <p>Solusi Lapar Saat Santai</p>
            <button class="btn-fast-order" @click="orderViaWhatsApp({ name: 'Paket Spesial Cemilku', price: 50000 })">
              ⚡ Fast Response Order
            </button>
          </div>
        </section>

        <!-- CATALOG SECTION -->
        <section class="catalog-section">
          <div class="catalog-header">
            <h2>Katalog Produk</h2>
            
            <!-- CATEGORY FILTER BUTTONS -->
            <div class="filter-buttons">
              <button 
                :class="['filter-btn', { active: selectedCategory === 'Semua' }]" 
                @click="selectedCategory = 'Semua'"
              >
                Semua
              </button>
              <button 
                :class="['filter-btn', { active: selectedCategory === 'Pedas' }]" 
                @click="selectedCategory = 'Pedas'"
              >
                🌶️ Pedas
              </button>
              <button 
                :class="['filter-btn', { active: selectedCategory === 'Gurih' }]" 
                @click="selectedCategory = 'Gurih'"
              >
                🧀 Gurih
              </button>
              <button 
                :class="['filter-btn', { active: selectedCategory === 'Manis' }]" 
                @click="selectedCategory = 'Manis'"
              >
                🍫 Manis
              </button>
            </div>
          </div>

          <!-- PRODUCT GRID -->
          <div v-if="filteredProducts.length > 0" class="product-grid">
            <div v-for="product in filteredProducts" :key="product.id" class="product-card">
              <div class="product-img-wrapper">
                <img :src="product.image" :alt="product.name" class="product-img" />
                <span class="product-badge">{{ product.category }}</span>
              </div>
              <div class="product-info">
                <h3>{{ product.name }}</h3>
                <p class="product-price">Rp {{ product.price.toLocaleString() }}</p>
                <button class="btn-order" @click="orderViaWhatsApp(product)">
                  Pesan via WhatsApp
                </button>
              </div>
            </div>
          </div>

          <!-- EMPTY STATE -->
          <div v-else class="empty-state">
            <p>Tidak ada produk yang tersedia saat ini.</p>
          </div>
        </section>

      </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
      <div class="container footer-content">
        <div class="footer-brand">
          <div class="brand-mark small">
            <img :src="logoCemilku" alt="Logo" class="cemilku-logo-img" @error="handleLogoError" />
          </div>
          <span class="brand-text">Cemilku</span>
        </div>
        <p>Platform pemesanan camilan lokal dengan pengalaman belanja praktis dan cepat.</p>
        <small>© 2026 Cemilku Snack Store. All rights reserved.</small>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.home-wrapper {
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
  width: 750px;
  height: 500px;
  background: radial-gradient(circle, rgba(255, 126, 95, 0.15) 0%, rgba(15, 18, 25, 0) 70%);
  filter: blur(100px);
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

.brand-mark.small {
  width: 32px;
  height: 32px;
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

/* NAV LINKS */
.nav-links {
  display: flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.03);
  padding: 6px;
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.06);
}

.nav-btn {
  background: transparent;
  border: none;
  color: #94a3b8;
  padding: 8px 16px;
  font-size: 0.85rem;
  font-weight: 600;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.nav-btn:hover {
  color: #fff;
  background: rgba(255, 255, 255, 0.05);
}

.nav-btn.active {
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
}

.btn-dashboard {
  background: linear-gradient(135deg, #ea580c, #c2410c) !important;
  color: #fff !important;
}

/* USER AUTH SECTION */
.nav-auth {
  display: flex;
  align-items: center;
  gap: 14px;
}

.user-greeting {
  display: flex;
  flex-direction: column;
  text-align: right;
}

.greet-label {
  font-size: 0.65rem;
  color: #64748b;
}

.user-email {
  font-size: 0.8rem;
  color: #f8fafc;
  font-weight: 700;
}

.btn-logout {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.3);
  color: #f87171;
  padding: 8px 16px;
  border-radius: 10px;
  font-size: 0.8rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-logout:hover {
  background: #dc2626;
  color: #fff;
  border-color: #dc2626;
  box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.btn-outline {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.15);
  color: #fff;
  padding: 8px 18px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-outline:hover {
  background: rgba(255, 255, 255, 0.12);
}

.btn-primary {
  background: linear-gradient(135deg, #ff7e5f, #ea580c);
  border: none;
  color: #fff;
  padding: 8px 18px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
  transition: all 0.2s;
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(234, 88, 12, 0.4);
}

/* MAIN CONTENT & HERO */
.main-content {
  flex: 1;
  padding: 40px 0 80px;
}

.hero-section {
  display: grid;
  grid-template-columns: 1.3fr 1fr;
  gap: 40px;
  align-items: center;
  margin-bottom: 60px;
}

.hero-tag {
  color: #ff7e5f;
  font-size: 0.8rem;
  font-weight: 800;
  letter-spacing: 0.15em;
  background: rgba(255, 126, 95, 0.1);
  padding: 6px 14px;
  border-radius: 30px;
  border: 1px solid rgba(255, 126, 95, 0.2);
  display: inline-block;
  margin-bottom: 16px;
}

.hero-title {
  font-size: 2.8rem;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 16px;
  letter-spacing: -0.03em;
  color: #fff;
}

.gradient-text {
  background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-desc {
  color: #94a3b8;
  font-size: 1rem;
  line-height: 1.6;
  margin-bottom: 24px;
}

.search-box {
  position: relative;
  max-width: 480px;
}

.search-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.9rem;
}

.search-box input {
  width: 100%;
  padding: 14px 16px 14px 46px;
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 14px;
  font-size: 0.9rem;
  color: #fff;
  outline: none;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.search-box input:focus {
  border-color: #ff7e5f;
  background: rgba(255, 255, 255, 0.07);
  box-shadow: 0 0 15px rgba(255, 126, 95, 0.2);
}

/* HERO BANNER CARD */
.hero-banner-card {
  background: rgba(255, 255, 255, 0.03);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 24px;
  padding: 40px;
  text-align: center;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
  transition: transform 0.3s ease;
}

.hero-banner-card:hover {
  transform: translateY(-4px);
}

.banner-icon-box {
  width: 70px;
  height: 70px;
  margin: 0 auto 16px;
  background: linear-gradient(135deg, #ff7e5f, #feb47b);
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px;
  box-shadow: 0 8px 20px rgba(255, 126, 95, 0.3);
}

.banner-logo-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.hero-banner-card h2 {
  font-size: 1.5rem;
  font-weight: 800;
  margin-bottom: 6px;
  color: #fff;
}

.hero-banner-card p {
  color: #94a3b8;
  font-size: 0.9rem;
  margin-bottom: 24px;
}

.btn-fast-order {
  background: linear-gradient(135deg, #ff7e5f, #ea580c);
  color: #fff;
  border: none;
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.9rem;
  cursor: pointer;
  box-shadow: 0 6px 20px rgba(234, 88, 12, 0.35);
  transition: all 0.2s ease;
}

.btn-fast-order:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(234, 88, 12, 0.5);
}

/* CATALOG SECTION */
.catalog-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.catalog-header h2 {
  font-size: 1.6rem;
  font-weight: 800;
  color: #fff;
  margin: 0;
}

.filter-buttons {
  display: flex;
  gap: 8px;
  background: rgba(255, 255, 255, 0.03);
  padding: 4px;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.06);
}

.filter-btn {
  background: transparent;
  border: none;
  color: #94a3b8;
  padding: 6px 14px;
  font-size: 0.8rem;
  font-weight: 600;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.filter-btn:hover {
  color: #fff;
  background: rgba(255, 255, 255, 0.05);
}

.filter-btn.active {
  background: linear-gradient(135deg, #ff7e5f, #ea580c);
  color: #fff;
}

/* PRODUCT GRID */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
}

.product-card {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 20px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.product-card:hover {
  transform: translateY(-4px);
  border-color: rgba(255, 126, 95, 0.3);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}

.product-img-wrapper {
  position: relative;
  height: 180px;
  background: rgba(15, 18, 25, 0.5);
}

.product-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  background: rgba(15, 18, 25, 0.75);
  backdrop-filter: blur(8px);
  color: #feb47b;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 20px;
  border: 1px solid rgba(255, 126, 95, 0.3);
}

.product-info {
  padding: 20px;
}

.product-info h3 {
  font-size: 1.05rem;
  font-weight: 700;
  color: #fff;
  margin-bottom: 6px;
}

.product-price {
  color: #ff7e5f;
  font-size: 1rem;
  font-weight: 800;
  margin-bottom: 16px;
}

.btn-order {
  width: 100%;
  background: rgba(255, 255, 255, 0.06);
  border: 1px solid rgba(255, 255, 255, 0.12);
  color: #fff;
  padding: 10px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-order:hover {
  background: linear-gradient(135deg, #ff7e5f, #ea580c);
  border-color: transparent;
}

.empty-state {
  text-align: center;
  padding: 60px 0;
  background: rgba(255, 255, 255, 0.02);
  border: 1px dashed rgba(255, 255, 255, 0.1);
  border-radius: 20px;
  color: #64748b;
  font-size: 0.95rem;
}

/* FOOTER */
.footer {
  border-top: 1px solid rgba(255, 255, 255, 0.05);
  padding: 40px 0;
  background: rgba(15, 18, 25, 0.5);
  margin-top: auto;
}

.footer-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 12px;
}

.footer-brand {
  display: flex;
  align-items: center;
  gap: 10px;
}

.footer-content p {
  color: #94a3b8;
  font-size: 0.85rem;
  max-width: 450px;
  margin: 0;
}

.footer-content small {
  color: #64748b;
  font-size: 0.75rem;
}

@media (max-width: 900px) {
  .hero-section {
    grid-template-columns: 1fr;
    gap: 30px;
  }
  
  .hero-title {
    font-size: 2.2rem;
  }
}
</style>