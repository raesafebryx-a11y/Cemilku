<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// =========================================================
// DATA KATEGORI
// =========================================================
// Sengaja kosong karena kategori belum tersedia.
// Nanti bisa diisi otomatis dari backend/API.

const categories = ref([])

// =========================================================
// FORM PRODUK
// =========================================================

const form = ref({
  name: '',
  category: '',
  price: '',
  stock: '',
  description: '',
  image: 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=500&auto=format&fit=crop&q=60'
})

// =========================================================
// FORMAT HARGA
// =========================================================

const formatPrice = (price) => {
  if (!price) {
    return 'Rp 0'
  }

  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(price)
}

// =========================================================
// SIMPAN PRODUK
// =========================================================

const handleSubmit = () => {
  if (
    !form.value.name ||
    !form.value.price ||
    !form.value.stock
  ) {
    alert('Mohon lengkapi data produk terlebih dahulu.')
    return
  }

  const products = JSON.parse(
    localStorage.getItem('cemilku_products') || '[]'
  )

  const newProduct = {
    id: Date.now(),
    name: form.value.name,
    category: form.value.category,
    price: Number(form.value.price),
    stock: Number(form.value.stock),
    description: form.value.description,
    image: form.value.image || 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=500&auto=format&fit=crop&q=60'
  }

  products.push(newProduct)

  localStorage.setItem(
    'cemilku_products',
    JSON.stringify(products)
  )

  alert('Produk berhasil ditambahkan!')

  router.push('/admin/produk')
}

// =========================================================
// KEMBALI
// =========================================================

const handleCancel = () => {
  router.push('/admin/produk')
}

// =========================================================
// LOGOUT
// =========================================================

const handleLogout = () => {
  localStorage.clear()
  router.push('/login')
}
</script>

