<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Log in</Link
                >

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Register</Link
                >
            </template>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
  
            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

                    <!-- Products -->
                    <a href="/#"
                        class="p-6 bg-white rounded-lg shadow flex hover:scale-[1.02] transition">
                        <div>
                            <div class="h-14 w-14 bg-blue-100 flex items-center justify-center rounded-full">
                                📦
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900">
                                Products
                            </h2>

                            <p class="mt-4 text-gray-500 text-sm">
                                Manage all products, prices, stock availability and product details.
                            </p>
                        </div>
                    </a>

                    <!-- Orders -->
                    <a href="/#"
                        class="p-6 bg-white rounded-lg shadow flex hover:scale-[1.02] transition">
                        <div>
                            <div class="h-14 w-14 bg-green-100 flex items-center justify-center rounded-full">
                                🧾
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900">
                                Orders
                            </h2>

                            <p class="mt-4 text-gray-500 text-sm">
                                View and manage all customer purchase orders and statuses.
                            </p>
                        </div>
                    </a>

                    <!-- Purchase Page -->
                    <a href="/#"
                        class="p-6 bg-white rounded-lg shadow flex hover:scale-[1.02] transition">
                        <div>
                            <div class="h-14 w-14 bg-purple-100 flex items-center justify-center rounded-full">
                                🛒
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900">
                                Buy Products
                            </h2>

                            <p class="mt-4 text-gray-500 text-sm">
                                Place new product orders with quantity selection and total calculation.
                            </p>
                        </div>
                    </a>

                    <!-- Inventory -->
                    <a href="/#"
                        class="p-6 bg-white rounded-lg shadow flex hover:scale-[1.02] transition">
                        <div>
                            <div class="h-14 w-14 bg-yellow-100 flex items-center justify-center rounded-full">
                                📊
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900">
                                Inventory
                            </h2>

                            <p class="mt-4 text-gray-500 text-sm">
                                Track stock levels, low stock alerts, and inventory changes.
                            </p>
                        </div>
                    </a>

                </div>
            </div>

            <!-- Footer Section -->
            <div class="flex justify-center mt-16 px-6 sm:items-center sm:justify-between">
                <div class="text-center text-sm text-gray-500 sm:text-left">
                    <p>Product Order System © {{ new Date().getFullYear() }}</p>
                </div>
            </div>
           
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
