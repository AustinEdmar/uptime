<template>
    <Head title="Dashboard" />

    <AuthenticatedEndpointLayout :endpoint="endpoint">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <h2 class="font-semibold text-lg text-gray-800 leading-tight">Checks</h2>

                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8 mt-3">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="relative overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full table-fixed divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="min-w-[12rem] pl-4 py-3.5 px-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Checked at
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Response code
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Response body
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <Check
                                            v-for="check in checks.data"
                                            :key="check.id"
                                            :check="check"
                                        />
                                    </tbody>
                                </table>

                                <!-- Paginação -->
                                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                                    <div class="text-sm text-gray-500">
                                        Mostrando
                                        <span class="font-medium">{{ checks.meta.from ?? 0 }}</span>
                                        –
                                        <span class="font-medium">{{ checks.meta.to ?? 0 }}</span>
                                        de
                                        <span class="font-medium">{{ checks.meta.total }}</span>
                                        resultados
                                    </div>

                                    <div class="flex gap-2">
                                        <Link
                                            v-if="checks.links.prev"
                                            :href="checks.links.prev"
                                            preserve-scroll
                                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                        >
                                            ← Anterior
                                        </Link>
                                        <span
                                            v-else
                                            class="relative inline-flex items-center rounded-md border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed"
                                        >
                                            ← Anterior
                                        </span>

                                        <Link
                                            v-if="checks.links.next"
                                            :href="checks.links.next"
                                            preserve-scroll
                                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                        >
                                            Próxima →
                                        </Link>
                                        <span
                                            v-else
                                            class="relative inline-flex items-center rounded-md border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed"
                                        >
                                            Próxima →
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedEndpointLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AuthenticatedEndpointLayout from '@/Layouts/AuthenticatedEndpointLayout.vue';
import Check from '@/Components/Check.vue';

defineProps({
    endpoint: Object,
    checks: Object,   // { data: [...], links: { prev, next }, meta: { from, to, total } }
});
</script>