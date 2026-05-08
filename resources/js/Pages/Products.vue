<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    products: Object,
    filters: Object,
});

const page = usePage();

const flash = computed(() => page.props.flash?.success ?? '');

const editingProduct = ref(null);

const form = useForm({
    name: '',
    price: '',
    quantity_available: '',
});

const deleteForm = useForm({});

const isEditing = computed(() => editingProduct.value !== null);

const title = computed(() =>
    isEditing.value ? 'Edit Product' : 'Create Product'
);

const sortField = ref(props.filters?.sort_by || 'id');

const sortDirection = ref(
    props.filters?.sort_direction || 'asc'
);

function getSortIcon(field) {
    if (sortField.value !== field) {
        return '↕';
    }

    return sortDirection.value === 'asc'
        ? '↑'
        : '↓';
}

function sort(column) {
    if (sortField.value === column) {
        sortDirection.value =
            sortDirection.value === 'asc'
                ? 'desc'
                : 'asc';
    } else {
        sortField.value = column;
        sortDirection.value = 'asc';
    }

    router.get(
        route('admin.products.index'),
        {
            sort_by: sortField.value,
            sort_direction: sortDirection.value,
            per_page: props.filters?.per_page || 10,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}

function changePerPage(perPage) {
    router.get(
        route('admin.products.index'),
        {
            sort_by: sortField.value,
            sort_direction: sortDirection.value,
            per_page: perPage,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}

function startEdit(product) {
    editingProduct.value = product;

    form.reset();

    form.name = product.name;
    form.price = product.price;
    form.quantity_available =
        product.quantity_available;
}

function cancelEdit() {
    editingProduct.value = null;
    form.reset();
}

function submit() {
    if (isEditing.value && editingProduct.value) {
        form.put(
            route(
                'admin.products.update',
                editingProduct.value.id
            ),
            {
                preserveScroll: true,
                onSuccess: () => {
                    cancelEdit();
                },
            }
        );
    } else {
        form.post(route('admin.products.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
            },
        });
    }
}

function removeProduct(product) {
    if (
        !confirm(
            'Are you sure you want to delete this product?'
        )
    ) {
        return;
    }

    deleteForm.delete(
        route('admin.products.destroy', product.id),
        {
            preserveScroll: true,
        }
    );
}
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div
                class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"
            >
                <!-- Form -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div
                        class="p-6 bg-white border-b border-gray-200"
                    >
                        <div
                            v-if="flash"
                            class="mb-4 rounded-lg bg-green-100 p-4 text-green-700"
                        >
                            {{ flash }}
                        </div>

                        <div class="mb-6">
                            <h3
                                class="text-lg font-medium text-gray-900"
                            >
                                {{ title }}
                            </h3>

                            <p
                                class="mt-1 text-sm text-gray-600"
                            >
                                Create or update a
                                product.
                            </p>
                        </div>

                        <div
                            class="grid gap-6 md:grid-cols-3"
                        >
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Name
                                </label>

                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                />

                                <p
                                    v-if="
                                        form.errors.name
                                    "
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{
                                        form.errors.name
                                    }}
                                </p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Price
                                </label>

                                <input
                                    v-model="form.price"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                />

                                <p
                                    v-if="
                                        form.errors.price
                                    "
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{
                                        form.errors.price
                                    }}
                                </p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Quantity
                                </label>

                                <input
                                    v-model="
                                        form.quantity_available
                                    "
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                />

                                <p
                                    v-if="
                                        form.errors
                                            .quantity_available
                                    "
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{
                                        form.errors
                                            .quantity_available
                                    }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="mt-6 flex items-center gap-3"
                        >
                            <button
                                type="button"
                                @click="submit"
                                :disabled="
                                    form.processing
                                "
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-500"
                            >
                                {{
                                    isEditing
                                        ? 'Update Product'
                                        : 'Create Product'
                                }}
                            </button>

                            <button
                                v-if="isEditing"
                                type="button"
                                @click="cancelEdit"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div
                        class="p-6 bg-white border-b border-gray-200"
                    >
                        <div
                            class="flex items-center justify-between mb-6"
                        >
                            <h3
                                class="text-lg font-medium text-gray-900"
                            >
                                Product List
                            </h3>

                            <div
                                class="flex items-center gap-2"
                            >
                                <label
                                    class="text-sm text-gray-700"
                                >
                                    Show
                                </label>

                                <select
                                    :value="
                                        props.filters
                                            ?.per_page ||
                                        10
                                    "
                                    @change="
                                        changePerPage(
                                            $event.target
                                                .value
                                        )
                                    "
                                    class="rounded-md border-gray-300 shadow-sm"
                                >
                                    <option value="5">
                                        5
                                    </option>

                                    <option value="10">
                                        10
                                    </option>

                                    <option value="25">
                                        25
                                    </option>

                                    <option value="50">
                                        50
                                    </option>
                                </select>

                                <span
                                    class="text-sm text-gray-700"
                                >
                                    per page
                                </span>
                            </div>
                        </div>

                        <div
                            class="overflow-x-auto"
                        >
                            <table
                                class="min-w-full divide-y divide-gray-200"
                            >
                                <thead
                                    class="bg-gray-50"
                                >
                                    <tr>
                                        <th
                                            @click="
                                                sort(
                                                    'id'
                                                )
                                            "
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        >
                                            ID
                                            {{
                                                getSortIcon(
                                                    'id'
                                                )
                                            }}
                                        </th>

                                        <th
                                            @click="
                                                sort(
                                                    'name'
                                                )
                                            "
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        >
                                            Name
                                            {{
                                                getSortIcon(
                                                    'name'
                                                )
                                            }}
                                        </th>

                                        <th
                                            @click="
                                                sort(
                                                    'price'
                                                )
                                            "
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        >
                                            Price
                                            {{
                                                getSortIcon(
                                                    'price'
                                                )
                                            }}
                                        </th>

                                        <th
                                            @click="
                                                sort(
                                                    'quantity_available'
                                                )
                                            "
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                        >
                                            Quantity
                                            {{
                                                getSortIcon(
                                                    'quantity_available'
                                                )
                                            }}
                                        </th>

                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody
                                    class="divide-y divide-gray-200 bg-white"
                                >
                                    <tr
                                        v-for="product in products.data"
                                        :key="
                                            product.id
                                        "
                                    >
                                        <td
                                            class="px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{
                                                product.id
                                            }}
                                        </td>

                                        <td
                                            class="px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{
                                                product.name
                                            }}
                                        </td>

                                        <td
                                            class="px-6 py-4 text-sm text-gray-900"
                                        >
                                            ${{
                                                Number(
                                                    product.price
                                                ).toFixed(
                                                    2
                                                )
                                            }}
                                        </td>

                                        <td
                                            class="px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{
                                                product.quantity_available
                                            }}
                                        </td>

                                        <td
                                            class="px-6 py-4 text-right space-x-2"
                                        >
                                            <button
                                                @click="
                                                    startEdit(
                                                        product
                                                    )
                                                "
                                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white rounded-md hover:bg-yellow-400"
                                            >
                                                Edit
                                            </button>

                                            <button
                                                @click="
                                                    removeProduct(
                                                        product
                                                    )
                                                "
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-500"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>

                                    <tr
                                        v-if="
                                            products.data
                                                .length ===
                                            0
                                        "
                                    >
                                        <td
                                            colspan="5"
                                            class="px-6 py-4 text-center text-gray-500"
                                        >
                                            No products
                                            found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div
                            v-if="
                                products.last_page >
                                1
                            "
                            class="mt-6 flex items-center justify-between"
                        >
                            <div
                                class="text-sm text-gray-700"
                            >
                                Showing
                                {{ products.from }}
                                to
                                {{ products.to }}
                                of
                                {{ products.total }}
                                results
                            </div>

                            <div
                                class="flex items-center space-x-1"
                            >
                                <Link
                                    v-for="link in products.links"
                                    :key="
                                        link.label
                                    "
                                    :href="
                                        link.url ||
                                        '#'
                                    "
                                    v-html="
                                        link.label
                                    "
                                    :class="[
                                        'px-4 py-2 border text-sm rounded-md',
                                        link.active
                                            ? 'bg-indigo-600 text-white'
                                            : 'bg-white text-gray-700',
                                        !link.url
                                            ? 'opacity-50 cursor-not-allowed'
                                            : ''
                                    ]"
                                    preserve-scroll
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
