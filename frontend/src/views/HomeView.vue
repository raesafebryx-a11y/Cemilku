<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import '../style.css'

const router = useRouter()
const logoCemilku = '/images/cemilku-logo.png'

// AUTHENTICATION STATE
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

onMounted(() => checkAuth())

const handleLogout = () => {
  ['user', 'username', 'role', 'userRole', 'isLoggedIn'].forEach(key => localStorage.removeItem(key))
  isLoggedIn.value = false
  currentUser.value = ''
  currentRole.value = ''
  cart.value = []
  showToast('Anda berhasil keluar')
}

// TOAST SYSTEM
const toast = ref({ show: false, message: '', type: 'success' })

const showToast = (message, type = 'success') => {
  toast.value = { show: true, message, type }
  setTimeout(() => { toast.value.show = false }, 3000)
}

// PRODUCT CATALOG DATA WITH IMAGES
const activeCategory = ref('Semua')
const categories = ['Semua', 'Basreng', 'Keripik', 'Makaroni', 'Kue']

const snacks = ref([
  {
    id: 1,
    name: 'Basreng Pedas Jeruk',
    category: 'Basreng',
    tag: 'Best Seller',
    spicyLevel: 3,
    price: 15000,
    desc: 'Baso goreng renyah bumbu cabai & daun jeruk khas Bandung.',
    image: 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 2,
    name: 'Keripik Singkong Balado',
    category: 'Keripik',
    tag: 'Favorit',
    spicyLevel: 2,
    price: 12000,
    desc: 'Keripik singkong tipis renyah dengan bumbu balado gurih.',
    image: 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 3,
    name: 'Makaroni Pedas Bawang',
    category: 'Makaroni',
    tag: 'Terlaris',
    spicyLevel: 3,
    price: 10000,
    desc: 'Makaroni renyah dengan racikan cabai dan aroma bawang.',
    image: 'https://images.unsplash.com/photo-1621996346565-e3d5d6281328?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 4,
    name: 'Seblak Kering Bantat',
    category: 'Keripik',
    tag: 'Pedas',
    spicyLevel: 4,
    price: 12000,
    desc: 'Kerupuk seblak pedas khas rempah kencur pilihan.',
    image: 'https://images.unsplash.com/photo-1601050690597-df0568f70950?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 5,
    name: 'Keripik Tempe Rempah',
    category: 'Keripik',
    tag: 'Gurih',
    spicyLevel: 1,
    price: 14000,
    desc: 'Tempe renyah balut adonan tepung berbumbu tradisional.',
    image: 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?q=80&w=600&auto=format&fit=crop'
  },
  {
    id: 6,
    name: 'Kue Kacang Lumer',
    category: 'Kue',
    tag: 'Manis',
    spicyLevel: 0,
    price: 18000,
    desc: 'Kue kacang manis gurih dengan tekstur lembut lumer.',
    image: 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?q=80&w=600&auto=format&fit=crop'
  }
])

const filteredSnacks = computed(() => {
  return activeCategory.value === 'Semua' 
    ? snacks.value 
    : snacks.value.filter(s => s.category === activeCategory.value)
})

// CART & CHECKOUT
const cart = ref([])
const isCartModalOpen = ref(false)
const checkoutForm = ref({ name: '', address: '', notes: '' })

const cartCount = computed(() => cart.value.reduce((sum, item) => sum + item.qty, 0))
const cartTotal = computed(() => cart.value.reduce((sum, item) => sum + item.price * item.qty, 0))

const handleAddToCart = (snack) => {
  if (!isLoggedIn.value) {
    showToast('Silakan masuk ke akun terlebih dahulu.', 'error')
    setTimeout(() => router.push('/login'), 1000)
    return
  }
  const item = cart.value.find(i => i.id === snack.id)
  if (item) item.qty++
  else cart.value.push({ id: snack.id, name: snack.name, price: snack.price, qty: 1, image: snack.image })
  showToast(`${snack.name} ditambahkan ke keranjang.`)
}

const updateQty = (id, amount) => {
  const item = cart.value.find(i => i.id === id)
  if (!item) return
  item.qty += amount
  if (item.qty <= 0) removeFromCart(id)
}