<template>
  <div class="dashboard-layout">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar">

      <!-- BRAND CEMILKU -->
      <div
        class="sidebar-brand"
        @click="router.push('/')"
      >

        <!-- LOGO KARDUS -->
        <div class="brand-mark">
          <span class="box-logo">
            📦
          </span>
        </div>

        <!-- NAMA BRAND -->
        <div class="brand-info">
          <span class="brand-text">
            Cemilku
          </span>

          <small>
            SNACK STORE
          </small>
        </div>

      </div>


      <!-- =================================================
           MENU SIDEBAR
      ================================================== -->

      <nav class="sidebar-menu">

        <div class="menu-category">
          MAIN
        </div>

        <router-link
          to="/admin"
          class="menu-item"
        >
          <span class="menu-icon">
            📊
          </span>

          <span>
            Dashboard
          </span>
        </router-link>


        <div class="menu-category">
          KELOLA TOKO
        </div>

        <router-link
          to="/admin/produk"
          class="menu-item active"
        >
          <span class="menu-icon">
            🍿
          </span>

          <span>
            Produk
          </span>
        </router-link>

        <router-link
          to="/admin/kategori"
          class="menu-item"
        >
          <span class="menu-icon">
            🏷️
          </span>

          <span>
            Kategori
          </span>
        </router-link>

        <router-link
          to="/admin/order"
          class="menu-item"
        >
          <span class="menu-icon">
            📑
          </span>

          <span>
            Order
          </span>
        </router-link>

        <router-link
          to="/admin/order-item"
          class="menu-item"
        >
          <span class="menu-icon">
            📋
          </span>

          <span>
            Order Item
          </span>
        </router-link>


        <div class="menu-category">
          SISTEM
        </div>

        <router-link
          to="/admin/pengaturan"
          class="menu-item"
        >
          <span class="menu-icon">
            ⚙️
          </span>

          <span>
            Pengaturan
          </span>
        </router-link>


        <!-- LOGOUT -->
        <a
          href="#"
          class="menu-item logout"
          @click.prevent="handleLogout"
        >
          <span class="menu-icon">
            🚪
          </span>

          <span>
            Keluar
          </span>
        </a>

      </nav>

    </aside>


    <!-- =====================================================
         MAIN WRAPPER
    ====================================================== -->

    <div class="main-wrapper">

      <!-- ===================================================
           TOPBAR
      ==================================================== -->

      <header class="topbar">

        <div class="search-box">

          <span class="search-icon">
            🔍
          </span>

          <input
            type="text"
            placeholder="Cari produk..."
          />

        </div>


        <div class="topbar-right">

          <button
            type="button"
            class="icon-btn"
            title="Notifikasi"
          >
            🔔
          </button>

          <div class="user-avatar">
            A
          </div>

        </div>

      </header>


      <!-- ===================================================
           CONTENT
      ==================================================== -->

      <main class="content-body">

        <!-- =================================================
             BANNER
        ================================================== -->

        <section class="dashboard-banner">

          <div class="banner-content">

            <div class="banner-left">

              <div class="breadcrumb">

                <span>
                  Produk
                </span>

                <b>
                  ›
                </b>

                <strong>
                  Tambah Produk
                </strong>

              </div>

              <h1>
                Tambah Produk Baru
              </h1>

              <p class="banner-sub">
                Tambahkan produk makanan ringan baru
                ke toko Cemilku.
              </p>

            </div>


            <!-- KEMBALI -->
            <button
              type="button"
              class="btn-back"
              @click="handleCancel"
            >
              <span>
                ←
              </span>

              Kembali
            </button>

          </div>

        </section>


        <!-- =================================================
             FORM CARD
        ================================================== -->

        <section class="form-card">

          <!-- HEADER FORM -->
          <div class="form-header">

            <div>

              <h3>
                Informasi Produk
              </h3>

              <p>
                Isi informasi produk dengan lengkap
                dan benar.
              </p>

            </div>


            <!-- IKON KARDUS -->
            <div class="form-icon">
              📦
            </div>

          </div>


          <!-- =================================================
               FORM
          ================================================== -->

          <form
            class="product-form"
            @submit.prevent="handleSubmit"
          >

            <!-- =================================================
                 NAMA + KATEGORI
            ================================================== -->

            <div class="form-grid">

              <!-- NAMA PRODUK -->
              <div class="form-group">

                <label for="product-name">
                  Nama Produk
                  <span>*</span>
                </label>

                <input
                  id="product-name"
                  v-model="form.name"
                  type="text"
                  placeholder="Contoh: Basreng Pedas Jeruk"
                  autocomplete="off"
                />

                <small>
                  Masukkan nama produk yang mudah dikenali.
                </small>

              </div>


              <!-- KATEGORI -->
              <div class="form-group">

                <label for="product-category">
                  Kategori
                </label>

                <select
                  id="product-category"
                  v-model="form.category"
                  :disabled="categories.length === 0"
                >

                  <option value="">
                    {{
                      categories.length === 0
                        ? 'Belum ada kategori'
                        : 'Pilih kategori'
                    }}
                  </option>

                  <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.name }}
                  </option>

                </select>

                <small v-if="categories.length === 0">
                  Belum ada kategori yang tersedia.
                </small>

                <small v-else>
                  Pilih kategori yang sesuai dengan produk.
                </small>

              </div>

            </div>


            <!-- =================================================
                 HARGA + STOK
            ================================================== -->

            <div class="form-grid">

              <!-- HARGA -->
              <div class="form-group">

                <label for="product-price">
                  Harga Produk
                  <span>*</span>
                </label>

                <div class="input-prefix">

                  <span>
                    Rp
                  </span>

                  <input
                    id="product-price"
                    v-model="form.price"
                    type="number"
                    min="0"
                    placeholder="15000"
                  />

                </div>

                <small>
                  Masukkan harga produk dalam Rupiah.
                </small>

              </div>


              <!-- STOK -->
              <div class="form-group">

                <label for="product-stock">
                  Stok Produk
                  <span>*</span>
                </label>

                <div class="input-suffix">

                  <input
                    id="product-stock"
                    v-model="form.stock"
                    type="number"
                    min="0"
                    placeholder="50"
                  />

                  <span>
                    pcs
                  </span>

                </div>

                <small>
                  Jumlah stok produk yang tersedia.
                </small>

              </div>

            </div>


            <!-- =================================================
                 DESKRIPSI
            ================================================== -->

            <div class="form-group full">

              <label for="product-description">
                Deskripsi Produk
              </label>

              <textarea
                id="product-description"
                v-model="form.description"
                rows="6"
                placeholder="Contoh: Basreng pedas dengan rasa jeruk yang gurih, renyah, dan cocok untuk teman santai."
              ></textarea>

              <small>
                Jelaskan rasa, ukuran, keunggulan,
                atau informasi lainnya.
              </small>

            </div>


            <!-- =================================================
                 RINGKASAN
            ================================================== -->

            <div
              v-if="
                form.name ||
                form.price ||
                form.stock
              "
              class="product-summary"
            >

              <div class="summary-title">
                Ringkasan Produk
              </div>


              <div class="summary-grid">

                <div class="summary-item">

                  <span>
                    Nama
                  </span>

                  <strong>
                    {{ form.name || '-' }}
                  </strong>

                </div>


                <div class="summary-item">

                  <span>
                    Kategori
                  </span>

                  <strong>
                    {{ form.category || 'Belum dipilih' }}
                  </strong>

                </div>


                <div class="summary-item">

                  <span>
                    Harga
                  </span>

                  <strong class="summary-price">
                    {{ formatPrice(form.price) }}
                  </strong>

                </div>


                <div class="summary-item">

                  <span>
                    Stok
                  </span>

                  <strong>
                    {{ form.stock || 0 }} pcs
                  </strong>

                </div>

              </div>

            </div>


            <!-- =================================================
                 BUTTON
            ================================================== -->

            <div class="form-actions">

              <button
                type="button"
                class="btn-cancel"
                @click="handleCancel"
              >
                Batal
              </button>


              <button
                type="submit"
                class="btn-save"
              >

                <span>
                  ✓
                </span>

                Simpan Produk

              </button>

            </div>

          </form>

        </section>

      </main>

    </div>

  </div>
