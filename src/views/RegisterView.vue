<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const router = useRouter()

const name = ref('')
const email = ref('')
const password = ref('')
const error = ref('')

async function onSubmit() {
  error.value = ''
  try {
    await auth.register(name.value, email.value, password.value)
    router.push('/menu')
  } catch (err: any) {
    error.value = err?.response?.data?.message ?? 'No se pudo completar el registro'
  }
}
</script>

<template>
  <section class="mx-auto max-w-md px-4 py-12">
    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-[0_4px_6px_-1px_rgb(0_0_0/0.08)]">
      <h1 class="mb-1 text-2xl font-bold text-gray-900">Crear cuenta</h1>
      <p class="mb-6 text-sm text-gray-600">Regístrate para comenzar a pedir.</p>

      <form class="space-y-4" @submit.prevent="onSubmit">
        <input
          v-model="name"
          type="text"
          placeholder="Nombre"
          class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none ring-[#D32F2F] focus:ring-2"
        />
        <input
          v-model="email"
          type="email"
          placeholder="Correo"
          class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none ring-[#D32F2F] focus:ring-2"
        />
        <input
          v-model="password"
          type="password"
          placeholder="Contraseña"
          class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none ring-[#D32F2F] focus:ring-2"
        />
        <button
          :disabled="auth.loading"
          class="w-full rounded-xl bg-[#D32F2F] px-4 py-3 font-semibold text-white shadow-sm hover:bg-[#B71C1C] disabled:opacity-50"
        >
          {{ auth.loading ? 'Creando...' : 'Crear cuenta' }}
        </button>
      </form>

      <p v-if="error" class="mt-4 text-sm text-red-600">{{ error }}</p>
    </div>
  </section>
</template>
