<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

defineProps({
    products: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- Flash Messages -->
                    <!-- <div v-if="$page.props.flash.success" class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ $page.props.flash.success }}
                    </div>
                    <div v-if="$page.props.flash.error" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ $page.props.flash.error }}
                    </div> -->

                    <!-- Products Grid -->
                    <div v-if="products.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <div v-for="product in products.data" :key="product.id" 
                             class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                            
                            <!-- Product Image Placeholder -->
                            <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                                <div class="text-white text-center">
                                    <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <p class="text-sm font-medium">Product Image</p>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <h3 class="font-semibold text-lg text-gray-800 mb-2 line-clamp-2">
                                    {{ product.name }}
                                </h3>
                                
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                    {{ product.description || 'No description available' }}
                                </p>

                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-2xl font-bold text-green-600">
                                        ${{ parseFloat(product.price).toFixed(2) }}
                                    </span>
                                    
                                    <!-- Stock Status -->
                                    <span v-if="product.quantity_available === 0" 
                                          class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                        Out of Stock
                                    </span>
                                    <span v-else 
                                          class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                        In Stock ({{ product.quantity_available }})
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <Link :href="route('products.show', product.id)"
                                          class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-center transition-colors duration-200">
                                        View Details
                                    </Link>
                                    
                                    <Link v-if="product.quantity_available > 0"
                                          :href="route('purchase.create', product.id)"
                                          class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-center transition-colors duration-200">
                                        Buy Now
                                    </Link>
                                    <button v-else
                                            disabled
                                            class="flex-1 bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed">
                                        Out of Stock
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No products available</h3>
                        <p class="mt-1 text-sm text-gray-500">Check back later for new products.</p>
                    </div>

                    <!-- Pagination -->
                    <div v-if="products.links.length > 3" class="mt-8">
                        <nav class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing {{ products.from }} to {{ products.to }} of {{ products.total }} results
                            </div>
                            <div class="flex gap-2">
                                <template v-for="(link, key) in products.links" :key="key">
                                    <Link v-if="link.url" 
                                          :href="link.url"
                                          v-html="link.label"
                                          :class="{
                                              'bg-blue-600 text-white': link.active,
                                              'bg-white text-gray-700 hover:bg-gray-50': !link.active,
                                              'px-3 py-2 text-sm font-medium rounded-md border border-gray-300': true
                                          }">
                                    </Link>
                                    <span v-else
                                          v-html="link.label"
                                          class="px-3 py-2 text-sm font-medium text-gray-500 border border-gray-300 rounded-md cursor-not-allowed">
                                    </span>
                                </template>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
