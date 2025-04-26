<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

const form = ref({
  email: '',
  password: '',
})

const errors = ref({}) // ⬅️ To hold validation errors

const submit = async () => {
  errors.value = {} // Clear previous errors
  try {
    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/login', form.value)
    router.get('/tasks')
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else if (error.response?.status === 401) {
      errors.value = { email: ['Invalid credentials.'] }
    } else {
      console.error('Unexpected error:', error)
    }
  }
}
</script>


<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-400 to-indigo-200">
      <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Welcome Back 👋</h1>
        <form @submit.prevent="submit" class="space-y-5">
          <!-- Email Field -->
          <div>
            <input
              v-model="form.email"
              type="email"
              placeholder="Email"
              class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <div v-if="errors.email" class="text-red-500 text-sm mt-1">
              {{ errors.email[0] }}
            </div>
          </div>

          <!-- Password Field -->
          <div>
            <input
              v-model="form.password"
              type="password"
              placeholder="Password"
              class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <div v-if="errors.password" class="text-red-500 text-sm mt-1">
              {{ errors.password[0] }}
            </div>
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition duration-200"
            >
              Login
            </button>
          </div>
          <div class="text-center">
                <span class="text-sm text-gray-600">Don't have an account?</span>
                <button
                    type="button"
                    @click="router.visit('/register')"
                    class="ml-1 ms-1 text-blue-600 hover:underline font-medium"
                >
                    Register
                </button>
            </div>
        </form>
      </div>
    </div>
  </template>


