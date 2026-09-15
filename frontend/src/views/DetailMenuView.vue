```vue
<script setup>
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

// Dark Mode
const isDarkMode = ref(localStorage.getItem('theme') === 'dark')

// Data Produk (sementara)
// Nantinya bisa diganti dengan API atau localStorage
const products = ref([
  {
    id: 1,
    name: 'Keripik Singkong Pedas',
    category: 'Pedas',
    price: 15000,
    stock: 25,
    image: 'https://via.placeholder.com/600x400',
    description:
      'Keripik singkong renyah dengan bumbu pedas khas nusantara yang menggugah selera.'
  },
  {
    id: 2,
    name: 'Jagung Bakar Gurih',
    category: 'Gurih',
    price: 12000,
    stock: 30,
    image: 'https://via.placeholder.com/600x400',
    description:
      'Camilan jagung bakar dengan rasa gurih dan aroma yang menggoda.'
  },
  {
    id: 3,
    name: 'Coklat Crispy',
    category: 'Manis',
    price: 18000,
    stock: 15,
    image: 'https://via.placeholder.com/600x400',
    description:
      'Perpaduan coklat premium dan tekstur crispy yang manis dan lezat.'
  }
])

const product = computed(() => {
  return products.value.find(
    (item) => item.id === Number(route.params.id)
  )
})

const goBack = () => {
  router.push('/')
}
</script>

<template>
  <div class="detail-wrapper" :class="{ 'dark-mode': isDarkMode }">

    <div v-if="product" class="container">

      <button class="btn-back" @click="goBack">
        ← Kembali
      </button>

      <div class="detail-card">

        <div class="image-section">
          <img :src="product.image" :alt="product.name" />
        </div>

        <div class="info-section">

          <span class="category-badge">
            {{ product.category }}
          </span>

          <h1>{{ product.name }}</h1>

          <p class="description">
            {{ product.description }}
          </p>

          <div class="info-grid">

            <div class="info-box">
              <span>Harga</span>
              <strong>
                Rp {{ product.price.toLocaleString('id-ID') }}
              </strong>
            </div>

            <div class="info-box">
              <span>Stok</span>
              <strong>
                {{ product.stock }}
              </strong>
            </div>

          </div>

          <button class="btn-buy">
            🛒 Tambah ke Keranjang
          </button>

        </div>

      </div>

    </div>

    <div v-else class="not-found">

      <div class="not-found-card">
        <h2>Produk Tidak Ditemukan</h2>
        <p>
          Produk yang Anda cari tidak tersedia.
        </p>

        <button class="btn-back" @click="goBack">
          Kembali ke Beranda
        </button>
      </div>

    </div>

  </div>
</template>

<style scoped>
.detail-wrapper {
  min-height: 100vh;
  background: #f8fafc;
  padding: 40px 20px;
}

.container {
  max-width: 1100px;
  margin: auto;
}

.btn-back {
  border: none;
  background: #2563eb;
  color: white;
  padding: 10px 18px;
  border-radius: 12px;
  cursor: pointer;
  margin-bottom: 24px;
  font-weight: 700;
}

.detail-card {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  background: white;
  border-radius: 24px;
  padding: 30px;
  box-shadow: 0 15px 30px rgba(0,0,0,0.08);
}

.image-section img {
  width: 100%;
  border-radius: 18px;
  object-fit: cover;
}

.info-section h1 {
  margin: 16px 0;
  font-size: 2rem;
  color: #0f172a;
}

.description {
  color: #64748b;
  line-height: 1.8;
}

.category-badge {
  display: inline-block;
  background: #dbeafe;
  color: #2563eb;
  padding: 8px 14px;
  border-radius: 999px;
  font-weight: 700;
}

.info-grid {
  display: flex;
  gap: 16px;
  margin-top: 24px;
}

.info-box {
  flex: 1;
  background: #f8fafc;
  border-radius: 16px;
  padding: 18px;
  border: 1px solid #e2e8f0;
}

.info-box span {
  display: block;
  color: #64748b;
  margin-bottom: 6px;
}

.info-box strong {
  font-size: 1.2rem;
  color: #0f172a;
}

.btn-buy {
  margin-top: 24px;
  width: 100%;
  border: none;
  background: linear-gradient(
    135deg,
    #2563eb,
    #3b82f6
  );
  color: white;
  padding: 14px;
  border-radius: 14px;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
}

.not-found {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
}

.not-found-card {
  background: white;
  padding: 40px;
  border-radius: 24px;
  text-align: center;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* DARK MODE */
.dark-mode {
  background: #0f172a;
}

.dark-mode .detail-card,
.dark-mode .not-found-card {
  background: #1e293b;
}

.dark-mode h1,
.dark-mode h2,
.dark-mode strong {
  color: #f8fafc !important;
}

.dark-mode .description,
.dark-mode .info-box span {
  color: #94a3b8;
}

.dark-mode .info-box {
  background: #0f172a;
  border-color: #334155;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .detail-card {
    grid-template-columns: 1fr;
  }

  .info-grid {
    flex-direction: column;
  }
}
</style>
```