</template>


<style scoped>

/* =========================================================
   CEMILKU ADMIN
   TAMBAH PRODUK
   DARK MODERN ADMIN
========================================================= */


/* =========================================================
   LAYOUT
========================================================= */

.dashboard-layout {
  display: flex;
  min-height: 100vh;
  width: 100%;
  background-color: #0f0f0f;
  color: #ffffff;
  font-family:
    system-ui,
    -apple-system,
    BlinkMacSystemFont,
    "Segoe UI",
    sans-serif;
}


/* =========================================================
   SIDEBAR
========================================================= */

.sidebar {
  width: 250px;
  min-height: 100vh;
  background-color: #18181b;
  border-right: 1px solid #27272a;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
}


/* =========================================================
   BRAND
========================================================= */

.sidebar-brand {
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid #27272a;
  cursor: pointer;
  transition: opacity 0.2s ease;
}

.sidebar-brand:hover {
  opacity: 0.9;
}


/* =========================================================
   LOGO KARDUS
========================================================= */

.brand-mark {
  width: 40px;
  height: 40px;
  border-radius: 10px;

  background: linear-gradient(
    135deg,
    #fb923c,
    #ea580c
  );

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  box-shadow:
    0 4px 12px rgba(234, 88, 12, 0.22);
}

.box-logo {
  font-size: 22px;
  line-height: 1;
  display: block;

  filter:
    drop-shadow(
      0 1px 1px rgba(0, 0, 0, 0.25)
    );
}


/* =========================================================
   BRAND TEXT
========================================================= */

.brand-info {
  display: flex;
  flex-direction: column;
}

.brand-text {
  color: #ffffff;
  font-size: 1.3rem;
  font-weight: 800;
  letter-spacing: -0.05em;
  line-height: 1.1;
}

.brand-info small {
  margin-top: 2px;
  color: #a1a1aa;
  font-size: 0.58rem;
  font-weight: 600;
  letter-spacing: 0.16em;
  text-transform: uppercase;
}


/* =========================================================
   SIDEBAR MENU
========================================================= */

.sidebar-menu {
  display: flex;
  flex-direction: column;
  gap: 4px;
  padding: 20px 12px;
}

