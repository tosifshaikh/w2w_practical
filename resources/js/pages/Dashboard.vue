<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArcElement, BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, LineElement, PointElement, Title, Tooltip } from 'chart.js';
import { Line, Pie } from 'vue-chartjs';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, PointElement, LineElement);

const props = defineProps<{
    charts: {
        totalTransactions: {
            count: number;
            total_amount: number;
            avg_amount: number;
        };
        byStatus: Array<{
            status: string;
            count: number;
            total_amount: number;
        }>;
        byCurrency: Array<{
            currency_name: string;
            count: number;
            total_amount: number;
        }>;
        overTime: Array<{
            date: string;
            count: number;
            total_amount: number;
        }>;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }];

// Prepare data for charts
const statusChartData = {
    labels: props.charts.byStatus.map((item) => item.status_name),
    datasets: [
        {
            label: 'Transactions by Status',
            data: props.charts.byStatus.map((item) => item.count),
            backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
        },
    ],
};

const currencyChartData = {
    labels: props.charts.byCurrency.map((item) => item.currency_name),
    datasets: [
        {
            label: 'Transactions by Currency',
            data: props.charts.byCurrency.map((item) => item.count),
            backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
        },
    ],
};

const timeChartData = {
    labels: props.charts.overTime.map((item) => item.date),
    datasets: [
        {
            label: 'Transaction Count',
            data: props.charts.overTime.map((item) => item.count),
            borderColor: '#4f46e5',
            backgroundColor: '#4f46e5',
            yAxisID: 'y',
        },
        {
            label: 'Total Amount',
            data: props.charts.overTime.map((item) => item.total_amount),
            borderColor: '#10b981',
            backgroundColor: '#10b981',
            yAxisID: 'y1',
        },
    ],
};

const statusChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
};

const timeChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        mode: 'index',
        intersect: false,
    },
    scales: {
        y: {
            type: 'linear',
            display: true,
            position: 'left',
        },
        y1: {
            type: 'linear',
            display: true,
            position: 'right',
            grid: {
                drawOnChartArea: false,
            },
        },
    },
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Summary Cards -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-gray-800">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Total Transactions</h3>
                    <p class="mt-2 text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                        {{ charts.totalTransactions.count.toLocaleString() }}
                    </p>
                </div>

                <div class="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-gray-800">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Total Amount</h3>
                    <p class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                        ${{ charts.totalTransactions.total_amount ? charts.totalTransactions.total_amount.toLocaleString() : '0' }}
                    </p>
                </div>

                <div class="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-gray-800">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Average Amount</h3>
                    <p class="mt-2 text-3xl font-bold text-amber-500 dark:text-amber-400">
                        ${{ Math.round(charts.totalTransactions.avg_amount).toLocaleString() }}
                    </p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-gray-800">
                    <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Transactions by Status</h3>
                    <div class="h-64">
                        <Pie :data="statusChartData" :options="statusChartOptions" />
                    </div>
                </div>
                <div class="rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-gray-800">
                    <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Transactions by Currency</h3>
                    <div class="h-64">
                        <Pie :data="currencyChartData" :options="statusChartOptions" />
                    </div>
                </div>

                <div class="rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-gray-800">
                    <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Transactions Over Time (Last 30 Days)</h3>
                    <div class="h-64">
                        <Line :data="timeChartData" :options="timeChartOptions" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
