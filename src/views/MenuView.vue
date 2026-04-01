<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import PizzaCard from '@/components/PizzaCard.vue'
import CartPanel from '@/components/CartPanel.vue'
import { useShopStore } from '@/stores/shop'
import { useAuthStore } from '@/stores/auth'
import type { Pizza } from '@/types'

const shop = useShopStore()
const auth = useAuthStore()
const router = useRouter()
const message = ref('')
const error = ref('')

onMounted(() => {
  if (!shop.pizzas.length) {
    shop.fetchPizzas()
  }
})

function addToCart(pizza: Pizza) {
  shop.addToCart(pizza)
  message.value = `${pizza.name} agregada al carrito`
  setTimeout(() => {
    message.value = ''
  }, 1800)
}

async function checkout() {
  error.value = ''
  if (!auth.isAuthenticated) {
    router.push('/login')
    return
  }
  try {
    await shop.placeOrder()
    message.value = 'Pedido creado correctamente'
  } catch (err: any) {
    error.value = err?.response?.data?.message ?? 'No se pudo procesar el pedido'
  }
}
</script>

<template>
  <section class="mx-auto grid max-w-6xl gap-8 px-4 py-10 lg:grid-cols-[1fr_340px]">
    <div>
      <h1 class="mb-2 text-3xl font-bold tracking-tight text-gray-900 md:text-4xl">Nuestro menú</h1>
      <p class="mb-6 text-gray-600">Elige tu pizza favorita y agrégala al carrito.</p>

      <p
        v-if="message"
        class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
      >
        {{ message }}
      </p>
      <p
        v-if="error"
        class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
      >
        {{ error }}
      </p>

      <div v-if="shop.loading" class="text-gray-600">Cargando pizzas...</div>
      <div v-else class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <PizzaCard v-for="pizza in shop.pizzas" :key="pizza.id" :pizza="pizza" @add="addToCart" />
      </div>
    </div>

    <CartPanel @checkout="checkout" />
  </section>
</template>
