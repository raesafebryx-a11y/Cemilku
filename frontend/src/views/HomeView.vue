  <script setup>
  import { ref, computed, onMounted } from 'vue'
  import { useRouter } from 'vue-router'
  import Swal from 'sweetalert2'

  const router = useRouter()

  // Dynamic Asset URLs
  const logoCemilku = ref('/images/cemilku-logo.png')

  const handleLogoError = (e) => {
    e.target.src = 'https://cdn-icons-png.flaticon.com/512/2553/2553691.png'
  }

  // Authentication & Role State
  const isLoggedIn = ref(false)
  const userRole = ref('')
  const username = ref('')

  // Dark Mode State
  const isDarkMode = ref(false)

  const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value
    localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light')
  }

  // Check auth status & theme on mounted
  const checkAuthStatus = () => {
    const loggedIn = localStorage.getItem('isLoggedIn') === 'true'
    isLoggedIn.value = loggedIn

    if (loggedIn) {
      userRole.value = localStorage.getItem('userRole') || localStorage.getItem('role') || 'user'
      username.value = localStorage.getItem('username') || localStorage.getItem('user') || 'Pengguna'
    } else {
      userRole.value = ''
      username.value = ''
    }

    // Load Theme
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme === 'dark') {
      isDarkMode.value = true
    }
  }

  onMounted(() => {
    checkAuthStatus()
  })

  // Logout Logic
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

  // Filter & Search Logic
  const searchQuery = ref('')
  const activeCategory = ref('Semua')

  const categories = [
    { id: 'Semua', label: 'Semua', icon: '🧺' },
    { id: 'Pedas', label: 'Pedas', icon: '🌶️' },
    { id: 'Gurih', label: 'Gurih', icon: '🌽' },
    { id: 'Manis', label: 'Manis', icon: '🍫' }
  ]

  // Mock Data Produk (Kosongkan array jika ingin menampilkan info 'Belum Ada Produk')
  const products = ref([])

  const filteredProducts = computed(() => {
    return products.value.filter((product) => {
      const matchesCategory =
        activeCategory.value === 'Semua' || product.category === activeCategory.value
      const matchesSearch = product.name
        .toLowerCase()
        .includes(searchQuery.value.toLowerCase())
      return matchesCategory && matchesSearch
    })
  })
  </script>

  <template>
    <div class="home-wrapper" :class="{ 'dark-mode': isDarkMode }">
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

          <!-- NAVIGATION MENU -->
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

          <!-- AUTH BUTTONS & THEME TOGGLE -->
          <div class="nav-auth">
            <!-- TOGGLE DARK MODE -->
            <button class="btn-theme-toggle" @click="toggleDarkMode" :title="isDarkMode ? 'Mode Terang' : 'Mode Gelap'">
              <span v-if="isDarkMode">☀️</span>
              <span v-else>🌙</span>
            </button>

            <!-- TAMPILAN JIKA SUDAH LOGIN (USER MAUPUN ADMIN) -->
            <template v-if="isLoggedIn">
              <div class="user-greeting">
                <span class="greet-label">Selamat datang,</span>
                <strong class="user-email">{{ username }}</strong>
              </div>

              <!-- TOMBOL PROFIL (TAMPIL UNTUK USER MAUPUN ADMIN) -->
              <button class="btn-profile" @click="router.push('/profile')">
                👤 Profil
              </button>

              <!-- TOMBOL KELUAR -->
              <button class="btn-logout" @click="handleLogout">
                Keluar
              </button>
            </template>

            <!-- TAMPILAN JIKA BELUM LOGIN -->
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

      <!-- HERO SECTION -->
      <section class="hero-section">
        <div class="container hero-container">
          <!-- LEFT COLUMN: TEXT & SEARCH -->
          <div class="hero-left">
            <div class="hero-badge">
              <span class="badge-dot"></span>
              Camilan Nusantara Terfavorit
            </div>
            
            <h1 class="hero-title">
              Cita Rasa Authentic,<br />
              <span class="text-gradient">Renyah & Nikmat</span>
            </h1>
            
            <p class="hero-subtitle">
              Koleksi jajanan pilihan dengan bahan olahan higienis. Dikemas rapi dan siap dikirim cepat ke seluruh wilayah Indonesia.
            </p>

            <!-- SEARCH BAR -->
            <div class="search-box">
              <span class="search-icon">🔍</span>
              <input 
                v-model="searchQuery" 
                type="text" 
                placeholder="Cari rasa atau jenis cemilan..." 
              />
            </div>
          </div>

          <!-- RIGHT COLUMN: BRAND CARD & HIGHLIGHTS -->
          <div class="hero-right">
            <div class="glass-card brand-highlight-card">
              <div class="card-logo-box">
                <img :src="logoCemilku" alt="Cemilku Store" class="cemilku-logo-img" @error="handleLogoError" />
              </div>
              <h2>Cemilku Store</h2>
              <p>Teman Setia Momen Santai Anda</p>
              
              <div class="stats-grid">
                <div class="stat-item">
                  <h3>100%</h3>
                  <span>Higienis</span>
                </div>
                <div class="stat-item">
                  <h3>Fast</h3>
                  <span>Delivery</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- PRODUCT CATALOG SECTION -->
      <main class="catalog-section">
        <div class="container">
          <!-- CATALOG HEADER & CATEGORY TABS -->
          <div class="catalog-header">
            <div class="catalog-title">
              <h2>Katalog Produk</h2>
              <p>Pilih kategori camilan sesuai selera Anda</p>
            </div>

            <!-- CATEGORY PILLS -->
            <div class="category-pills">
              <button
                v-for="cat in categories"
                :key="cat.id"
                class="pill-btn"
                :class="{ active: activeCategory === cat.id }"
                @click="activeCategory = cat.id"
              >
                <span>{{ cat.icon }}</span> {{ cat.label }}
              </button>
            </div>
          </div>

          <!-- PRODUCT GRID OR EMPTY STATE -->
          <div v-if="filteredProducts.length > 0" class="product-grid">
            <div 
              v-for="product in filteredProducts" 
              :key="product.id" 
              class="product-card"
              @click="router.push(`/detail/${product.id}`)"
            >
              <div class="product-img-box">
                <img :src="product.image" :alt="product.name" />
                <span class="category-tag">{{ product.category }}</span>
              </div>
              <div class="product-info">
                <h3>{{ product.name }}</h3>
                <p class="product-desc">{{ product.description }}</p>
                <div class="product-bottom">
                  <span class="product-price">Rp {{ product.price.toLocaleString('id-ID') }}</span>
                  <button class="btn-detail">Lihat Detail</button>
                </div>
              </div>
            </div>
          </div>

          <!-- EMPTY STATE (Sama seperti pada Gambar Screenshot Anda) -->
          <div v-else class="empty-state-card">
            <div class="empty-icon-box">
              <span>📥</span>
            </div>
            <h3>Belum Ada Produk</h3>
            <p>Produk belum tersedia atau tidak cocok dengan pencarian Anda.</p>
          </div>
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
  .home-wrapper {
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
    transition: all 0.5s ease;
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
    transition: transform 0.3s ease;
  }

  .brand-logo:hover {
    transform: translateY(-2px);
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

  /* NAVIGATION LINKS MENU */
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
    transition: all 0.25s ease;
  }

  .nav-btn:hover {
    color: #2563eb;
    background: rgba(255, 255, 255, 0.5);
  }

  .nav-btn.active {
    background: #ffffff;
    color: #2563eb;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  }

  .btn-dashboard {
    background: #2563eb !important;
    color: #ffffff !important;
  }

  .btn-dashboard:hover {
    background: #1d4ed8 !important;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
  }

  /* AUTH SECTION & PROFILE BUTTON */
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

  /* HERO SECTION */
  .hero-section {
    padding: 60px 0 40px;
    position: relative;
    z-index: 1;
  }

  .hero-container {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    align-items: center;
    gap: 40px;
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    margin-bottom: 20px;
  }

  .badge-dot {
    width: 8px;
    height: 8px;
    background: #2563eb;
    border-radius: 50%;
  }

  .hero-title {
    font-size: 2.8rem;
    font-weight: 800;
    line-height: 1.25;
    color: #0f172a;
    margin-bottom: 16px;
    letter-spacing: -0.03em;
  }

  .text-gradient {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .hero-subtitle {
    color: #64748b;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 28px;
    max-width: 500px;
  }

  .search-box {
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 12px 18px;
    max-width: 440px;
    box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.06);
    transition: all 0.25s ease;
  }

  .search-box:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 12px 28px -5px rgba(37, 99, 235, 0.15);
  }

  .search-icon {
    margin-right: 12px;
    opacity: 0.6;
  }

  .search-box input {
    border: none;
    outline: none;
    width: 100%;
    font-size: 0.9rem;
    background: transparent;
    color: inherit;
  }

  /* RIGHT HERO BRAND CARD */
  .brand-highlight-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(226, 232, 240, 0.9);
    border-radius: 24px;
    padding: 36px;
    text-align: center;
    box-shadow: 0 20px 40px -15px rgba(37, 99, 235, 0.1);
  }

  .card-logo-box {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border-radius: 18px;
    margin: 0 auto 16px;
    padding: 10px;
    box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);
  }

  .brand-highlight-card h2 {
    font-size: 1.3rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 4px;
  }

  .brand-highlight-card p {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 24px;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    background: #f8fafc;
    padding: 16px;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
  }

  .stat-item h3 {
    font-size: 1.1rem;
    font-weight: 800;
    color: #2563eb;
    margin-bottom: 2px;
  }

  .stat-item span {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 600;
  }

  /* CATALOG SECTION */
  .catalog-section {
    padding: 40px 0 80px;
    position: relative;
    z-index: 1;
  }

  .catalog-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 32px;
  }

  .catalog-title h2 {
    font-size: 1.6rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 4px;
  }

  .catalog-title p {
    color: #64748b;
    font-size: 0.9rem;
  }

  .category-pills {
    display: flex;
    gap: 8px;
  }

  .pill-btn {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #475569;
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

  .pill-btn:hover {
    border-color: #93c5fd;
    color: #2563eb;
  }

  .pill-btn.active {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
  }

  /* EMPTY STATE CARD */
  .empty-state-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 60px 20px;
    text-align: center;
    box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.03);
  }

  .empty-icon-box {
    width: 56px;
    height: 56px;
    background: #eff6ff;
    color: #2563eb;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 1.5rem;
  }

  .empty-state-card h3 {
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 6px;
  }

  .empty-state-card p {
    color: #64748b;
    font-size: 0.875rem;
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

  .dark-mode .brand-text,
  .dark-mode .hero-title,
  .dark-mode .catalog-title h2,
  .dark-mode .brand-highlight-card h2,
  .dark-mode .empty-state-card h3 {
    color: #f8fafc !important;
  }

  .dark-mode .nav-links {
    background: rgba(30, 41, 59, 0.7) !important;
    border-color: #334155 !important;
  }

  .dark-mode .nav-btn {
    color: #94a3b8 !important;
  }

  .dark-mode .nav-btn.active {
    background: #1e293b !important;
    color: #60a5fa !important;
  }

  .dark-mode .btn-theme-toggle,
  .dark-mode .pill-btn {
    background: #1e293b !important;
    border-color: #334155 !important;
    color: #cbd5e1 !important;
  }

  .dark-mode .pill-btn.active {
    background: #2563eb !important;
    color: #ffffff !important;
  }

  .dark-mode .user-email {
    color: #f8fafc !important;
  }

  .dark-mode .greet-label,
  .dark-mode .hero-subtitle,
  .dark-mode .catalog-title p,
  .dark-mode .empty-state-card p,
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

  .dark-mode .btn-profile {
    background: #1e293b !important;
    border-color: #334155 !important;
    color: #60a5fa !important;
  }

  .dark-mode .btn-profile:hover {
    background: #2563eb !important;
    color: #ffffff !important;
  }

  .dark-mode .search-box,
  .dark-mode .brand-highlight-card,
  .dark-mode .empty-state-card {
    background: rgba(30, 41, 59, 0.8) !important;
    border-color: #334155 !important;
  }

  .dark-mode .stats-grid {
    background: #0f172a !important;
    border-color: #1e293b !important;
  }

  .dark-mode .footer {
    background: linear-gradient(180deg, rgba(15, 23, 42, 1) 0%, rgba(15, 23, 42, 0.96) 100%) !important;
    border-top-color: #1e293b !important;
  }

  .dark-mode .footer-bottom {
    border-top-color: #1e293b !important;
  }

  /* RESPONSIVE DESIGN */
  @media (max-width: 900px) {
    .hero-container {
      grid-template-columns: 1fr;
    }

    .catalog-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 16px;
    }

    .footer-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 520px) {
    .footer-bottom {
      flex-direction: column;
      align-items: flex-start;
    }
  }
  </style>