const removeFromCart = (id) => {
  cart.value = cart.value.filter(i => i.id !== id)
  if (!cart.value.length) isCartModalOpen.value = false
}

const handleCheckout = () => {
  if (!cart.value.length) return showToast('Keranjang masih kosong.', 'error')
  if (!checkoutForm.value.name.trim() || !checkoutForm.value.address.trim()) {
    return showToast('Nama & Alamat pengiriman wajib diisi.', 'error')
  }

  let msg = `*PEMESANAN CEMILKU*\n\n`
  msg += `Nama: ${checkoutForm.value.name}\nAkun: ${currentUser.value}\nAlamat: ${checkoutForm.value.address}\n`
  if (checkoutForm.value.notes) msg += `Catatan: ${checkoutForm.value.notes}\n`
  msg += `\n*DETAIL ITEMS:*\n`
  cart.value.forEach((item, idx) => {
    msg += `${idx + 1}. ${item.name} (${item.qty}x) - ${formatRupiah(item.price * item.qty)}\n`
  })
  msg += `\n*TOTAL:* ${formatRupiah(cartTotal.value)}`

  window.open(`https://wa.me/6281234567890?text=${encodeURIComponent(msg)}`, '_blank')
  isCartModalOpen.value = false
  showToast('Pesanan diteruskan ke WhatsApp.')
}

