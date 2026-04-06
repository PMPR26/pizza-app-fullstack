import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import HomeView from '@/views/HomeView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import MenuView from '@/views/MenuView.vue'
import MyOrdersView from '@/views/MyOrdersView.vue'
import AdminView from '@/views/AdminView.vue'

const router = createRouter({
  history: createWebHistory('/pizza-app-fullstack/'),
  routes: [
    { path: '/', name: 'home', component: HomeView },
    { path: '/login', name: 'login', component: LoginView, meta: { guest: true } },
    { path: '/register', name: 'register', component: RegisterView, meta: { guest: true } },
    { path: '/menu', name: 'menu', component: MenuView },
    { path: '/my-orders', name: 'my-orders', component: MyOrdersView, meta: { auth: true } },
    { path: '/admin', name: 'admin', component: AdminView, meta: { auth: true, admin: true } },
  ],
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()
  if (!auth.user && auth.token) {
    await auth.fetchMe()
  }

  if (to.meta.auth && !auth.isAuthenticated) {
    return { name: 'login' }
  }

  if (to.meta.guest && auth.isAuthenticated) {
    return { name: 'menu' }
  }

  if (to.meta.admin && !auth.isAdmin) {
    return { name: 'menu' }
  }

  return true
})

export default router