.menu-category {
  padding: 12px 12px 4px;
  color: #71717a;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.5px;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 10px;

  padding: 10px 12px;

  border-radius: 8px;

  color: #a1a1aa;

  text-decoration: none;

  font-size: 14px;
  font-weight: 500;

  transition:
    background-color 0.2s ease,
    color 0.2s ease;
}

.menu-item:hover {
  background-color: #27272a;
  color: #ffffff;
}

.menu-item.router-link-active {
  background-color: #27272a;
  color: #ffffff;
}

.menu-item.active {
  background-color: #ea580c;
  color: #ffffff;
}

.menu-icon {
  width: 20px;
  min-width: 20px;
  text-align: center;
  font-size: 15px;
}

.menu-item.logout {
  color: #ef4444;
}

.menu-item.logout:hover {
  background-color: rgba(239, 68, 68, 0.1);
  color: #f87171;
}


/* =========================================================
   MAIN
========================================================= */

.main-wrapper {
  flex: 1;
  min-width: 0;

  display: flex;
  flex-direction: column;

  overflow-x: hidden;
}


/* =========================================================
   TOPBAR
========================================================= */

.topbar {
  height: 64px;
  min-height: 64px;

  padding: 0 28px;

  background-color: #18181b;

  border-bottom: 1px solid #27272a;

  display: flex;
  align-items: center;
  justify-content: space-between;
}

.search-box {
  width: 300px;

  display: flex;
  align-items: center;

  gap: 8px;

  padding: 8px 14px;

  background-color: #27272a;

  border-radius: 8px;
}

.search-icon {
  font-size: 13px;
  opacity: 0.7;
}

.search-box input {
  width: 100%;

  padding: 0;

  border: none;
  outline: none;

  background: transparent;

  color: #ffffff;

  font-size: 13px;
}

.search-box input::placeholder {
  color: #71717a;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 16px;
}

.icon-btn {
  padding: 4px;

  border: none;
  outline: none;

  background: transparent;

  cursor: pointer;

  font-size: 16px;
}

.user-avatar {
  width: 36px;
  height: 36px;

  border-radius: 50%;

  background-color: #ea580c;

  color: #ffffff;

  display: grid;
  place-items: center;

  font-weight: 700;
}


/* =========================================================
   CONTENT
========================================================= */

.content-body {
  width: 100%;
  box-sizing: border-box;

  padding: 0 28px 40px;
}


/* =========================================================
   BANNER
========================================================= */

.dashboard-banner {
  margin-top: 24px;

  padding: 28px;

  background:
    linear-gradient(
      135deg,
      #27272a 0%,
      #18181b 100%
    );

  border: 1px solid #3f3f46;

  border-radius: 12px;
}

.banner-content {
  display: flex;

  align-items: center;
  justify-content: space-between;

  gap: 20px;
}

.banner-left {
  min-width: 0;
}

.breadcrumb {
  display: flex;
  align-items: center;

  gap: 8px;

  margin-bottom: 8px;

  color: #71717a;

  font-size: 12px;
}

.breadcrumb b {
  color: #ea580c;

  font-size: 16px;

  font-weight: 500;
}

.breadcrumb strong {
  color: #a1a1aa;
  font-weight: 500;
}

.banner-content h1 {
  margin: 0;

  color: #ffffff;

  font-size: 22px;

  font-weight: 700;
}

.banner-sub {
  margin: 6px 0 0;

  color: #a1a1aa;

  font-size: 13px;
}


/* =========================================================
   BACK BUTTON
========================================================= */

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;

  padding: 10px 18px;

  border: 1px solid #3f3f46;

  border-radius: 8px;

  background-color: #27272a;

  color: #d4d4d8;

  font-size: 13px;
  font-weight: 600;

  cursor: pointer;

  transition:
    background-color 0.2s ease,
    color 0.2s ease,
    transform 0.2s ease;
}

.btn-back:hover {
  background-color: #3f3f46;

  color: #ffffff;

  transform: translateX(-2px);
}


/* =========================================================
   FORM CARD
========================================================= */