// HELPERS
const formatRupiah = (val) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val)
const getSpicyLabel = (level) => ['Tidak Pedas', 'Ringan', 'Sedang', 'Pedas', 'Sangat Pedas'][level] || 'Pedas'
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

    <!-- HEADER / NAVIGATION BAR -->
    <header class="navbar">
      <div class="container nav-content">
        <div class="brand-logo" @click="router.push('/')">
          <div class="brand-mark logo-image-wrapper">
            <img :src="logoCemilku" alt="Logo Cemilku" class="cemilku-logo" />
          </div>
          <div class="brand-info">
            <span class="brand-text">Cemilku</span>
            <small>Snack Store</small>
          </div>
        </div>

        <nav class="main-navigation">
          <router-link to="/" class="nav-link" active-class="active">Produk</router-link>
          <router-link to="/tentang-kami" class="nav-link">Tentang Kami</router-link>
          <router-link to="/kontak" class="nav-link">Kontak</router-link>
          <router-link v-if="isAdmin" to="/admin" class="nav-link nav-admin-btn">Dashboard</router-link>
        </nav>

        <div class="user-menu">
          <template v-if="isLoggedIn">
            <div class="user-profile">
              <div class="user-avatar">{{ currentUser.charAt(0).toUpperCase() }}</div>
              <div class="user-information">
                <span class="user-greeting">Halo,</span>
                <strong>{{ currentUser }}</strong>
              </div>
            </div>
            <button @click="handleLogout" class="btn-logout">Keluar</button>
          </template>
          <template v-else>
            <button @click="router.push('/login')" class="btn-outline">Masuk</button>
            <button @click="router.push('/register')" class="btn-primary">Daftar</button>
          </template>
        </div>
      </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero-section">
      <div class="container hero-container">
        <div class="hero-content">
          <span class="hero-label">CEMILAN PILIHAN</span>
          <h1 class="hero-title">Camilan berkualitas<br><span>untuk setiap momen.</span></h1>
          <p class="hero-subtitle">Pilihan camilan renyah cita rasa khas Bandung, higienis dan dikemas rapi hingga ke tangan Anda.</p>
          
          <div class="hero-actions">
            <a href="#produk" class="btn-primary hero-button">Lihat Produk</a>
            <button @click="isAdmin ? router.push('/admin') : router.push('/register')" class="btn-secondary hero-button">
              {{ isAdmin ? 'Kelola Admin' : 'Buat Akun' }}
            </button>
          </div>

          <div class="hero-information">
            <div class="hero-stat"><strong>6+</strong><span>Varian Produk</span></div>
            <div class="stat-divider"></div>
            <div class="hero-stat"><strong>100%</strong><span>Produk Pilihan</span></div>
            <div class="stat-divider"></div>
            <div class="hero-stat"><strong>Bandung</strong><span>Area Operasional</span></div>
          </div>
        </div>

        <div class="hero-visual">
          <div class="hero-product-card">
            <div class="hero-image-wrapper">
              <img 
                src="https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?q=80&w=600&auto=format&fit=crop" 
                alt="Basreng Pedas Jeruk" 
                class="hero-featured-img"
              />
            </div>
            <div class="hero-product-info">
              <span>PRODUK UNGGULAN</span>
              <h3>Basreng Pedas Jeruk</h3>
              <div class="hero-product-bottom">
                <strong>Rp 15.000</strong>
                <span class="badge-bestseller">Best Seller</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CATALOG GRID SECTION -->
    <main id="produk" class="container catalog-section">
      <div class="catalog-header">
        <div>
          <span class="section-label">PRODUK KAMI</span>
          <h2 class="section-title">Pilihan Camilan</h2>
          <p class="section-subtitle">Temukan produk favorit Anda dari katalog Cemilku.</p>
        </div>

        <div class="categories-tab">
          <button 
            v-for="cat in categories" 
            :key="cat" 
            :class="['btn-category', { active: activeCategory === cat }]"
            @click="activeCategory = cat"
          >
            {{ cat }}
          </button>
        </div>
      </div>

      <div class="product-grid">
        <article v-for="snack in filteredSnacks" :key="snack.id" class="snack-card">
          <div class="product-card-top">
            <span class="product-category">{{ snack.category }}</span>
            <span class="product-tag">{{ snack.tag }}</span>
          </div>

          <!-- GAMBAR PRODUK SUNGGUHAN -->
          <div class="product-image-container">
            <img :src="snack.image" :alt="snack.name" class="snack-img" />
          </div>

          <div class="product-card-content">
            <div class="spicy-info">
              <span>Tingkat Rasa</span>
              <strong>{{ getSpicyLabel(snack.spicyLevel) }}</strong>
            </div>

            <h3 class="snack-title">{{ snack.name }}</h3>
            <p class="snack-desc">{{ snack.desc }}</p>

            <div class="card-bottom">
              <div class="price-box">
                <span class="price-label">Harga</span>
                <span class="price-amount">{{ formatRupiah(snack.price) }}</span>
              </div>
              <div class="card-actions">
                <button @click="router.push(`/detail/${snack.id}`)" class="btn-detail">Detail</button>
                <button @click="handleAddToCart(snack)" class="btn-add">Tambah</button>
              </div>
            </div>
          </div>
        </article>
      </div>
    </main>

    <!-- TRUST VALUES SECTION -->
    <section class="trust-section">
      <div class="container trust-grid">
        <div class="trust-item">
          <div class="trust-number">01</div>
          <div>
            <h3>Produk Berkualitas</h3>
            <p>Bahan higienis pilihan diproses dengan standar mutu terjamin.</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-number">02</div>
          <div>
            <h3>Harga Transparan</h3>
            <p>Informasi harga jelas tanpa biaya tersembunyi saat checkout.</p>
          </div>
        </div>
        <div class="trust-item">
          <div class="trust-number">03</div>
          <div>
            <h3>Pemesanan Mudah</h3>
            <p>Pilih produk favorit lalu checkout langsung via WhatsApp.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- FLOATING CART BUTTON -->
    <Transition name="cart">
      <div v-if="cartCount > 0" class="floating-cart">
        <div class="cart-info">
          <div class="cart-count">{{ cartCount }}</div>
          <div>
            <span class="cart-label">Keranjang</span>
            <strong class="cart-total">{{ formatRupiah(cartTotal) }}</strong>
          </div>
        </div>
        <button @click="isCartModalOpen = true" class="btn-checkout">Lihat Keranjang</button>
      </div>
    </Transition>

    <!-- MODAL CART CHECKOUT -->
    <Transition name="modal">
      <div v-if="isCartModalOpen" class="modal-overlay" @click.self="isCartModalOpen = false">
        <div class="modal-card cart-modal">
          <button @click="isCartModalOpen = false" class="btn-close">&times;</button>
          
          <div class="modal-header">
            <span class="section-label">CHECKOUT</span>
            <h2 class="modal-title">Keranjang Belanja</h2>
            <p class="modal-subtitle">Periksa kembali barang sebelum memesan.</p>
          </div>

          <div class="cart-items-list">
            <div v-for="item in cart" :key="item.id" class="cart-item">
              <img :src="item.image" :alt="item.name" class="cart-item-thumb" />
              <div class="cart-item-details">
                <strong class="cart-item-name">{{ item.name }}</strong>
                <span class="cart-item-price">{{ formatRupiah(item.price) }}</span>
              </div>
              <div class="cart-item-actions">
                <div class="qty-control">
                  <button @click="updateQty(item.id, -1)" class="btn-qty">−</button>
                  <span class="qty-num">{{ item.qty }}</span>
                  <button @click="updateQty(item.id, 1)" class="btn-qty">+</button>
                </div>
                <button @click="removeFromCart(item.id)" class="btn-remove">Hapus</button>
              </div>
            </div>
          </div>

          <div class="checkout-form-container">
            <div class="form-section-header">
              <h3>Informasi Pengiriman</h3>
              <span>Wajib diisi</span>
            </div>
            <div class="form-group">
              <label>Nama Penerima</label>
              <input v-model="checkoutForm.name" type="text" placeholder="Masukkan nama lengkap" class="form-input" />
            </div>
            <div class="form-group">
              <label>Alamat Lengkap</label>
              <textarea v-model="checkoutForm.address" placeholder="Masukkan alamat pengiriman" rows="2" class="form-input form-textarea"></textarea>
            </div>
            <div class="form-group">
              <label>Catatan Pesanan <span>(Opsional)</span></label>
              <input v-model="checkoutForm.notes" type="text" placeholder="Contoh: Bumbu pedasnya dipisah" class="form-input" />
            </div>
          </div>

          <div class="cart-summary">
            <div class="summary-row">
              <span>Total Pesanan</span>
              <strong class="summary-total">{{ formatRupiah(cartTotal) }}</strong>
            </div>
            <button @click="handleCheckout" class="btn-wa-checkout">Lanjutkan Pemesanan</button>
            <p class="checkout-note">Pesanan diteruskan ke WhatsApp resmi Cemilku.</p>
          </div>
        </div>
      </div>
    </Transition>

    <!-- FOOTER -->
    <footer class="footer">
      <div class="container footer-content">
        <div class="footer-brand">
          <div class="brand-mark logo-image-wrapper footer-logo">
            <img :src="logoCemilku" alt="Logo Cemilku" />
          </div>
          <div>
            <strong>Cemilku</strong>
            <span>Snack Store</span>
          </div>
        </div>
        <div class="footer-description">
          Platform pemesanan camilan lokal Bandung berkualitas tinggi.
        </div>
        <div class="footer-copy">
          © 2026 Cemilku. All rights reserved.
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.home-wrapper {
  min-height: 100vh;
  background:
    radial-gradient(circle at top right, rgba(249, 115, 22, 0.08), transparent 26%),
    linear-gradient(180deg, #fffaf6 0%, #fff 100%);
  color: #1f2937;
}

.navbar {
  position: sticky;
  top: 0;
  z-index: 40;
  backdrop-filter: blur(14px);
  background: rgba(18, 14, 12, 0.8);
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
}

.brand-mark {
  width: 46px;
  height: 46px;
  border-radius: 14px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #fb923c, #ea580c);
  box-shadow: 0 12px 24px rgba(249, 115, 22, 0.25);
}

.brand-mark img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.brand-text {
  color: #fff;
  font-size: 1.6rem;
  font-weight: 800;
  letter-spacing: -0.06em;
}

.brand-info small {
  display: block;
  margin-top: 2px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.72);
  font-size: 0.62rem;
}

