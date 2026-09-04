<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const orderItems = ref([
  { id: 101, orderId: 'ORD-001', productName: 'Keripik Kaca', qty: 2, subtotal: 'Rp 24.000' },
  { id: 102, orderId: 'ORD-001', productName: 'Basreng Pedas Jeruk', qty: 1, subtotal: 'Rp 15.000' },
  { id: 103, orderId: 'ORD-002', productName: 'Makaroni Level 5', qty: 3, subtotal: 'Rp 30.000' }
])

const handleLogout = () => {
  localStorage.clear()
  router.push('/login')
}
</script>

<template>
  <div class="admin-dashboard">
    <header class="admin-navbar">
      <div class="navbar-container">
        <div class="brand-logo" @click="router.push('/')">
          <div class="brand-mark">C</div>
          <div class="brand-info">
            <span class="brand-text">Cemilku</span>
            <small>ADMIN PANEL</small>
          </div>
        </div>

        <nav class="admin-menu">
          <router-link to="/admin/dashboard" class="menu-item">Dashboard</router-link>
          <router-link to="/admin/produk" class="menu-item">Produk</router-link>
          <router-link to="/admin/kategori" class="menu-item">Kategori</router-link>
          <router-link to="/admin/order" class="menu-item">Order</router-link>
          <router-link to="/admin/order-items" class="menu-item active">Order Items</router-link>
        </nav>

        <div class="nav-actions">
          <router-link to="/" class="btn-outline-home">Ke Home</router-link>
          <button @click="handleLogout" class="btn-logout">Keluar</button>
        </div>
      </div>
    </header>

    <main class="dashboard-main">
      <div class="container">
        <div class="dashboard-banner">
          <div>
            <span class="section-label">DETAIL ITEM</span>
            <h1>Kelola Order Items</h1>
            <p>Rincian item produk yang dipesan dalam transaksi.</p>
          </div>
        </div>

        <section class="recent-orders-section">
          <div class="table-wrapper">
            <table class="orders-table">
              <thead>
                <tr>
                  <th>ID Item</th>
                  <th>ID Order</th>
                  <th>Nama Produk</th>
                  <th>Jumlah (Qty)</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in orderItems" :key="item.id">
                  <td><strong>#{{ item.id }}</strong></td>
                  <td>{{ item.orderId }}</td>
                  <td>{{ item.productName }}</td>
                  <td>{{ item.qty }} pcs</td>
                  <td><span class="price-tag">{{ item.subtotal }}</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </main>

    <footer class="admin-footer">
      <div class="container">© 2026 Cemilku Snack Store — Admin Management</div>
    </footer>
  </div>
</template>

<style scoped>
.admin-dashboard { min-height: 100vh; display: flex; flex-direction: column; background-color: #fafafa; font-family: 'Inter', system-ui, -apple-system, sans-serif; color: #1e293b; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.admin-navbar { background: #ffffff; border-bottom: 1px solid #f1f5f9; padding: 14px 0; }
.navbar-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; display: flex; justify-content: space-between; align-items: center; }
.brand-logo { display: flex; align-items: center; gap: 12px; cursor: pointer; }
.brand-mark { background: #ea580c; color: white; font-weight: 800; font-size: 20px; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.brand-info { display: flex; flex-direction: column; }
.brand-text { font-weight: 700; font-size: 18px; color: #0f172a; line-height: 1.1; }
.brand-info small { font-size: 10px; letter-spacing: 0.5px; color: #ea580c; font-weight: 700; }
.admin-menu { display: flex; gap: 8px; }
.menu-item { text-decoration: none; color: #64748b; font-weight: 600; font-size: 14px; padding: 8px 14px; border-radius: 8px; transition: all 0.2s; }
.menu-item:hover, .menu-item.active { color: #ea580c; background-color: #fff7ed; }
.nav-actions { display: flex; align-items: center; gap: 12px; }
.btn-outline-home { text-decoration: none; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #334155; }
.btn-logout { background: #ef4444; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; }
.dashboard-main { flex: 1; padding: 36px 0; }
.dashboard-banner { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; text-align: left; }
.section-label { color: #ea580c; font-size: 12px; font-weight: 700; letter-spacing: 1px; }
.dashboard-banner h1 { font-size: 32px; font-weight: 800; color: #0f172a; margin: 6px 0; }
.dashboard-banner p { color: #64748b; font-size: 14px; margin: 0; }
.recent-orders-section { background: #ffffff; border: 1px solid #f1f5f9; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02); text-align: left; }
.table-wrapper { overflow-x: auto; }
.orders-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.orders-table th { background: #f8fafc; padding: 12px 16px; color: #475569; font-weight: 600; text-align: left; border-bottom: 1px solid #e2e8f0; }
.orders-table td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; color: #334155; }
.price-tag { font-weight: 700; color: #ea580c; }
.admin-footer { text-align: center; padding: 20px 0; font-size: 13px; color: #94a3b8; border-top: 1px solid #f1f5f9; background: #ffffff; }
</style>