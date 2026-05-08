<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

defineProps({
    transactions: {
        type: Object,
        required: true
    }
})

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<template>
    <Head title="Transaction History" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaction History</h2>
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

                    <!-- Header -->
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Your Purchase History</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                View all your past purchases and transactions.
                            </p>
                        </div>
                        <Link :href="route('products.index')"
                              class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            Shop Now
                        </Link>
                    </div>

                    <!-- Desktop Table View -->
                    <div v-if="transactions.data.length > 0" class="hidden md:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Product
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Price
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Purchase Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="transaction in transactions.data" :key="transaction.id" 
                                        class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ transaction.product.name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        ID: #{{ transaction.id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ transaction.quantity }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-green-600">
                                                ${{ parseFloat(transaction.total_amount).toFixed(2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(transaction.created_at) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div v-if="transactions.data.length > 0" class="md:hidden space-y-4">
                        <div v-for="transaction in transactions.data" :key="transaction.id" 
                             class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-400 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            {{ transaction.product.name }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Transaction #{{ transaction.id }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-green-600">
                                        ${{ parseFloat(transaction.total_amount).toFixed(2) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ formatDate(transaction.created_at) }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-sm text-gray-600">Quantity: {{ transaction.quantity }}</span>
                                <span class="text-sm text-gray-600">
                                    ${{ parseFloat(transaction.product.price).toFixed(2) }} each
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't made any purchases yet.</p>
                        <div class="mt-6">
                            <Link :href="route('products.index')"
                                  class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Start Shopping
                            </Link>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="transactions.links.length > 3" class="mt-8">
                        <nav class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing {{ transactions.from }} to {{ transactions.to }} of {{ transactions.total }} results
                            </div>
                            <div class="flex gap-2">
                                <template v-for="(link, key) in transactions.links" :key="key">
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
