import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import { api } from '@/services/api'
import type { Order, Pizza } from '@/types'

interface CartItem {
  pizza: Pizza
  quantity: number
}

export const useShopStore = defineStore('shop', () => {
  const pizzas = ref<Pizza[]>([])
  const orders = ref<Order[]>([])
  const cart = ref<CartItem[]>([])
  const loading = ref(false)

  const cartCount = computed(() => cart.value.reduce((acc, item) => acc + item.quantity, 0))
  const cartTotal = computed(() =>
    cart.value.reduce((acc, item) => acc + Number(item.pizza.price) * item.quantity, 0),
  )

  async function fetchPizzas() {
    loading.value = true
    try {
      const { data } = await api.get<Pizza[]>('/pizzas')
      pizzas.value = data
    } finally {
      loading.value = false
    }
  }

  async function fetchMyOrder(orderId: number) {
    const { data } = await api.get<Order>(`/orders/${orderId}`)
    return data
  }

  async function fetchAllOrders() {
    const { data } = await api.get<Order[]>('/orders')
    orders.value = data
  }

  function addToCart(pizza: Pizza) {
    const existing = cart.value.find((item) => item.pizza.id === pizza.id)
    if (existing) {
      existing.quantity += 1
      return
    }
    cart.value.push({ pizza, quantity: 1 })
  }

  function removeFromCart(pizzaId: number) {
    cart.value = cart.value.filter((item) => item.pizza.id !== pizzaId)
  }

  function incrementQuantity(pizzaId: number) {
    const item = cart.value.find((i) => i.pizza.id === pizzaId)
    if (item) {
      item.quantity += 1
    }
  }

  function decrementQuantity(pizzaId: number) {
    const item = cart.value.find((i) => i.pizza.id === pizzaId)
    if (!item) return
    if (item.quantity <= 1) {
      removeFromCart(pizzaId)
      return
    }
    item.quantity -= 1
  }

  async function placeOrder() {
    const requests = cart.value.map((item) =>
      api.post('/orders', {
        pizza_id: item.pizza.id,
        quantity: item.quantity,
      }),
    )
    const responses = await Promise.all(requests)
    orders.value = responses.map((response) => response.data.order as Order)
    cart.value = []
  }

  return {
    pizzas,
    orders,
    cart,
    loading,
    cartCount,
    cartTotal,
    fetchPizzas,
    fetchMyOrder,
    fetchAllOrders,
    addToCart,
    removeFromCart,
    incrementQuantity,
    decrementQuantity,
    placeOrder,
  }
})
