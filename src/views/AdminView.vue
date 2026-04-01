<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { api } from '@/services/api'
import type { Ingredient, Order, Pizza } from '@/types'

const ingredients = ref<Ingredient[]>([])
const pizzas = ref<Pizza[]>([])
const orders = ref<Order[]>([])
const loading = ref(false)
const error = ref('')
const success = ref('')

const ingredientForm = ref({ name: '' })
const editingIngredientId = ref<number | null>(null)

const pizzaForm = ref({
  name: '',
  description: '',
  price: '',
  image_url: '',
  ingredientIds: [] as number[],
})
const pizzaImage = ref<File | null>(null)
const pizzaImagePreview = ref<string | null>(null)
const imageFileInputRef = ref<HTMLInputElement | null>(null)
const editingPizzaId = ref<number | null>(null)

const pizzaFormTitle = computed(() =>
  editingPizzaId.value ? 'Editar pizza' : 'Añadir nueva pizza',
)

function notifySuccess(message: string) {
  success.value = message
  setTimeout(() => {
    success.value = ''
  }, 2400)
}

function normalizeError(err: any, fallback: string) {
  return err?.response?.data?.message ?? fallback
}

async function fetchDashboard() {
  loading.value = true
  error.value = ''
  try {
    const [ingredientsResponse, pizzasResponse, ordersResponse] = await Promise.all([
      api.get<Ingredient[]>('/ingredients'),
      api.get<Pizza[]>('/pizzas'),
      api.get<Order[]>('/orders'),
    ])
    ingredients.value = ingredientsResponse.data
    pizzas.value = pizzasResponse.data
    orders.value = ordersResponse.data
  } catch (err: any) {
    error.value = normalizeError(err, 'No se pudo cargar el panel admin')
  } finally {
    loading.value = false
  }
}

async function submitIngredient() {
  try {
    if (editingIngredientId.value) {
      await api.put(`/ingredients/${editingIngredientId.value}`, ingredientForm.value)
      notifySuccess('Ingrediente actualizado')
    } else {
      await api.post('/ingredients', ingredientForm.value)
      notifySuccess('Ingrediente creado')
    }
    ingredientForm.value = { name: '' }
    editingIngredientId.value = null
    await fetchDashboard()
  } catch (err: any) {
    error.value = normalizeError(err, 'No se pudo guardar el ingrediente')
  }
}

function editIngredient(ingredient: Ingredient) {
  editingIngredientId.value = ingredient.id
  ingredientForm.value = { name: ingredient.name }
}

async function deleteIngredient(id: number) {
  try {
    await api.delete(`/ingredients/${id}`)
    notifySuccess('Ingrediente eliminado')
    await fetchDashboard()
  } catch (err: any) {
    error.value = normalizeError(err, 'No se pudo eliminar el ingrediente')
  }
}

function onSelectImage(event: Event) {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0] ?? null
  pizzaImage.value = file
  if (pizzaImagePreview.value) {
    URL.revokeObjectURL(pizzaImagePreview.value)
    pizzaImagePreview.value = null
  }
  if (file) {
    pizzaImagePreview.value = URL.createObjectURL(file)
    pizzaForm.value.image_url = ''
  }
}

function clearImageFile() {
  pizzaImage.value = null
  if (pizzaImagePreview.value) {
    URL.revokeObjectURL(pizzaImagePreview.value)
    pizzaImagePreview.value = null
  }
  if (imageFileInputRef.value) {
    imageFileInputRef.value.value = ''
  }
}

function onImageUrlInput() {
  if (pizzaForm.value.image_url.trim()) {
    clearImageFile()
  }
}

function resetPizzaForm() {
  pizzaForm.value = {
    name: '',
    description: '',
    price: '',
    image_url: '',
    ingredientIds: [],
  }
  clearImageFile()
  editingPizzaId.value = null
}

