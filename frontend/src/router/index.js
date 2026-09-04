import { createRouter, createWebHistory } from 'vue-router'

import HomeView from '../views/HomeView.vue'
import DetailMenuView from '../views/DetailMenuView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import TentangkamiView from '../views/TentangkamiView.vue'
import KontakView from '../views/KontakView.vue'

// Import View Admin dari folder admin/
import DashboardAdminView from '../views/admin/DashboardAdminView.vue'
import ProdukAdminView from '../views/admin/ProdukAdminView.vue'
import KategoriAdminView from '../views/admin/KategoriAdminView.vue'
import OrderAdminView from '../views/admin/OrderAdminView.vue'
import OrderItemsAdminView from '../views/admin/OrderItemsAdminView.vue'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: LoginView
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView
  },
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/detail/:id?',
    name: 'detail-menu',
    component: DetailMenuView
  },
  {
    path: '/tentang-kami',
    name: 'tentang-kami',
    component: TentangkamiView
  },
  {
    path: '/kontak',
    name: 'kontak',
    component: KontakView
  },

  // Rute Grup Admin (Perlu Auth & Role Admin)
  {
    path: '/admin',
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      {
        path: '', // Mengakses /admin langsung membuka Dashboard
        name: 'admin-dashboard',
        component: DashboardAdminView
      },
      {
        path: 'dashboard', // Alternatif alias jika /admin/dashboard tetap diakses
        redirect: { name: 'admin-dashboard' }
      },
      {
        path: 'produk',
        name: 'admin-produk',
        component: ProdukAdminView
      },
      {
        path: 'kategori',
        name: 'admin-kategori',
        component: KategoriAdminView
      },
      {
        path: 'order',
        name: 'admin-order',
        component: OrderAdminView
      },
      {
        path: 'order-items',
        name: 'admin-order-items',
        component: OrderItemsAdminView
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation Guard
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('isLoggedIn') === 'true'
  const isAdmin = localStorage.getItem('userRole') === 'admin'

  // Periksa apakah rute tujuan (atau parent-nya) membutuhkan Auth/Admin
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin)

  // 1. Membutuhkan Auth tapi belum login
  if (requiresAuth && !isAuthenticated) {
    return next({ name: 'login' })
  }

  // 2. Membutuhkan hak akses Admin tapi bukan admin
  if (requiresAdmin && !isAdmin) {
    return next({ name: 'home' })
  }

  // 3. Sudah login tapi mencoba ke halaman login/register
  if ((to.name === 'login' || to.name === 'register') && isAuthenticated) {
    return next({ name: 'home' })
  }

  next()
})

export default router