.form-card {
  margin-top: 24px;

  padding: 28px;

  background-color: #18181b;

  border: 1px solid #27272a;

  border-radius: 12px;
}

.form-header {
  display: flex;
  align-items: center;
  justify-content: space-between;

  gap: 20px;

  margin-bottom: 26px;

  padding-bottom: 22px;

  border-bottom: 1px solid #27272a;
}

.form-header h3 {
  margin: 0;

  color: #ffffff;

  font-size: 17px;
  font-weight: 700;
}

.form-header p {
  margin: 5px 0 0;

  color: #71717a;

  font-size: 13px;
}


/* =========================================================
   FORM ICON KARDUS
========================================================= */

.form-icon {
  width: 44px;
  height: 44px;

  border-radius: 10px;

  background-color: rgba(234, 88, 12, 0.12);

  border: 1px solid rgba(234, 88, 12, 0.25);

  display: grid;
  place-items: center;

  font-size: 21px;
}


/* =========================================================
   FORM GRID
========================================================= */

.product-form {
  width: 100%;
}

.form-grid {
  display: grid;

  grid-template-columns:
    repeat(2, minmax(0, 1fr));

  gap: 22px;

  margin-bottom: 22px;
}

.form-group {
  display: flex;
  flex-direction: column;

  min-width: 0;
}

.form-group.full {
  margin-bottom: 22px;
}

.form-group label {
  margin-bottom: 8px;

  color: #e4e4e7;

  font-size: 13px;
  font-weight: 600;
}

.form-group label span {
  color: #f97316;
  margin-left: 2px;
}


/* =========================================================
   INPUT
========================================================= */

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  box-sizing: border-box;

  padding: 11px 13px;

  border: 1px solid #3f3f46;

  border-radius: 8px;

  outline: none;

  background-color: #27272a;

  color: #ffffff;

  font-family: inherit;

  font-size: 13px;

  transition:
    border-color 0.2s ease,
    box-shadow 0.2s ease;
}

.form-group input::placeholder,
.form-group textarea::placeholder {
  color: #71717a;
}

.form-group select {
  cursor: pointer;
}

.form-group select:disabled {
  opacity: 0.65;
  cursor: not-allowed;
  color: #71717a;
}

.form-group textarea {
  min-height: 120px;

  resize: vertical;

  line-height: 1.6;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #ea580c;

  box-shadow:
    0 0 0 3px rgba(234, 88, 12, 0.1);
}

.form-group small {
  margin-top: 6px;

  color: #71717a;

  font-size: 11px;

  line-height: 1.4;
}


/* =========================================================
   HARGA
========================================================= */

.input-prefix {
  display: flex;
  align-items: stretch;
}

.input-prefix > span {
  display: flex;
  align-items: center;

  padding: 0 12px;

  border: 1px solid #3f3f46;

  border-right: none;

  border-radius: 8px 0 0 8px;

  background-color: #3f3f46;

  color: #a1a1aa;

  font-size: 12px;

  font-weight: 600;
}

.input-prefix input {
  border-radius: 0 8px 8px 0;
}


/* =========================================================
   STOK
========================================================= */

.input-suffix {
  display: flex;
  align-items: stretch;
}

.input-suffix input {
  border-radius: 8px 0 0 8px;
}

.input-suffix > span {
  display: flex;
  align-items: center;

  padding: 0 12px;

  border: 1px solid #3f3f46;

  border-left: none;

  border-radius: 0 8px 8px 0;

  background-color: #3f3f46;

  color: #a1a1aa;

  font-size: 12px;

  font-weight: 600;
}


/* =========================================================
   IMAGE PREVIEW
========================================================= */

.image-preview {
  margin-bottom: 24px;

  padding: 18px;

  background-color: #111113;

  border: 1px dashed #3f3f46;

  border-radius: 10px;
}

.preview-header {
  margin-bottom: 12px;

  color: #a1a1aa;

  font-size: 12px;

  font-weight: 600;
}

.preview-content {
  min-height: 40px;
}

.preview-content img {
  display: block;

  width: 180px;
  height: 140px;

  object-fit: cover;

  border: 1px solid #3f3f46;

  border-radius: 8px;
}


