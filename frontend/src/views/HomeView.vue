<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import '../style.css'

const router = useRouter()

// =========================================================
// LOGO CEMILKU
// Pastikan file tersimpan di: frontend/public/images/cemilku-logo.png
// =========================================================
const logoCemilku = '/images/cemilku-logo.png'

// =========================================================
// AUTHENTICATION
// =========================================================
const isLoggedIn = ref(false)
const currentUser = ref('')
const currentRole = ref('')

// Computed untuk memeriksa status Admin
const isAdmin = computed(() => {
  return currentRole.value === 'admin'
})

// =========================================================
// TOAST
// =========================================================
const toast = ref({
  show: false,
  message: '',
  type: 'success'
})

const showToast = (message, type = 'success') => {
  toast.value = {
    show: true,
    message,
    type
  }

  setTimeout(() => {
    toast.value.show = false
  }, 3000)
}

// =========================================================
// CHECK LOGIN & ROLE
// =========================================================
const checkAuth = () => {
  const user =
    localStorage.getItem('user') ||
    localStorage.getItem('username')

  const role =
    localStorage.getItem('userRole') ||
    localStorage.getItem('role')

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

// =========================================================
// LOGOUT
// =========================================================
const handleLogout = () => {
  localStorage.removeItem('user')
  localStorage.removeItem('username')
  localStorage.removeItem('role')
  localStorage.removeItem('userRole')
  localStorage.removeItem('isLoggedIn')

  isLoggedIn.value = false
  currentUser.value = ''
  currentRole.value = ''

  cart.value = []

  showToast('Anda berhasil keluar dari akun')
}

// =========================================================
// NAVIGATION
// =========================================================
const goToLogin = () => {
  router.push('/login')
}

const goToRegister = () => {
  router.push('/register')
}

const goToDetail = (id) => {
  router.push(`/detail/${id}`)
}

const goToAdmin = () => {
  router.push('/admin')
}

// =========================================================
// PRODUCT DATA
// =========================================================
const activeCategory = ref('Semua')

const categories = [
  'Semua',
  'Basreng',
  'Keripik',
  'Makaroni',
  'Kue'
]

const snacks = ref([
  {
    id: 1,
    name: 'Basreng Pedas Jeruk',
    category: 'Basreng',
    tag: 'Best Seller',
    spicyLevel: 3,
    price: 15000,
    desc: 'Baso goreng renyah dengan bumbu cabai dan daun jeruk khas Bandung.'
  },
  {
    id: 2,
    name: 'Keripik Singkong Balado',
    category: 'Keripik',
    tag: 'Favorit',
    spicyLevel: 2,
    price: 12000,
    desc: 'Keripik singkong tipis dan renyah dengan perpaduan rasa balado.'
  },
  {
    id: 3,
    name: 'Makaroni Pedas Bawang',
    category: 'Makaroni',
    tag: 'Terlaris',
    spicyLevel: 3,
    price: 10000,
    desc: 'Makaroni renyah dengan bumbu cabai dan aroma bawang yang gurih.'
  },
  {
    id: 4,
    name: 'Seblak Kering Bantat',
    category: 'Keripik',
    tag: 'Pedas',
    spicyLevel: 4,
    price: 12000,
    desc: 'Kerupuk seblak dengan perpaduan rempah dan kencur pilihan.'
  },
  {
    id: 5,
    name: 'Keripik Tempe Rempah',
    category: 'Keripik',
    tag: 'Gurih',
    spicyLevel: 1,
    price: 14000,
    desc: 'Tempe pilihan dengan adonan tepung berbumbu rempah tradisional.'
  },
  {
    id: 6,
    name: 'Kue Kacang Lumer',
    category: 'Kue',
    tag: 'Manis',
    spicyLevel: 0,
    price: 18000,
    desc: 'Kue kacang dengan rasa manis gurih dan tekstur yang lembut.'
  }
])

const filteredSnacks = computed(() => {
  if (activeCategory.value === 'Semua') {
    return snacks.value
  }

  return snacks.value.filter(
    item => item.category === activeCategory.value
  )
})

// =========================================================
// CART
// =========================================================
const cart = ref([])
const isCartModalOpen = ref(false)

const checkoutForm = ref({
  name: '',
  address: '',
  notes: ''
})

const cartCount = computed(() => {
  return cart.value.reduce(
    (total, item) => total + item.qty,
    0
  )
})

const cartTotal = computed(() => {
  return cart.value.reduce(
    (total, item) => total + item.price * item.qty,
    0
  )
})

// =========================================================
// ADD TO CART
// =========================================================
const handleAddToCart = (snack) => {
  if (!isLoggedIn.value) {
    showToast(
      'Silakan masuk ke akun terlebih dahulu.',
      'error'
    )

    setTimeout(() => {
      router.push('/login')
    }, 1000)

    return
  }

  const existingItem = cart.value.find(
    item => item.id === snack.id
  )

  if (existingItem) {
    existingItem.qty++
  } else {
    cart.value.push({
      id: snack.id,
      name: snack.name,
      price: snack.price,
      qty: 1
    })
  }

  showToast(
    `${snack.name} ditambahkan ke keranjang.`
  )
}

// =========================================================
// UPDATE QUANTITY
// =========================================================
const updateQty = (id, amount) => {
  const item = cart.value.find(
    item => item.id === id
  )

  if (!item) return

  item.qty += amount

  if (item.qty <= 0) {
    removeFromCart(id)
  }
}

// =========================================================
// REMOVE CART
// =========================================================
const removeFromCart = (id) => {
  cart.value = cart.value.filter(
    item => item.id !== id
  )

  if (cart.value.length === 0) {
    isCartModalOpen.value = false
  }
}

// =========================================================
// CHECKOUT
// =========================================================
const handleCheckout = () => {
  if (cart.value.length === 0) {
    showToast(
      'Keranjang belanja masih kosong.',
      'error'
    )

    return
  }

  if (
    !checkoutForm.value.name.trim() ||
    !checkoutForm.value.address.trim()
  ) {
    showToast(
      'Nama penerima dan alamat wajib diisi.',
      'error'
    )

    return
  }

  const phone = '6281234567890'

  let message =
    `Halo Cemilku, saya ingin melakukan pemesanan.\n\n`

  message += `DATA PEMESAN\n`
  message += `Nama: ${checkoutForm.value.name}\n`
  message += `Akun: ${currentUser.value}\n`
  message += `Alamat: ${checkoutForm.value.address}\n`

  if (checkoutForm.value.notes) {
    message += `Catatan: ${checkoutForm.value.notes}\n`
  }

  message += `\nDETAIL PESANAN\n`

  cart.value.forEach((item, index) => {
    message +=
      `${index + 1}. ${item.name} (${item.qty}x) - ` +
      `${formatRupiah(item.price * item.qty)}\n`
  })

  message += `\nTOTAL: ${formatRupiah(cartTotal.value)}\n`

  const waUrl =
    `https://wa.me/${phone}?text=` +
    encodeURIComponent(message)

  window.open(waUrl, '_blank')

  showToast(
    'Pesanan diteruskan ke WhatsApp.'
  )

  isCartModalOpen.value = false
}

// =========================================================
// FORMAT CURRENCY
// =========================================================
const formatRupiah = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(value)
}

