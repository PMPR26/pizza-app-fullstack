<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useShopStore } from '@/stores/shop'

const shop = useShopStore()
const { orders } = storeToRefs(shop)
</script>

<template>
  <section class="mx-auto max-w-5xl px-4 py-10">
    <h1 class="mb-4 text-2xl font-bold text-gray-900">Mis pedidos</h1>
    <div v-if="orders.length" class="space-y-3">
      <article
        v-for="order in orders"
        :key="order.id"
        class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm"
      >
        <p class="font-semibold text-gray-900">Pedido #{{ order.id }}</p>
        <p class="text-sm text-gray-600">Pizza: {{ order.pizza?.name ?? order.pizza_id }}</p>
        <p class="text-sm text-gray-600">Cantidad: {{ order.quantity }}</p>
        <p class="text-sm font-semibold text-[#D32F2F]">Total: S/ {{ Number(order.total).toFixed(2) }}</p>
      </article>
    </div>
    <p v-else class="text-gray-500">Aún no tienes pedidos en esta sesión.</p>
  </section>
</template>