/* =========================================================
   SUMMARY
========================================================= */

.product-summary {
  margin-bottom: 24px;

  padding: 18px;

  background-color: #111113;

  border: 1px solid #27272a;

  border-radius: 10px;
}

.summary-title {
  margin-bottom: 16px;

  color: #ffffff;

  font-size: 13px;

  font-weight: 700;
}

.summary-grid {
  display: grid;

  grid-template-columns:
    repeat(4, minmax(0, 1fr));

  gap: 12px;
}

.summary-item {
  min-width: 0;

  padding: 14px;

  background-color: #18181b;

  border: 1px solid #27272a;

  border-radius: 8px;
}

.summary-item span {
  display: block;

  margin-bottom: 6px;

  color: #71717a;

  font-size: 11px;
}

.summary-item strong {
  display: block;

  overflow: hidden;

  color: #ffffff;

  font-size: 13px;

  font-weight: 600;

  text-overflow: ellipsis;

  white-space: nowrap;
}

.summary-item .summary-price {
  color: #f97316;
}


/* =========================================================
   FORM ACTION
========================================================= */

.form-actions {
  display: flex;

  align-items: center;
  justify-content: flex-end;

  gap: 10px;

  padding-top: 24px;

  border-top: 1px solid #27272a;
}


/* =========================================================
   BUTTON BATAL
========================================================= */

.btn-cancel {
  padding: 11px 20px;

  border: 1px solid #3f3f46;

  border-radius: 8px;

  background-color: #27272a;

  color: #d4d4d8;

  font-size: 13px;

  font-weight: 600;

  cursor: pointer;

  transition:
    background-color 0.2s ease,
    color 0.2s ease;
}

.btn-cancel:hover {
  background-color: #3f3f46;
  color: #ffffff;
}


/* =========================================================
   BUTTON SIMPAN
========================================================= */

.btn-save {
  display: inline-flex;

  align-items: center;
  justify-content: center;

  gap: 8px;

  padding: 11px 22px;

  border: none;

  border-radius: 8px;

  background:
    linear-gradient(
      135deg,
      #fb923c,
      #ea580c
    );

  color: #ffffff;

  font-size: 13px;

  font-weight: 700;

  cursor: pointer;

  box-shadow:
    0 4px 12px rgba(234, 88, 12, 0.25);

  transition:
    transform 0.25s ease,
    box-shadow 0.25s ease,
    background 0.25s ease;
}

.btn-save:hover {
  background:
    linear-gradient(
      135deg,
      #f97316,
      #c2410c
    );

  transform: translateY(-2px);

  box-shadow:
    0 8px 20px rgba(234, 88, 12, 0.4);
}

.btn-save:active {
  transform: translateY(0);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1000px) {

  .sidebar {
    width: 220px;
  }

  .summary-grid {
    grid-template-columns:
      repeat(2, minmax(0, 1fr));
  }

}


@media (max-width: 800px) {

  .form-grid {
    grid-template-columns: 1fr;
  }

  .banner-content {
    align-items: flex-start;
    flex-direction: column;
  }

  .btn-back {
    width: 100%;
    justify-content: center;
  }

}


@media (max-width: 700px) {

  .sidebar {
    display: none;
  }

  .topbar {
    padding: 0 16px;
  }

  .search-box {
    width: 220px;
  }

  .content-body {
    padding: 0 16px 30px;
  }

  .dashboard-banner {
    padding: 20px;
  }

  .form-card {
    padding: 20px;
  }

  .summary-grid {
    grid-template-columns: 1fr;
  }

  .form-actions {
    flex-direction: column-reverse;
    align-items: stretch;
  }

  .btn-cancel,
  .btn-save {
    width: 100%;
    justify-content: center;
  }

}


@media (max-width: 450px) {

  .search-box {
    width: 160px;
  }

  .topbar-right {
    gap: 8px;
  }

  .dashboard-banner {
    padding: 18px;
  }

  .form-card {
    padding: 16px;
  }

  .banner-content h1 {
    font-size: 20px;
  }

}
</style>