// =========================================================
// SPICY LEVEL
// =========================================================
const getSpicyLabel = (level) => {
  if (level === 0) return 'Tidak Pedas'
  if (level === 1) return 'Ringan'
  if (level === 2) return 'Sedang'
  if (level === 3) return 'Pedas'

  return 'Sangat Pedas'
}
</script>

<template>
  <div class="home-wrapper">

    <Transition name="toast">
      <div
        v-if="toast.show"
        :class="['toast-notification', toast.type]"
      >
        <span class="toast-indicator"></span>

        <div>
          <strong>
            {{ toast.type === 'success' ? 'Berhasil' : 'Perhatian' }}
          </strong>

          <p>{{ toast.message }}</p>
        </div>
      </div>
    </Transition>


    <header class="navbar">
      <div class="container nav-content">

        <div
          class="brand-logo"
          @click="router.push('/')"
        >
          <div class="brand-mark logo-image-wrapper">
            <img
              :src="logoCemilku"
              alt="Logo Cemilku"
              class="cemilku-logo"
              draggable="false"
            />
          </div>

          <div class="brand-info">
            <span class="brand-text">
              Cemilku
            </span>

            <small>
              Snack Store
            </small>
          </div>
        </div>


        <nav class="main-navigation">
          <router-link
            to="/"
            class="nav-link"
            active-class="active"
            :class="{
              active: router.currentRoute.value.path === '/'
            }"
          >
            Produk
          </router-link>

          <router-link
            to="/tentang-kami"
            class="nav-link"
          >
            Tentang Kami
          </router-link>

          <router-link
            to="/kontak"
            class="nav-link"
          >
            Kontak
          </router-link>

          <router-link
            v-if="isAdmin"
            to="/admin"
            class="nav-link nav-admin-btn"
          >
            Dashboard
          </router-link>
        </nav>


        <div class="user-menu">
          <template v-if="isLoggedIn">
            <div class="user-profile">
              <div class="user-avatar">
                {{ currentUser.charAt(0).toUpperCase() }}
              </div>

              <div class="user-information">
                <span class="user-greeting">
                  Selamat datang
                </span>

                <strong>
                  {{ currentUser }}
                </strong>
              </div>
            </div>

            <button
              @click="handleLogout"
              class="btn-logout"
            >
              Keluar
            </button>
          </template>

          <template v-else>
            <button
              @click="goToLogin"
              class="btn-outline"
            >
              Masuk
            </button>

            <button
              @click="goToRegister"
              class="btn-primary"
            >
              Daftar
            </button>
          </template>
        </div>

      </div>
    </header>


    <section class="hero-section">
      <div class="container hero-container">

        <div class="hero-content">
          <span class="hero-label">
            CEMILAN PILIHAN
          </span>

          <h1 class="hero-title">
            Camilan berkualitas
            <br>
            <span>
              untuk setiap momen.
            </span>
          </h1>

          <p class="hero-subtitle">
            Pilihan camilan renyah dengan cita rasa khas
            yang diproses secara higienis dan dikemas
            untuk menjaga kualitas hingga sampai ke tangan Anda.
          </p>

          <div class="hero-actions">
            <a
              href="#produk"
              class="btn-primary hero-button"
            >
              Lihat Produk
            </a>

            <button
              v-if="isAdmin"
              @click="goToAdmin"
              class="btn-secondary hero-button"
            >
              Kelola Admin
            </button>
            <button
              v-else
              @click="goToRegister"
              class="btn-secondary hero-button"
            >
              Buat Akun
            </button>
          </div>

          <div class="hero-information">
            <div class="hero-stat">
              <strong>6+</strong>
              <span>Varian Produk</span>
            </div>

            <div class="stat-divider"></div>

            <div class="hero-stat">
              <strong>100%</strong>
              <span>Produk Pilihan</span>
            </div>

            <div class="stat-divider"></div>

            <div class="hero-stat">
              <strong>Bandung</strong>
              <span>Area Operasional</span>
            </div>
          </div>
        </div>


        <div class="hero-visual">
          <div class="hero-product-card">
            <div class="product-placeholder">
              <div class="hero-logo-circle">
                <img
                  :src="logoCemilku"
                  alt="Cemilku Snack Store"
                  class="hero-cemilku-logo"
                  draggable="false"
                />
              </div>
            </div>

            <div class="hero-product-info">
              <span>
                PRODUK UNGGULAN
              </span>

              <h3>
                Basreng Pedas Jeruk
              </h3>

              <div class="hero-product-bottom">
                <strong>
                  Rp 15.000
                </strong>

                <span>
                  Best Seller
                </span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>


    <main
      id="produk"
      class="container catalog-section"
    >
      <div class="catalog-header">
        <div>
          <span class="section-label">
            PRODUK KAMI
          </span>

          <h2 class="section-title">
            Pilihan camilan
          </h2>

          <p class="section-subtitle">
            Temukan produk favorit Anda dari berbagai
            kategori camilan Cemilku.
          </p>
        </div>

        <div class="categories-tab">
          <button
            v-for="category in categories"
            :key="category"
            :class="[
              'btn-category',
              {
                active: activeCategory === category
              }
            ]"
            @click="activeCategory = category"
          >
            {{ category }}
          </button>
        </div>
      </div>


      <div class="product-grid">
        <article
          v-for="snack in filteredSnacks"
          :key="snack.id"
          class="snack-card"
        >
          <div class="product-card-top">
            <span class="product-category">
              {{ snack.category }}
            </span>

            <span class="product-tag">
              {{ snack.tag }}
            </span>
          </div>

          <div class="product-image-placeholder">
            <div class="product-mini-logo">
              <img
                :src="logoCemilku"
                alt="Cemilku"
                draggable="false"
              />
            </div>
          </div>

          <div class="product-card-content">
            <div class="spicy-info">
              <span>
                Tingkat rasa
              </span>

              <strong>
                {{ getSpicyLabel(snack.spicyLevel) }}
              </strong>
            </div>

            <h3 class="snack-title">
              {{ snack.name }}
            </h3>

            <p class="snack-desc">
              {{ snack.desc }}
            </p>

            <div class="card-bottom">
              <div class="price-box">
                <span class="price-label">
                  Harga
                </span>

                <span class="price-amount">
                  {{ formatRupiah(snack.price) }}
                </span>
              </div>

              <div class="card-actions">
                <button
                  @click="goToDetail(snack.id)"
                  class="btn-detail"
                >
                  Detail
                </button>

                <button
                  @click="handleAddToCart(snack)"
                  class="btn-add"
                >
                  Tambah
                </button>
              </div>
            </div>
          </div>
        </article>
      </div>
    </main>


    <section class="trust-section">
      <div class="container trust-grid">
        <div class="trust-item">
          <div class="trust-number">
            01
          </div>

          <div>
            <h3>
              Produk Berkualitas
            </h3>

            <p>
              Produk dipilih dan diproses dengan
              memperhatikan kualitas bahan serta
              kebersihan.
            </p>
          </div>
        </div>

        <div class="trust-item">
          <div class="trust-number">
            02
          </div>

          <div>
            <h3>
              Harga Transparan
            </h3>

            <p>
              Informasi harga ditampilkan secara jelas
              sebelum pelanggan melakukan pemesanan.
            </p>
          </div>
        </div>

        <div class="trust-item">
          <div class="trust-number">
            03
          </div>

          <div>
            <h3>
              Pemesanan Mudah
            </h3>

            <p>
              Pelanggan dapat memilih produk,
              mengatur jumlah dan melakukan checkout
              dengan mudah.
            </p>
          </div>
        </div>
      </div>
    </section>


    <Transition name="cart">
      <div
        v-if="cartCount > 0"
        class="floating-cart"
      >
        <div class="cart-info">
          <div class="cart-count">
            {{ cartCount }}
          </div>

          <div>
            <span class="cart-label">
              Keranjang
            </span>

            <strong class="cart-total">
              {{ formatRupiah(cartTotal) }}
            </strong>
          </div>
        </div>

        <button
          @click="isCartModalOpen = true"
          class="btn-checkout"
        >
          Lihat Keranjang
        </button>
      </div>
    </Transition>


    <Transition name="modal">
      <div
        v-if="isCartModalOpen"
        class="modal-overlay"
        @click.self="isCartModalOpen = false"
      >
        <div class="modal-card cart-modal">
          <button
            @click="isCartModalOpen = false"
            class="btn-close"
            aria-label="Tutup"
          >
            ×
          </button>

          <div class="modal-header">
            <span class="section-label">
              CHECKOUT
            </span>

            <h2 class="modal-title">
              Keranjang Belanja
            </h2>

            <p class="modal-subtitle">
              Periksa kembali produk sebelum
              melanjutkan pemesanan.
            </p>
          </div>

          <div class="cart-items-list">
            <div
              v-for="item in cart"
              :key="item.id"
              class="cart-item"
            >
              <div class="cart-item-image">
                {{ item.name.charAt(0) }}
              </div>

              <div class="cart-item-details">
                <strong class="cart-item-name">
                  {{ item.name }}
                </strong>

                <span class="cart-item-price">
                  {{ formatRupiah(item.price) }}
                </span>
              </div>

              <div class="cart-item-actions">
                <div class="qty-control">
                  <button
                    @click="updateQty(item.id, -1)"
                    class="btn-qty"
                  >
                    −
                  </button>

                  <span class="qty-num">
                    {{ item.qty }}
                  </span>

                  <button
                    @click="updateQty(item.id, 1)"
                    class="btn-qty"
                  >
                    +
                  </button>
                </div>

                <button
                  @click="removeFromCart(item.id)"
                  class="btn-remove"
                >
                  Hapus
                </button>
              </div>
            </div>
          </div>

          <div class="checkout-form-container">
            <div class="form-section-header">
              <h3>
                Informasi Pengiriman
              </h3>

              <span>
                Wajib diisi
              </span>
            </div>

            <div class="form-group">
              <label>
                Nama Penerima
              </label>

              <input
                v-model="checkoutForm.name"
                type="text"
                placeholder="Masukkan nama lengkap"
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label>
                Alamat Lengkap
              </label>

              <textarea
                v-model="checkoutForm.address"
                placeholder="Masukkan alamat pengiriman"
                rows="3"
                class="form-input form-textarea"
              ></textarea>
            </div>

            <div class="form-group">
              <label>
                Catatan Pesanan
                <span>(Opsional)</span>
              </label>

              <input
                v-model="checkoutForm.notes"
                type="text"
                placeholder="Contoh: Jangan terlalu pedas"
                class="form-input"
              />
            </div>
          </div>

          <div class="cart-summary">
            <div class="summary-row">
              <span>
                Total Pesanan
              </span>

              <strong class="summary-total">
                {{ formatRupiah(cartTotal) }}
              </strong>
            </div>

            <button
              @click="handleCheckout"
              class="btn-wa-checkout"
            >
              Lanjutkan Pemesanan
            </button>

            <p class="checkout-note">
              Anda akan diarahkan ke WhatsApp untuk
              menyelesaikan pemesanan.
            </p>
          </div>
        </div>
      </div>
    </Transition>


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
            <strong>
              Cemilku
            </strong>

            <span>
              Snack Store
            </span>
          </div>
        </div>

        <div class="footer-description">
          Platform pemesanan camilan lokal
          dengan pengalaman belanja yang sederhana
          dan terpercaya.
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

/* FIXING HERO Visual & Circle Image */
.product-placeholder {
  width: 100%;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #18181b;
  border-radius: 12px;
  margin-bottom: 16px;
  overflow: hidden;
}

.hero-logo-circle {
  width: 130px;
  height: 130px;
  border-radius: 50%;
  background-color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  border: 4px solid #ea580c;
}

.hero-cemilku-logo {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* FIXING PRODUCT CARD IMAGE IN CATALOG GRID */
.product-image-placeholder {
  width: 100%;
  height: 180px;
  background-color: #f8fafc;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  margin-bottom: 16px;
  border: 1px solid #f1f5f9;
  overflow: hidden;
}

.product-mini-logo {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  background: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  border: 2px solid #fdba74;
}

.product-mini-logo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.logo-image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}
</style>