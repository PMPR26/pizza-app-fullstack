<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useShopStore } from '@/stores/shop'

const auth = useAuthStore()
const shop = useShopStore()
const router = useRouter()

const initials = computed(() => {
  if (!auth.user?.name) return 'U'
  return auth.user.name
    .split(' ')
    .map((part) => part[0])
    .join('')
    .slice(0, 2)
    .toUpperCase()
})

async function handleLogout() {
  await auth.logout()
  router.push('/login')
}
</script>

<template>
  <header class="sticky top-0 z-40 border-b border-gray-200 bg-white/95 shadow-sm backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
      <router-link to="/" class="text-lg font-bold text-[#D32F2F]">Pizza App</router-link>

      <nav class="flex items-center gap-4 text-sm font-medium text-gray-700">
        <router-link to="/menu" class="hover:text-[#D32F2F]">Menú</router-link>
        <router-link v-if="auth.isAuthenticated" to="/my-orders" class="hover:text-[#D32F2F]">
          Mis pedidos
        </router-link>
        <router-link v-if="auth.isAdmin" to="/admin" class="hover:text-[#D32F2F]">Admin</router-link>
      </nav>

      <div class="flex items-center gap-3">
        <span
          v-if="shop.cartCount"
          class="rounded-full bg-[#FEF9C3] px-2.5 py-1 text-xs font-bold text-[#713F12]"
        >
          {{ shop.cartCount }}
        </span>

        <template v-if="auth.isAuthenticated">
          <span
            class="grid h-9 w-9 place-content-center rounded-full bg-gray-200 text-xs font-bold text-gray-800"
          >
            {{ initials }}
          </span>
          <button
            class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-50"
            @click="handleLogout"
          >
            Salir
          </button>
        </template>

        <template v-else>
          <router-link
            to="/login"
            class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50"
          >
            Login
          </router-link>
          <router-link
            to="/register"
            class="rounded-lg bg-[#D32F2F] px-3 py-2 text-xs font-semibold text-white hover:bg-[#B71C1C]"
          >
            Crear cuenta
          </router-link>
        </template>
      </div>
    </div>
  </header>
</template>
