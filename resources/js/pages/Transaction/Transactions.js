import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

export function useTransactions(initialFilters, routeName) {
    const filters = ref({
        search: initialFilters?.search || '',
        currency_id: initialFilters?.currency_id || '',
        payment_type_id: initialFilters?.payment_type_id || '',
        payment_status_id: initialFilters?.payment_status_id || '',
    });

    let searchTimeout;

    watch(
        filters,
        (newFilters) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                router.get(route(routeName), newFilters, {
                    preserveState: true,
                    replace: true,
                });
            }, 300);
        },
        { deep: true },
    );

    const handlePageChange = (page) => {
        router.get(
            route(routeName),
            {
                ...filters.value,
                page: page,
            },
            {
                preserveState: true,
                replace: true,
                preserveScroll: true,
            },
        );
    };

    const resetFilters = () => {
        filters.value = {
            search: '',
            currency_id: '',
            payment_type_id: '',
            payment_status_id: '',
        };
    };

    const hasFilters = computed(() => {
        return Object.values(filters.value).some((value) => value !== '');
    });

    return {
        filters,
        handlePageChange,
        resetFilters,
        hasFilters,
    };
}
