<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = ref({})

const submit = async () => {
  errors.value = {}
  try {
    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/register', form.value)
    router.get('/tasks')
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error('Unexpected error:', error)
    }
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-green-400 to-emerald-200">
    <div class="bg-white shadow-xl rounded-xl p-8 w-full max-w-md">
      <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Create Account</h1>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Name -->
        <div>
          <input
            v-model="form.name"
            type="text"
            placeholder="Name"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <div v-if="errors.name" class="text-red-500 text-sm mt-1">
            {{ errors.name[0] }}
          </div>
        </div>

        <!-- Email -->
        <div>
          <input
            v-model="form.email"
            type="email"
            placeholder="Email"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <div v-if="errors.email" class="text-red-500 text-sm mt-1">
            {{ errors.email[0] }}
          </div>
        </div>

        <!-- Password -->
        <div>
          <input
            v-model="form.password"
            type="password"
            placeholder="Password"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
          <div v-if="errors.password" class="text-red-500 text-sm mt-1">
            {{ errors.password[0] }}
          </div>
        </div>

        <!-- Confirm Password -->
        <div>
          <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirm Password"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          />
        </div>

        <!-- Submit -->
        <div>
          <button
            type="submit"
            class="w-full bg-emerald-600 text-white font-semibold py-3 rounded-lg hover:bg-emerald-700 transition duration-200"
          >
            Register
          </button>
          <div class="text-center">
                <span class="text-sm text-gray-600">Already have an account?</span>
                <button
                    type="button"
                    @click="router.visit('/')"
                    class="ml-1 ms-1 text-blue-600 hover:underline font-medium"
                >
                Login
                </button>
            </div>
        </div>
      </form>
    </div>
  </div>
</template>
