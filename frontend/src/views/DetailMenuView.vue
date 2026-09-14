<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const snack = ref(null)

// Data produk
const snacksData = [
  {
    id: 1,
    name: 'Basreng Pedas Jeruk',
    category: 'Basreng',
    tag: 'Best Seller 🔥',
    tagClass: 'tag-rose',
    spicyLevel: '🌶️🌶️🌶️',
    price: 15000,
    desc: 'Baso goreng renyah disiram bumbu cabai asli dan racikan daun jeruk segar khas Bandung.'
  },
  {
    id: 2,
    name: 'Keripik Singkong Balado',
    category: 'Keripik',
    tag: 'Favorit 🧡',
    tagClass: 'tag-amber',
    spicyLevel: '🌶️🌶️',
    price: 12000,
    desc: 'Irisan singkong super tipis bertabur bumbu balado manis pedas gurih renyah.'
  },
  {
    id: 3,
    name: 'Makaroni Pedas Bawang',
    category: 'Makaroni',
    tag: 'Kriuk Banget ✨',
    tagClass: 'tag-emerald',
    spicyLevel: '🌶️🌶️🌶️',
    price: 10000,
    desc: 'Makaroni renyah kriuk dengan balutan cabai kering dan aroma bawang gurih memikat.'
  },
  {
    id: 4,
    name: 'Seblak Kering Bantat',
    category: 'Keripik',
    tag: 'Super Pedas 🌶️',
    tagClass: 'tag-red',
    spicyLevel: '🌶️🌶️🌶️🌶️',
    price: 12000,
    desc: 'Kerupuk seblak pedas rempah kencur pilihan, cocok buat kamu pencinta tantangan pedas.'
  },
  {
    id: 5,
    name: 'Keripik Tempe Rempah',
    category: 'Keripik',
    tag: 'Gurih Alami 🌿',
    tagClass: 'tag-teal',
    spicyLevel: '🌶️',
    price: 14000,
    desc: 'Tempe pilihan digoreng renyah dengan adonan tepung bumbu rempah tradisional.'
  },
  {
    id: 6,
    name: 'Kue Kacang Lumer',
    category: 'Kue',
    tag: 'Manis Legit 🍪',
    tagClass: 'tag-orange',
    spicyLevel: 'Manis Gurih',
    price: 18000,
    desc: 'Kue kacang renyah manis yang meleleh lembut di mulut begitu digigit.'
  }
]

onMounted(() => {
  const snackId = parseInt(route.params.id) || 1

  snack.value =
    snacksData.find(item => item.id === snackId) ||
    snacksData[0]
})

const goBack = () => {
  router.push('/')
}

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(val)
}
</script>


<template>
  <div class="detail-page-wrapper">

    <!-- =========================================
         NAVBAR
    ========================================== -->
    <header class="detail-navbar">

      <div class="detail-container detail-nav-content">

        <button
          @click="goBack"
          class="detail-back-button"
        >
          <span class="detail-back-icon">←</span>
          <span>Kembali ke Menu</span>
        </button>


        <div
          class="detail-brand"
          @click="router.push('/')"
        >
          <span class="detail-brand-icon">🍿</span>

          <span class="detail-brand-name">
            Cemilku
          </span>
        </div>

      </div>

    </header>


    <!-- =========================================
         DETAIL CONTENT
    ========================================== -->
    <main
      class="detail-container detail-content-section"
      v-if="snack"
    >

      <article class="detail-card-large">

        <!-- HEADER -->
        <div class="detail-header">

          <span
            :class="[
              'detail-tag-badge',
              snack.tagClass
            ]"
          >
            {{ snack.tag }}
          </span>


          <div class="detail-spicy-box">

            <span class="detail-spicy-label">
              Tingkat Pedas
            </span>

            <span class="detail-spicy-value">
              {{ snack.spicyLevel }}
            </span>

          </div>

        </div>


        <!-- TITLE -->
        <h1 class="detail-title">
          {{ snack.name }}
        </h1>


        <!-- CATEGORY -->
        <p class="detail-category">
          Kategori
          <span class="detail-category-separator">•</span>
          <strong>{{ snack.category }}</strong>
        </p>


        <!-- DIVIDER -->
        <div class="detail-divider"></div>


        <!-- DESCRIPTION -->
        <section class="detail-description-section">

          <p class="detail-section-label">
            DESKRIPSI PRODUK
          </p>

          <h2 class="detail-description-title">
            Tentang Produk
          </h2>

          <p class="detail-desc">
            {{ snack.desc }}
          </p>

        </section>


        <!-- INFO -->
        <div class="detail-info-grid">

          <div class="detail-info-item">

            <span class="detail-info-label">
              KATEGORI
            </span>

            <span class="detail-info-value">
              {{ snack.category }}
            </span>

          </div>


          <div class="detail-info-item">

            <span class="detail-info-label">
              LEVEL
            </span>

            <span class="detail-info-value">
              {{ snack.spicyLevel }}
            </span>

          </div>


          <div class="detail-info-item">

            <span class="detail-info-label">
              STATUS
            </span>

            <span class="detail-status">
              Tersedia
            </span>

          </div>

        </div>


        <!-- FOOTER -->
        <div class="detail-footer">

          <div class="detail-price-container">

            <span class="detail-price-label">
              Harga Satuan
            </span>

            <span class="detail-price">
              {{ formatRupiah(snack.price) }}
            </span>

          </div>


          <button
            @click="goBack"
            class="detail-primary-button"
          >
            <span>Kembali & Pesan di Beranda</span>
            <span class="detail-button-arrow">→</span>
          </button>

        </div>

      </article>

    </main>

  </div>
</template>