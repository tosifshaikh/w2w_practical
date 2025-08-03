<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useTransactions } from '@/pages/Transaction/Transactions';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Pagination from '../Paggination.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Transactions',
        href: '/transactions',
    },
];
const props = defineProps({
    transactions: Object,
    filters: Object,
    dropdowns: Object,
    pagination: Object,
});
const { filters, handlePageChange, resetFilters, hasFilters } = useTransactions(props.filters, 'transactions.index');

</script>

<template>
    <Head title="Transactions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6 sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="mb-1 block text-sm font-medium text-gray-700">Search</label>
                        <input
                            type="text"
                            id="search"
                            v-model="filters.search"
                            placeholder="Amount, last4, or customer..."
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                        />
                    </div>

                    <!-- Currency Filter -->
                    <div v-if="dropdowns?.currencies">
                        <label for="currency" class="mb-1 block text-sm font-medium text-gray-700">Currency</label>
                        <select
                            id="currency"
                            v-model="filters.currency_id"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                        >
                            <option value="">All Currencies</option>
                            <option v-for="currency in dropdowns.currencies" :key="currency.id" :value="currency.id">
                                {{ currency.code }}
                            </option>
                        </select>
                    </div>

                    <!-- Payment Type Filter -->
                    <div v-if="dropdowns?.paymentTypes">
                        <label for="paymentType" class="mb-1 block text-sm font-medium text-gray-700">Payment Type</label>
                        <select
                            id="paymentType"
                            v-model="filters.payment_type_id"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                        >
                            <option value="">All Types</option>
                            <option v-for="type in dropdowns.paymentTypes" :key="type.id" :value="type.id">
                                {{ type.code }}
                            </option>
                        </select>
                    </div>
                    <div v-if="dropdowns?.paymentStatuses">
                        <label for="paymentType" class="mb-1 block text-sm font-medium text-gray-700">Payment Type</label>
                        <select
                            id="paymentType"
                            v-model="filters.payment_status_id"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                        >
                            <option value="">All Status</option>
                            <option v-for="status in dropdowns.paymentStatuses" :key="status.id" :value="status.id">
                                {{ status.code }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button
                        @click="resetFilters"
                        :disabled="!hasFilters"
                        :class="{
                            'cursor-not-allowed bg-gray-100 text-gray-400': !hasFilters,
                            'bg-gray-100 text-gray-700 hover:cursor-pointer hover:bg-gray-200': hasFilters,
                        }"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium shadow-sm transition"
                    >
                        Reset Filters
                    </button>
                </div>
            </div>

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase"
                                >
                                    ID
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase"
                                >
                                    Customer
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase"
                                >
                                    Email
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase"
                                >
                                    Last 4
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase"
                                >
                                    Currency
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase"
                                >
                                    Amount
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase"
                                >
                                    Type
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase"
                                >
                                    Status
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase"
                                >
                                    Transaction Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900">{{ transaction.id }}</td>
                                <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900">
                                    {{ transaction.customer?.customer_name }}
                                </td>
                                <td class="max-w-xs truncate px-4 py-3 text-sm whitespace-nowrap text-gray-900">{{ transaction.customer?.email }}</td>
                                <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900">{{ transaction.last4 }}</td>
                                <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900">{{ transaction.currency?.code }}</td>
                                <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900">{{ transaction.amount }}</td>
                                <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900">{{ transaction.payment_type?.code }}</td>
                                <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900">{{ transaction.payment_status?.code }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ transaction.formatted_transaction_date }}</td>
                            </tr>
                            <tr v-if="transactions.data.length === 0">
                                <td colspan="9" class="px-4 py-4 text-center text-sm text-gray-500">No transactions found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-4 py-3 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ pagination.from }}</span> to <span class="font-medium">{{ pagination.to }}</span> of
                            <span class="font-medium">{{ pagination.total }}</span> results
                        </div>
                        <Pagination :links="transactions.links" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