.main-navigation {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 6px;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.06);
  border: 1px solid rgba(255, 255, 255, 0.08);
}

.nav-link {
  color: rgba(255, 255, 255, 0.72);
  font-size: 0.74rem;
  font-weight: 700;
  padding: 9px 14px;
  border-radius: 9px;
  transition: all 0.2s ease;
}

.nav-link:hover,
.nav-link.active {
  color: #fff;
  background: rgba(249, 115, 22, 0.16);
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 10px;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 10px;
  padding-right: 6px;
}

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: grid;
  place-items: center;
  font-weight: 800;
  color: #fff;
  background: linear-gradient(135deg, #fb923c, #ea580c);
}

.user-information {
  display: flex;
  flex-direction: column;
  line-height: 1.2;
}

.user-greeting {
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.58rem;
}

.user-information strong {
  color: #fff;
  font-size: 0.72rem;
}

.btn-outline,
.btn-primary,
.btn-secondary,
.btn-logout,
.btn-checkout,
.btn-wa-checkout,
.btn-detail,
.btn-add,
.btn-qty,
.btn-close {
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-outline,
.btn-primary,
.btn-secondary,
.btn-logout {
  height: 40px;
  padding: 0 17px;
  border-radius: 10px;
  font-size: 0.7rem;
  font-weight: 700;
}

.btn-outline {
  background: rgba(255, 255, 255, 0.04);
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.12);
}

