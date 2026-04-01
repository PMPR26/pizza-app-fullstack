<script setup lang="ts">
import { useShopStore } from '@/stores/shop'

const shop = useShopStore()

const emit = defineEmits<{
  checkout: []
}>()
</script>

<template>
  <aside class="h-fit rounded-3xl border border-gray-200 bg-white p-5 shadow-[0_4px_6px_-1px_rgb(0_0_0/0.08)]">
    <h2 class="mb-4 text-lg font-bold text-gray-900">Tu carrito</h2>
    <div v-if="shop.cart.length" class="space-y-3">
      <div
        v-for="item in shop.cart"
        :key="item.pizza.id"
        class="rounded-xl border border-gray-100 bg-gray-50/80 p-3"
      >
        <div class="mb-2 flex items-start justify-between gap-2">
          <p class="text-sm font-semibold leading-tight text-gray-900">{{ item.pizza.name }}</p>
          <button
            type="button"
            class="shrink-0 text-xs font-medium text-[#D32F2F] hover:underline"
            @click="shop.removeFromCart(item.pizza.id)"
          >
            Quitar
          </button>
        </div>
        <div class="flex items-center justify-between gap-2">
          <div class="inline-flex items-center rounded-xl border border-gray-200 bg-white p-0.5 shadow-sm">
            <button
              type="button"
              class="grid h-9 w-9 place-items-center rounded-lg text-lg font-semibold text-gray-700 transition hover:bg-gray-100"
              aria-label="Menos"
              @click="shop.decrementQuantity(item.pizza.id)"
            >
              −
            </button>
            <span class="min-w-[2rem] text-center text-sm font-bold tabular-nums text-gray-900">
              {{ item.quantity }}
            </span>
            <button
              type="button"
              class="grid h-9 w-9 place-items-center rounded-lg text-lg font-semibold text-gray-700 transition hover:bg-gray-100"
              aria-label="Más"
              @click="shop.incrementQuantity(item.pizza.id)"
            >
              +
            </button>
          </div>
          <p class="text-sm font-semibold text-[#D32F2F]">
            S/ {{ (Number(item.pizza.price) * item.quantity).toFixed(2) }}
          </p>
        </div>
      </div>
      <div class="border-t border-gray-200 pt-4 text-sm text-gray-800">
        Total:
        <span class="font-bold text-[#D32F2F]">S/ {{ shop.cartTotal.toFixed(2) }}</span>
      </div>
      <button
        type="button"
        class="w-full rounded-xl bg-[#D32F2F] px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#B71C1C]"
        @click="emit('checkout')"
      >
        Confirmar pedido
      </button>
    </div>
    <p v-else class="text-sm text-gray-500">Aún no agregaste pizzas.</p>
  </aside>
</template>