function editPizza(pizza: Pizza) {
  editingPizzaId.value = pizza.id
  pizzaForm.value = {
    name: pizza.name,
    description: pizza.description,
    price: String(pizza.price),
    image_url: pizza.image,
    ingredientIds: (pizza.ingredients ?? []).map((ingredient) => ingredient.id),
  }
  clearImageFile()
  const el = document.getElementById('pizza-form')
  el?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

async function submitPizza() {
  const payload = new FormData()
  payload.append('name', pizzaForm.value.name)
  payload.append('description', pizzaForm.value.description)
  payload.append('price', pizzaForm.value.price)

  if (pizzaImage.value) {
    payload.append('image', pizzaImage.value)
  } else if (pizzaForm.value.image_url?.trim()) {
    payload.append('image_url', pizzaForm.value.image_url.trim())
  }

  pizzaForm.value.ingredientIds.forEach((id) => payload.append('ingredients[]', String(id)))

  try {
    if (editingPizzaId.value) {
      payload.append('_method', 'PUT')
      await api.post(`/pizzas/${editingPizzaId.value}`, payload)
      notifySuccess('Pizza actualizada')
    } else {
      await api.post('/pizzas', payload)
      notifySuccess('Pizza creada')
    }
    resetPizzaForm()
    await fetchDashboard()
    document.getElementById('pizzas-lista')?.scrollIntoView({ behavior: 'smooth', block: 'start' })
  } catch (err: any) {
    error.value = normalizeError(err, 'No se pudo guardar la pizza')
  }
}

async function deletePizza(id: number) {
  try {
    await api.delete(`/pizzas/${id}`)
    notifySuccess('Pizza eliminada')
    await fetchDashboard()
  } catch (err: any) {
    error.value = normalizeError(err, 'No se pudo eliminar la pizza')
  }
}

onMounted(fetchDashboard)
</script>

<template>
  <div class="pb-16">
    <section class="mx-auto max-w-7xl space-y-6 px-4 py-8">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Panel de administración</h1>
          <p class="mt-1 text-sm text-gray-600">
            Gestiona ingredientes, revisa el catálogo de pizzas y los pedidos. Usa el menú de abajo para saltar a cada
            sección.
          </p>
        </div>
      </div>

      <p v-if="error" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ error }}
      </p>
      <p v-if="success" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ success }}
      </p>

      <p v-if="loading" class="text-gray-600">Cargando datos...</p>

      <template v-else>
        <!-- Resumen rápido -->
        <div class="grid gap-3 sm:grid-cols-3">
          <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Pizzas en catálogo</p>
            <p class="mt-1 text-2xl font-bold text-[#D32F2F]">{{ pizzas.length }}</p>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Ingredientes</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ ingredients.length }}</p>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Pedidos totales</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ orders.length }}</p>
          </div>
        </div>

        <!-- Navegación por secciones -->
        <nav
          class="sticky top-[52px] z-30 flex flex-wrap items-center gap-2 rounded-2xl border border-gray-200 bg-white/95 px-3 py-2 shadow-sm backdrop-blur sm:gap-3"
          aria-label="Secciones del panel"
        >
          <span class="hidden text-xs font-medium text-gray-400 sm:inline">Ir a:</span>
          <a
            href="#ingredientes"
            class="rounded-lg bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-800 hover:bg-gray-200"
            >Ingredientes</a
          >
          <a
            href="#pizzas-lista"
            class="rounded-lg bg-[#FEF9C3] px-3 py-1.5 text-sm font-semibold text-[#713F12] ring-1 ring-amber-200/80 hover:bg-amber-100"
            >Lista de pizzas</a
          >
          <a href="#pizza-form" class="rounded-lg bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-800 hover:bg-gray-200"
            >Formulario pizza</a
          >
          <a href="#pedidos" class="rounded-lg bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-800 hover:bg-gray-200"
            >Pedidos</a
          >
        </nav>

        <div class="grid gap-8 xl:grid-cols-3">
          <!-- Columna ingredientes -->
          <article
            id="ingredientes"
            class="scroll-mt-36 space-y-4 rounded-3xl border border-gray-200 bg-white p-5 shadow-sm xl:col-span-1"
          >
            <h2 class="text-lg font-bold text-gray-900">Ingredientes</h2>
            <p class="text-sm text-gray-600">Crea y edita ingredientes para usarlos en las pizzas.</p>
            <form class="space-y-3" @submit.prevent="submitIngredient">
              <input
                v-model="ingredientForm.name"
                type="text"
                required
                placeholder="Nombre del ingrediente"
                class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none ring-[#D32F2F] focus:ring-2"
              />
              <div class="flex gap-2">
                <button
                  type="submit"
                  class="rounded-xl bg-[#D32F2F] px-4 py-2 text-sm font-semibold text-white hover:bg-[#B71C1C]"
                >
                  {{ editingIngredientId ? 'Actualizar' : 'Crear' }}
                </button>
                <button
                  v-if="editingIngredientId"
                  type="button"
                  class="rounded-xl border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                  @click="
                    editingIngredientId = null;
                    ingredientForm = { name: '' };
                  "
                >
                  Cancelar
                </button>
              </div>
            </form>

            <div class="space-y-2">
              <div
                v-for="ingredient in ingredients"
                :key="ingredient.id"
                class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 px-3 py-2 text-sm text-gray-800"
              >
                <span>{{ ingredient.name }}</span>
                <div class="flex gap-2">
                  <button
                    type="button"
                    class="text-xs font-medium text-[#D32F2F] hover:underline"
                    @click="editIngredient(ingredient)"
                  >
                    Editar
                  </button>
                  <button
                    type="button"
                    class="text-xs font-medium text-red-600 hover:underline"
                    @click="deleteIngredient(ingredient.id)"
                  >
                    Eliminar
                  </button>
                </div>
              </div>
            </div>
          </article>

          <!-- Columna pizzas: primero LISTA, luego formulario -->
          <div class="space-y-8 xl:col-span-2">
            <!-- LISTADO — arriba y muy visible -->
            <section
              id="pizzas-lista"
              class="scroll-mt-36 rounded-3xl border-2 border-[#D32F2F]/25 bg-gradient-to-b from-red-50/80 to-white p-5 shadow-md"
            >
              <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-red-100 pb-4">
                <div>
                  <h2 class="text-xl font-bold text-gray-900">Pizzas en el catálogo</h2>
                  <p class="mt-0.5 text-sm text-gray-600">
                    Aquí ves todas las pizzas publicadas. {{ pizzas.length === 0 ? 'Aún no hay pizzas.' : `Total: ${pizzas.length}.` }}
                  </p>
                </div>
                <span
                  class="inline-flex items-center rounded-full bg-[#D32F2F] px-3 py-1 text-sm font-bold text-white shadow-sm"
                  >{{ pizzas.length }}</span
                >
              </div>

              <div v-if="pizzas.length" class="grid gap-4 sm:grid-cols-2">
                <article
                  v-for="pizza in pizzas"
                  :key="pizza.id"
                  class="flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white p-3 shadow-sm"
                >
                  <img :src="pizza.image" :alt="pizza.name" class="mb-2 h-36 w-full rounded-xl object-cover" />
                  <p class="font-semibold text-gray-900">{{ pizza.name }}</p>
                  <p class="text-sm font-medium text-[#D32F2F]">S/ {{ Number(pizza.price).toFixed(2) }}</p>
                  <div class="mt-auto flex gap-2 pt-3">
                    <button
                      type="button"
                      class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-800 hover:bg-gray-50"
                      @click="editPizza(pizza)"
                    >
                      Editar
                    </button>
                    <button
                      type="button"
                      class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100"
                      @click="deletePizza(pizza.id)"
                    >
                      Eliminar
                    </button>
                  </div>
                </article>
              </div>
              <div v-else class="rounded-xl border border-dashed border-gray-300 bg-white/80 py-10 text-center text-sm text-gray-500">
                No hay pizzas todavía. Usa el formulario de abajo para crear la primera.
              </div>

              <p class="mt-4 text-center text-xs text-gray-500">
                ¿Quieres añadir otra?
                <a href="#pizza-form" class="font-semibold text-[#D32F2F] underline hover:no-underline">Ir al formulario</a>
              </p>
            </section>

            <!-- FORMULARIO — debajo, con título claro -->
            <section
              id="pizza-form"
              class="scroll-mt-36 space-y-4 rounded-3xl border border-gray-200 bg-white p-5 shadow-sm"
            >
              <div class="border-b border-gray-100 pb-4">
                <h2 class="text-lg font-bold text-gray-900">{{ pizzaFormTitle }}</h2>
                <p class="mt-1 text-sm text-gray-600">
                  Completa los datos y, si editas una pizza, pulsa «Editar» en la lista de arriba.
                </p>
              </div>

              <form class="grid gap-3 md:grid-cols-2" @submit.prevent="submitPizza">
                <input
                  v-model="pizzaForm.name"
                  type="text"
                  required
                  placeholder="Nombre"
                  class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none ring-[#D32F2F] focus:ring-2"
                />
                <input
                  v-model="pizzaForm.price"
                  type="number"
                  min="0"
                  step="0.01"
                  required
                  placeholder="Precio"
                  class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none ring-[#D32F2F] focus:ring-2"
                />
                <textarea
                  v-model="pizzaForm.description"
                  required
                  rows="3"
                  placeholder="Descripción"
                  class="md:col-span-2 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none ring-[#D32F2F] focus:ring-2"
                />

                <div class="md:col-span-2 space-y-3 rounded-2xl border border-gray-200 bg-gray-50/80 p-4">
                  <div>
                    <p class="text-sm font-semibold text-gray-900">Imagen de la pizza</p>
                    <p class="mt-0.5 text-xs text-gray-500">
                      Sube un archivo (JPG, PNG, WEBP) y se guardará en Cloudinary; la URL quedará en la base de datos.
                      También puedes pegar una URL si ya tienes la imagen alojada.
                    </p>
                  </div>

                  <div class="flex flex-col gap-3 sm:flex-row sm:items-start">
                    <div class="flex-1">
                      <label
                        class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-white px-4 py-6 text-center transition hover:border-[#D32F2F]/50 hover:bg-red-50/30"
                      >
                        <span class="text-sm font-medium text-gray-800">Subir imagen desde archivo</span>
                        <span class="mt-1 text-xs text-gray-500">Máx. recomendado 4 MB · se envía a Cloudinary</span>
                        <input
                          ref="imageFileInputRef"
                          type="file"
                          accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                          class="sr-only"
                          @change="onSelectImage"
                        />
                      </label>
                      <p v-if="pizzaImage" class="mt-2 text-xs font-medium text-emerald-700">
                        Archivo seleccionado: {{ pizzaImage.name }}
                      </p>
                    </div>
                    <div v-if="pizzaImagePreview" class="sm:w-40">
                      <p class="mb-1 text-xs text-gray-500">Vista previa</p>
                      <img
                        :src="pizzaImagePreview"
                        alt="Vista previa"
                        class="h-28 w-full rounded-lg border border-gray-200 object-cover"
                      />
                      <button
                        type="button"
                        class="mt-2 text-xs font-medium text-[#D32F2F] hover:underline"
                        @click="clearImageFile"
                      >
                        Quitar archivo
                      </button>
                    </div>
                  </div>

                  <div>
                    <label for="pizza-image-url" class="mb-1 block text-xs font-medium text-gray-600"
                      >O pega URL de imagen (si no subes archivo)</label
                    >
                    <input
                      id="pizza-image-url"
                      v-model="pizzaForm.image_url"
                      type="url"
                      placeholder="https://..."
                      class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none ring-[#D32F2F] focus:ring-2"
                      @input="onImageUrlInput"
                    />
                  </div>
                </div>
                <select
                  v-model="pizzaForm.ingredientIds"
                  multiple
                  class="md:col-span-2 min-h-28 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none ring-[#D32F2F] focus:ring-2"
                >
                  <option v-for="ingredient in ingredients" :key="ingredient.id" :value="ingredient.id">
                    {{ ingredient.name }}
                  </option>
                </select>
                <div class="md:col-span-2 flex flex-wrap gap-2">
                  <button
                    type="submit"
                    class="rounded-xl bg-[#D32F2F] px-4 py-2 text-sm font-semibold text-white hover:bg-[#B71C1C]"
                  >
                    {{ editingPizzaId ? 'Guardar cambios' : 'Crear pizza' }}
                  </button>
                  <button
                    v-if="editingPizzaId"
                    type="button"
                    class="rounded-xl border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                    @click="resetPizzaForm"
                  >
                    Cancelar edición
                  </button>
                  <a
                    href="#pizzas-lista"
                    class="inline-flex items-center rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >Ver listado de pizzas</a
                  >
                </div>
              </form>
            </section>
          </div>
        </div>

        <article id="pedidos" class="scroll-mt-36 rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
          <h2 class="mb-1 text-lg font-bold text-gray-900">Pedidos</h2>
          <p class="mb-4 text-sm text-gray-600">Historial de pedidos recientes.</p>
          <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            <div
              v-for="order in orders"
              :key="order.id"
              class="rounded-xl border border-gray-100 bg-gray-50 p-3 text-sm text-gray-800"
            >
              <p class="font-semibold">Pedido #{{ order.id }}</p>
              <p>{{ order.user?.name ?? 'Cliente' }}</p>
              <p>{{ order.pizza?.name ?? `Pizza ${order.pizza_id}` }}</p>
              <p class="font-semibold text-[#D32F2F]">S/ {{ Number(order.total).toFixed(2) }}</p>
            </div>
          </div>
        </article>
      </template>
    </section>
  </div>
</template>
