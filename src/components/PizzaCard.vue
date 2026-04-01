<script setup lang="ts">
import { computed } from 'vue'
import type { Pizza } from '@/types'

const props = defineProps<{
  pizza: Pizza
}>()

const emit = defineEmits<{
  add: [pizza: Pizza]
}>()

/** Badge tipo referencia: primer ingrediente o etiqueta corta */
const badgeText = computed(() => {
  const first = props.pizza.ingredients?.[0]?.name?.trim()
  if (first) return first.length > 22 ? `${first.slice(0, 22)}…` : first
  return 'Especial'
})

const ingredientNames = computed(() => props.pizza.ingredients?.map((i) => i.name) ?? [])
</script>

<template>
  <article
    class="flex flex-col overflow-hidden rounded-3xl bg-white shadow-[0_4px_6px_-1px_rgb(0_0_0/0.1),0_2px_4px_-2px_rgb(0_0_0/0.1)] transition hover:shadow-lg hover:shadow-black/10"
  >
    <div class="aspect-[4/3] w-full shrink-0 overflow-hidden bg-gray-100">
      <img
        :src="pizza.image"
        :alt="pizza.name"
        class="h-full w-full object-cover"
        loading="lazy"
      />
    </div>

    <div class="flex flex-1 flex-col p-6">
      <div class="mb-3 flex items-start justify-between gap-3">
        <h3 class="max-w-[65%] text-xl font-bold leading-tight tracking-tight text-[#D32F2F]">
          {{ pizza.name }}
        </h3>
        <span
          class="shrink-0 rounded-lg bg-[#FEF9C3] px-2.5 py-1 text-center text-[11px] font-semibold leading-tight text-[#713F12]"
        >
          {{ badgeText }}
        </span>
      </div>

      <p class="mb-4 text-sm leading-relaxed text-gray-600">
        {{ pizza.description }}
      </p>

      <div v-if="ingredientNames.length" class="mb-5 flex-1">
        <p class="mb-2 text-sm font-semibold text-gray-800">Ingredientes principales:</p>
        <ul class="list-inside list-disc space-y-1 text-sm text-gray-600 marker:text-gray-800">
          <li v-for="name in ingredientNames" :key="name">{{ name }}</li>
        </ul>
      </div>
      <p v-else class="mb-5 text-sm italic text-gray-400">Sin ingredientes listados.</p>

      <div class="mt-auto flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-4">
        <p class="text-lg font-bold text-gray-900">
          S/ {{ Number(pizza.price).toFixed(2) }}
        </p>
        <button
          type="button"
          class="rounded-xl bg-[#D32F2F] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#B71C1C] focus:outline-none focus:ring-2 focus:ring-[#D32F2F]/40"
          @click="emit('add', pizza)"
        >
          Agregar al carrito
        </button>
      </div>
    </div>
  </article>
</template>
