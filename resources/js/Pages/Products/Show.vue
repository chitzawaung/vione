<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

defineProps({
    product: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <Head :title="product.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Details</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Flash Messages -->
                    <!-- <div v-if="$page.props.flash.success" class="m-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ $page.props.flash.success }}
                    </div>
                    <div v-if="$page.props.flash.error" class="m-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ $page.props.flash.error }}
                    </div> -->

                    <!-- Product Details -->
                    <div class="md:flex">
                        <!-- Product Image -->
                        <div class="md:w-1/2 p-6">
                            <div class="h-96 bg-gradient-to-br from-blue-400 to-purple-600 rounded-xl flex items-center justify-center">
                                <div class="text-white text-center">
                                    <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <p class="text-lg font-medium">Product Image</p>
                                </div>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="md:w-1/2 p-6">
                            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                                {{ product.name }}
                            </h1>

                            <div class="mb-6">
                                <span class="text-4xl font-bold text-green-600">
                                    ${{ parseFloat(product.price).toFixed(2) }}
                                </span>
                            </div>

                            <!-- Stock Status -->
                            <div class="mb-6">
                                <div v-if="product.quantity_available === 0" 
                                     class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 font-medium rounded-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Out of Stock
                                </div>
                                <div v-else 
                                     class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 font-medium rounded-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    In Stock: {{ product.quantity_available }} available
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                                <p class="text-gray-600 leading-relaxed">
                                    {{ product.description || 'No description available for this product.' }}
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-4">
                                <Link :href="route('products.index')"
                                      class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                                    ← Back to Products
                                </Link>
                                
                                <Link v-if="product.quantity_available > 0"
                                      :href="route('purchase.create', product.id)"
                                      class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-center transition-colors duration-200">
                                    Buy Now
                                </Link>
                                <button v-else
                                        disabled
                                        class="flex-1 px-6 py-3 bg-gray-300 text-gray-500 font-medium rounded-lg cursor-not-allowed">
                                    Out of Stock
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Product Details -->
                    <div class="border-t border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Product ID</p>
                                <p class="font-medium text-gray-800">#{{ product.id }}</p>
                            </div> -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Price per Unit</p>
                                <p class="font-medium text-gray-800">${{ parseFloat(product.price).toFixed(2) }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Available Stock</p>
                                <p class="font-medium text-gray-800">{{ product.quantity_available }} units</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Status</p>
                                <p class="font-medium" :class="product.quantity_available > 0 ? 'text-green-600' : 'text-red-600'">
                                    {{ product.quantity_available > 0 ? 'Available' : 'Out of Stock' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
