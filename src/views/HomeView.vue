<template>
  <section class="mx-auto grid max-w-6xl gap-10 px-4 py-14 md:grid-cols-2 md:items-center">
    <div class="space-y-5">
      <p
        class="inline-flex rounded-full border border-[#FEF9C3] bg-[#FEF9C3]/80 px-3 py-1 text-xs font-medium text-[#713F12]"
      >
        Pizza artesanal en minutos
      </p>
      <h1 class="text-4xl font-bold leading-tight tracking-tight text-gray-900 md:text-5xl">
        Pide tus pizzas favoritas con una experiencia moderna y rápida.
      </h1>
      <p class="text-lg text-gray-600">
        Explora el menú, agrega al carrito y gestiona tus pedidos desde una interfaz limpia y responsive.
      </p>
      <div class="flex flex-wrap gap-3">
        <router-link
          to="/menu"
          class="rounded-xl bg-[#D32F2F] px-5 py-3 font-semibold text-white shadow-sm hover:bg-[#B71C1C]"
        >
          Ver menú
        </router-link>
        <router-link
          to="/register"
          class="rounded-xl border-2 border-gray-300 bg-white px-5 py-3 font-semibold text-gray-800 hover:bg-gray-50"
        >
          Crear cuenta
        </router-link>
      </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white p-4 shadow-lg shadow-gray-200/80">
      <img
        class="h-80 w-full rounded-2xl object-cover"
        src="https://images.unsplash.com/photo-1541745537411-b8046dc6d66c?q=80&w=1200&auto=format&fit=crop"
        alt="Pizza"
      />
    </div>
  </section>
</template>
