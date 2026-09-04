
<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const registerName = ref('')
const registerEmail = ref('')
const registerPassword = ref('')
const errorMessage = ref('')

const handleRegister = () => {
  errorMessage.value = ''

  if (
    !registerName.value ||
    !registerEmail.value ||
    !registerPassword.value
  ) {
    errorMessage.value = 'Semua data pendaftaran wajib diisi.'
    return
  }

  localStorage.setItem('isLoggedIn', 'true')
  localStorage.setItem('username', registerName.value)
  localStorage.setItem('user', registerName.value)
  localStorage.setItem('role', 'user')

  router.push('/')
}
</script>

<template>
  <div class="auth-page">

    <header class="auth-navbar">
      <div class="auth-nav-container">

        <div class="brand-logo" @click="router.push('/')">

          <div class="brand-mark">
            C
          </div>

          <div class="brand-info">
            <span class="brand-text">
              Cemilku
            </span>
            <small>Snack Store</small>
          </div>

        </div>

        <button
          class="btn-outline"
          @click="router.push('/')"
        >
          Kembali
        </button>

      </div>
    </header>


    <main class="auth-main">

      <section class="auth-layout">

        <div class="auth-intro">

          <span class="section-label">
            BERGABUNG BERSAMA KAMI
          </span>

          <h1>
            Buat akun dan mulai
            berbelanja dengan mudah.
          </h1>

          <p>
            Cemilku menyediakan pengalaman pemesanan
            camilan yang sederhana dengan tampilan modern
            dan proses checkout yang praktis.
          </p>

          <div class="auth-benefit">

            <div class="benefit-item">
              <div class="benefit-icon">01</div>
              <div>
                <strong>Registrasi cepat</strong>
                <span>Pendaftaran hanya membutuhkan beberapa data.</span>
              </div>
            </div>

            <div class="benefit-item">
              <div class="benefit-icon">02</div>
              <div>
                <strong>Pilih produk favorit</strong>
                <span>Akses seluruh katalog Cemilku.</span>
              </div>
            </div>

            <div class="benefit-item">
              <div class="benefit-icon">03</div>
              <div>
                <strong>Pesan kapan saja</strong>
                <span>Checkout mudah melalui WhatsApp.</span>
              </div>
            </div>

          </div>

        </div>


        <div class="auth-card">

          <div class="auth-card-header">

            <span class="section-label">
              REGISTER
            </span>

            <h2>
              Buat Akun
            </h2>

            <p>
              Lengkapi data berikut untuk membuat akun baru.
            </p>

          </div>


          <form @submit.prevent="handleRegister">

            <div class="auth-form-group">

              <label>
                Nama Lengkap
              </label>

              <input
                v-model="registerName"
                type="text"
                placeholder="Masukkan nama lengkap"
              />

            </div>


            <div class="auth-form-group">

              <label>
                Email
              </label>

              <input
                v-model="registerEmail"
                type="email"
                placeholder="nama@email.com"
              />

            </div>


            <div class="auth-form-group">

              <label>
                Password
              </label>

              <input
                v-model="registerPassword"
                type="password"
                placeholder="Buat password"
              />

            </div>


            <div
              v-if="errorMessage"
              class="auth-error"
            >
              {{ errorMessage }}
            </div>


            <button
              type="submit"
              class="auth-submit"
            >
              Buat Akun
            </button>

          </form>


          <div class="auth-divider">
            <span></span>
            <p>atau</p>
            <span></span>
          </div>


          <p class="auth-switch">
            Sudah memiliki akun?
            <router-link to="/login">
              Masuk di sini
            </router-link>
          </p>

        </div>

      </section>

    </main>


    <footer class="auth-footer">
      <div class="container">
        © 2026 Cemilku Snack Store
      </div>
    </footer>

  </div>
</template>