.btn-outline:hover {
  background: rgba(255, 255, 255, 0.08);
}

.btn-primary,
.btn-add,
.btn-checkout,
.btn-wa-checkout {
  background: linear-gradient(135deg, #fb923c, #ea580c);
  color: #fff;
  box-shadow: 0 12px 24px rgba(249, 115, 22, 0.18);
}

.btn-primary:hover,
.btn-add:hover,
.btn-checkout:hover,
.btn-wa-checkout:hover {
  transform: translateY(-1px);
  filter: brightness(1.03);
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.06);
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.12);
}

.btn-secondary:hover {
  background: rgba(255, 255, 255, 0.1);
}

.btn-logout {
  background: rgba(239, 68, 68, 0.08);
  color: #fecaca;
  border: 1px solid rgba(248, 113, 113, 0.24);
}

.btn-logout:hover {
  background: rgba(239, 68, 68, 0.14);
}

.nav-admin-btn {
  background: linear-gradient(135deg, #f97316, #ea580c) !important;
  color: #fff !important;
  border-radius: 8px;
}

.hero-section {
  position: relative;
  overflow: hidden;
  background:
    radial-gradient(circle at 18% 20%, rgba(249, 115, 22, 0.2), transparent 18%),
    linear-gradient(135deg, #201914 0%, #120d0b 100%);
  border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}

.hero-section::before,
.hero-section::after {
  content: '';
  position: absolute;
  border-radius: 50%;
  border: 1px solid rgba(249, 115, 22, 0.12);
}

.hero-section::before {
  width: 440px;
  height: 440px;
  right: -120px;
  top: -140px;
}

.hero-section::after {
  width: 260px;
  height: 260px;
  left: -70px;
  bottom: -120px;
}

.hero-container {
  position: relative;
  z-index: 1;
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 64px;
  align-items: center;
  padding: 88px 0;
}

.hero-content {
  max-width: 620px;
}

.hero-label,
.section-label {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 7px 12px;
  border-radius: 999px;
  background: rgba(249, 115, 22, 0.1);
  border: 1px solid rgba(249, 115, 22, 0.24);
  color: #fdba74;
  letter-spacing: 0.18em;
  font-size: 0.63rem;
  font-weight: 800;
  text-transform: uppercase;
}

.hero-title {
  margin: 20px 0 18px;
  color: #fff;
  font-size: clamp(2.8rem, 5vw, 5rem);
  line-height: 1.02;
  letter-spacing: -0.08em;
  font-weight: 900;
}

.hero-title span {
  color: #fb923c;
}

.hero-subtitle {
  max-width: 560px;
  color: rgba(255, 255, 255, 0.72);
  font-size: 1rem;
  line-height: 1.8;
  margin-bottom: 30px;
}

.hero-actions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 36px;
}

.hero-button {
  height: 48px;
  padding: 0 24px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  font-weight: 800;
  font-size: 0.73rem;
}

.hero-information {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 18px;
}

.hero-stat {
  display: flex;
  flex-direction: column;
  min-width: 110px;
}

.hero-stat strong {
  color: #fff;
  font-size: 1.25rem;
  font-weight: 800;
}

.hero-stat span {
  color: rgba(255, 255, 255, 0.58);
  font-size: 0.68rem;
  margin-top: 4px;
}

.stat-divider {
  width: 1px;
  height: 30px;
  background: rgba(255, 255, 255, 0.12);
}

.hero-visual {
  display: flex;
  justify-content: flex-end;
}

.hero-product-card {
  width: min(100%, 380px);
  background: linear-gradient(145deg, #2a211d, #18120f);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 30px 80px rgba(0, 0, 0, 0.42);
  transform: rotate(1.5deg);
  transition: transform 0.25s ease;
}

.hero-product-card:hover {
  transform: rotate(0deg) translateY(-6px);
}

.hero-image-wrapper {
  width: 100%;
  height: 240px;
  overflow: hidden;
  background: #2e1d17;
}

.hero-featured-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.35s ease;
}

.hero-product-card:hover .hero-featured-img {
  transform: scale(1.05);
}

.hero-product-info {
  padding: 20px 22px 22px;
}

.hero-product-info > span {
  display: block;
  color: rgba(255, 255, 255, 0.62);
  letter-spacing: 0.18em;
  font-size: 0.58rem;
  font-weight: 700;
}

.hero-product-info h3 {
  margin-top: 8px;
  color: #fff;
  font-size: 1.45rem;
  font-weight: 800;
}

.hero-product-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-top: 18px;
}

