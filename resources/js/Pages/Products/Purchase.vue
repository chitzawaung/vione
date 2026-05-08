<script setup>
import { computed } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const page = usePage()

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

const form = useForm({
    quantity: 1
})

const flash = computed(() => page.props.flash || {})

const totalPrice = computed(() => {
    return (props.product.price * form.quantity).toFixed(2)
})

const maxQuantity = computed(() => {
    return props.product.quantity_available
})

const submit = () => {
    form.post(route('purchase.store', props.product.id), {
        onSuccess: () => {
            form.reset()
        }
    })
}

const increaseQuantity = () => {
    if (form.quantity < maxQuantity.value) {
        form.quantity++
    }
}

const decreaseQuantity = () => {
    if (form.quantity > 1) {
        form.quantity--
    }
}
</script>

<template>
    <Head :title="`Purchase ${product.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Purchase Product
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                <!-- Flash Messages -->
                <div
                    v-if="flash.success"
                    class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg"
                >
                    {{ flash.success }}
                </div>

                <div
                    v-if="flash.error"
                    class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg"
                >
                    {{ flash.error }}
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <div class="md:flex">

                            <!-- Product Info -->
                            <div class="md:w-1/2 p-6">
                                <h1 class="text-2xl font-bold text-gray-800 mb-4">
                                    {{ product.name }}
                                </h1>

                                <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-600 rounded-xl flex items-center justify-center mb-4">
                                    <div class="text-white text-center">
                                        <p class="text-sm font-medium">Product Image</p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span>Unit Price:</span>
                                        <span class="font-semibold">
                                            ${{ parseFloat(product.price).toFixed(2) }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span>Available Stock:</span>
                                        <span :class="product.quantity_available > 0 ? 'text-green-600' : 'text-red-600'">
                                            {{ product.quantity_available }} units
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h3 class="font-semibold mb-2">Description</h3>
                                    <p class="text-gray-600 text-sm">
                                        {{ product.description || 'No description available.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Purchase Form -->
                            <div class="md:w-1/2 p-6 bg-gray-50">
                                <h2 class="text-xl font-bold mb-6">Complete Purchase</h2>

                                <!-- Quantity -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium mb-2">
                                        Quantity
                                    </label>

                                    <div class="flex items-center gap-3">

                                        <button type="button" @click="decreaseQuantity"
                                            class="w-10 h-10 bg-white border rounded-lg">
                                            -
                                        </button>

                                        <input
                                            type="number"
                                            v-model.number="form.quantity"
                                            min="1"
                                            :max="maxQuantity"
                                            class="flex-1 text-center border rounded-lg py-2"
                                        />

                                        <button type="button" @click="increaseQuantity"
                                            class="w-10 h-10 bg-white border rounded-lg">
                                            +
                                        </button>

                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="bg-white p-4 rounded-lg mb-6">
                                    <div class="flex justify-between">
                                        <span>Total:</span>
                                        <span class="text-xl font-bold text-green-600">
                                            ${{ totalPrice }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full bg-blue-600 text-white py-3 rounded-lg"
                                >
                                    {{ form.processing ? 'Processing...' : `Buy $${totalPrice}` }}
                                </button>

                                <Link
                                    :href="route('products.show', product.id)"
                                    class="block mt-3 text-center bg-gray-200 py-3 rounded-lg"
                                >
                                    Cancel
                                </Link>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>