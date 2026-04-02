<script setup lang="ts">
import { onMounted } from 'vue'
import AppHeader from '@/components/AppHeader.vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()

onMounted(() => {
  auth.fetchMe()
})
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <AppHeader />
    <main>
      <router-view />
    </main>
  </div>
</template>
