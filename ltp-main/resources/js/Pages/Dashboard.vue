<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components:{
        AppLayout,
        Head
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Dashboard</h2>
        </template>
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">Dashboard!</div>
            </div>
        </div>
    </AppLayout>
</template>