.hero-product-bottom strong {
  color: #fdba74;
  font-size: 1.08rem;
}

.badge-bestseller {
  background: rgba(34, 197, 94, 0.12);
  border: 1px solid rgba(34, 197, 94, 0.3);
  color: #86efac;
  padding: 6px 10px;
  font-size: 0.64rem;
  border-radius: 999px;
  font-weight: 800;
}

.catalog-section {
  padding: 86px 0 100px;
}

.catalog-header {
  display: flex;
  align-items: end;
  justify-content: space-between;
  gap: 24px;
  margin-bottom: 30px;
}

.section-label {
  background: rgba(249, 115, 22, 0.08);
  color: #ea580c;
  border-color: rgba(249, 115, 22, 0.18);
}

.section-title {
  margin: 12px 0 6px;
  font-size: clamp(2rem, 3vw, 2.8rem);
  line-height: 1.1;
  color: #1f2937;
  letter-spacing: -0.06em;
  font-weight: 900;
}

.section-subtitle {
  color: #6b7280;
  font-size: 0.9rem;
  line-height: 1.7;
}

.categories-tab {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  padding: 6px;
  background: #f4efe9;
  border: 1px solid #eadfd6;
  border-radius: 14px;
}

.btn-category {
  border: none;
  background: transparent;
  color: #6b7280;
  font-size: 0.68rem;
  font-weight: 800;
  padding: 10px 14px;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-category.active,
.btn-category:hover {
  background: #fff;
  color: #c2410c;
  box-shadow: 0 6px 12px rgba(15, 23, 42, 0.06);
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 22px;
}

.snack-card {
  background: linear-gradient(180deg, #fffdfb 0%, #fff 100%);
  border: 1px solid #f1e7df;
  border-radius: 22px;
  padding: 18px;
  box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
  transition: all 0.25s ease;
}

.snack-card:hover {
  transform: translateY(-6px);
  border-color: #f4d4b7;
  box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
}

.product-card-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 14px;
}

.product-category {
  color: #9ca3af;
  font-size: 0.6rem;
  font-weight: 800;
  letter-spacing: 0.14em;
  text-transform: uppercase;
}

.product-tag {
  background: #fff7ed;
  border: 1px solid #fed7aa;
  color: #c2410c;
  padding: 5px 9px;
  border-radius: 7px;
  font-size: 0.58rem;
  font-weight: 800;
}

.product-image-container {
  width: 100%;
  height: 190px;
  overflow: hidden;
  border-radius: 16px;
  background: linear-gradient(135deg, #f2e8df, #f7f3ee);
  margin-bottom: 16px;
}

.snack-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.35s ease;
}

.snack-card:hover .snack-img {
  transform: scale(1.06);
}

.product-card-content {
  display: flex;
  flex-direction: column;
}

.spicy-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

.spicy-info span,
.spicy-info strong {
  font-size: 0.62rem;
  color: #6b7280;
}

.spicy-info strong {
  color: #374151;
  font-weight: 800;
}

.snack-title {
  margin: 0 0 8px;
  color: #1f2937;
  font-size: 1.06rem;
  font-weight: 800;
  letter-spacing: -0.04em;
}

.snack-desc {
  min-height: 46px;
  margin: 0 0 18px;
  color: #6b7280;
  font-size: 0.74rem;
  line-height: 1.7;
}

.card-bottom {
  display: flex;
  align-items: end;
  justify-content: space-between;
  gap: 12px;
  padding-top: 14px;
  border-top: 1px solid #f1e5dd;
}

.price-box {
  display: flex;
  flex-direction: column;
}

.price-label {
  color: #9ca3af;
  font-size: 0.56rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.price-amount {
  color: #c2410c;
  font-size: 1.08rem;
  font-weight: 900;
}

.card-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-detail,
.btn-add {
  height: 34px;
  padding: 0 12px;
  border-radius: 10px;
  font-size: 0.64rem;
  font-weight: 800;
}

.btn-detail {
  background: #fff;
  border: 1px solid #eadfd6;
  color: #374151;
}

.btn-detail:hover {
  background: #f8f4f1;
}

.trust-section {
  background: linear-gradient(135deg, #1d1714 0%, #271d18 100%);
  border-top: 1px solid rgba(255, 255, 255, 0.05);
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  padding: 64px 0;
}

.trust-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 30px;
}

.trust-item {
  display: flex;
  align-items: flex-start;
  gap: 16px;
}

.trust-number {
  color: #fdba74;
  font-weight: 800;
  font-size: 0.9rem;
  padding-top: 4px;
}

.trust-item h3 {
  margin: 0 0 6px;
  color: #fff;
  font-size: 1rem;
  font-weight: 800;
}

.trust-item p {
  margin: 0;
  color: rgba(255, 255, 255, 0.64);
  font-size: 0.74rem;
  line-height: 1.8;
}

.floating-cart {
  position: fixed;
  right: 22px;
  bottom: 22px;
  z-index: 70;
  min-width: 300px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  background: rgba(17, 12, 10, 0.94);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 16px;
  padding: 12px 14px;
  box-shadow: 0 24px 50px rgba(0, 0, 0, 0.28);
}

.cart-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.cart-count {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: grid;
  place-items: center;
  background: linear-gradient(135deg, #fb923c, #ea580c);
  color: #fff;
  font-weight: 800;
}

.cart-label {
  display: block;
  color: rgba(255, 255, 255, 0.55);
  font-size: 0.58rem;
  margin-bottom: 4px;
}

.cart-total {
  color: #fff;
  font-size: 0.8rem;
}

.btn-checkout {
  height: 36px;
  padding: 0 14px;
  border-radius: 10px;
  font-size: 0.66rem;
  font-weight: 800;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 90;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background: rgba(17, 12, 10, 0.7);
  backdrop-filter: blur(5px);
}

.modal-card {
  position: relative;
  width: min(100%, 700px);
  max-height: 90vh;
  overflow-y: auto;
  background: #fffdfb;
  border: 1px solid #f0e4dc;
  border-radius: 24px;
  box-shadow: 0 28px 80px rgba(17, 12, 10, 0.3);
}

.cart-modal {
  padding: 28px;
}

.btn-close {
  position: absolute;
  top: 16px;
  right: 16px;
  width: 34px;
  height: 34px;
  border-radius: 8px;
  background: #f8f3ef;
  color: #4b5563;
  font-size: 1.4rem;
}

.modal-header {
  padding-right: 40px;
  margin-bottom: 20px;
}

.modal-title {
  margin: 14px 0 8px;
  font-size: 1.9rem;
  font-weight: 900;
  letter-spacing: -0.06em;
  color: #111827;
}

.modal-subtitle {
  color: #6b7280;
  font-size: 0.74rem;
}

.cart-items-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 24px;
}

.cart-item {
  display: grid;
  grid-template-columns: 56px 1fr auto;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border-radius: 14px;
  background: #f6f1ee;
  border: 1px solid #f1e5dd;
}

.cart-item-thumb {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  object-fit: cover;
}

.cart-item-details {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.cart-item-name {
  font-size: 0.8rem;
  color: #111827;
  font-weight: 800;
}

.cart-item-price {
  color: #c2410c;
  font-size: 0.7rem;
  font-weight: 800;
  margin-top: 4px;
}

.cart-item-actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

.qty-control {
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-qty {
  background: #fff;
  border: 1px solid #eadfd6;
  color: #374151;
  width: 28px;
  height: 28px;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 800;
}

.qty-num {
  min-width: 18px;
  text-align: center;
  font-weight: 800;
  color: #111827;
  font-size: 0.74rem;
}

.btn-remove {
  border: none;
  background: transparent;
  color: #dc2626;
  font-size: 0.64rem;
  font-weight: 800;
  cursor: pointer;
}

.checkout-form-container {
  border-top: 1px solid #f1e5dd;
  padding-top: 20px;
}

.form-section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}

.form-section-header h3 {
  margin: 0;
  color: #111827;
  font-size: 0.8rem;
  font-weight: 800;
}

.form-section-header span {
  color: #c2410c;
  font-size: 0.6rem;
  font-weight: 800;
}

.form-group {
  margin-bottom: 14px;
}

.form-group label {
  display: block;
  color: #374151;
  font-size: 0.64rem;
  font-weight: 800;
  margin-bottom: 6px;
}

.form-input {
  width: 100%;
  border: 1px solid #eadfd6;
  border-radius: 10px;
  background: #fff;
  color: #111827;
  padding: 10px 12px;
  font-size: 0.72rem;
  outline: none;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-input:focus {
  border-color: #fb923c;
  box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.08);
}

.form-textarea {
  min-height: 82px;
  resize: vertical;
}

.cart-summary {
  margin-top: 20px;
  border-top: 1px solid #f1e5dd;
  padding-top: 20px;
}

.summary-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}

.summary-row span {
  color: #6b7280;
  font-size: 0.7rem;
}

.summary-total {
  color: #c2410c;
  font-size: 1.2rem;
  font-weight: 900;
}

.btn-wa-checkout {
  width: 101;
  height: 44px;
  border-radius: 12px;
  font-size: 0.72rem;
  font-weight: 800;
}

.checkout-note {
  margin: 10px 0 0;
  text-align: center;
  color: #6b7280;
  font-size: 0.6rem;
}

.footer {
  background: #1a120f;
  border-top: 1px solid rgba(255, 255, 255, 0.06);
  padding: 34px 0;
}

.footer-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 18px;
}

.footer-brand {
  display: flex;
  align-items: center;
  gap: 12px;
}

.footer-logo {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  overflow: hidden;
}

.footer-brand strong {
  color: #fff;
  font-size: 1rem;
  font-weight: 800;
}

.footer-brand span {
  display: block;
  color: rgba(255, 255, 255, 0.58);
  font-size: 0.6rem;
  letter-spacing: 0.14em;
  text-transform: uppercase;
}

.footer-description,
.footer-copy {
  color: rgba(255, 255, 255, 0.6);
  font-size: 0.7rem;
}

@media (max-width: 980px) {
  .hero-container {
    grid-template-columns: 1fr;
    gap: 38px;
  }

  .hero-visual {
    justify-content: flex-start;
  }

  .product-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 760px) {
  .nav-content {
    height: 72px;
  }

  .main-navigation {
    display: none;
  }

  .brand-text {
    font-size: 1.3rem;
  }

  .catalog-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .product-grid,
  .trust-grid {
    grid-template-columns: 1fr;
  }

  .floating-cart {
    left: 14px;
    right: 14px;
    min-width: auto;
  }

  .footer-content {
    flex-direction: column;
    align-items: flex-start;
  }
}

@media (max-width: 520px) {
  .user-information {
    display: none;
  }

  .user-menu {
    gap: 8px;
  }

  .hero-container {
    padding: 70px 0;
  }

  .hero-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .hero-button {
    width: 100%;
  }

  .hero-information {
    gap: 10px;
  }

  .hero-stat {
    min-width: auto;
  }

  .cart-item {
    grid-template-columns: 52px 1fr;
  }

  .cart-item-actions {
    grid-column: 2;
    justify-content: space-between;
  }

  .btn-primary,
  .btn-outline,
  .btn-secondary,
  .btn-logout {
    padding: 0 12px;
  }
}
</style>