<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import PrimaryButton from '../Components/PrimaryButton.vue';
import TextInput from '../Components/TextInput.vue';
import InputLabel from '../Components/InputLabel.vue';
import InputError from '../Components/InputError.vue';
import Endpoint from '../Components/Endpoint.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { PlusIcon } from '@heroicons/vue/24/solid';
import {
    SignalIcon,
    ChartBarIcon,
    CheckCircleIcon,
    XCircleIcon,
    ExclamationTriangleIcon,
    BellIcon,
    TrashIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';
import EmailNotifications from '../Components/EmailNotifications.vue';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    site: Object,
    sites: Object,
    endpoints: Object,
});

const page = usePage();

const endpointForm = useForm({
    location: null,
    frequency: page.props.endpointFrequencies.data[0].frequency,
});

const storeEndpoint = () => {
    endpointForm.post(`/sites/${props.site.data.id}/endpoints`, {
        preserveScroll: true,
        onSuccess: () => {
            endpointForm.reset();
        },
    });
};

const showDeleteModal = ref(false);
const showNotificationsModal = ref(false);
const showFabOptions = ref(false);

const deleteSite = () => {
    showFabOptions.value = false;
    showDeleteModal.value = true;
};

const openNotifications = () => {
    showFabOptions.value = false;
    showNotificationsModal.value = true;
};

const confirmDeleteSite = () => {
    showDeleteModal.value = false;

    router.delete(`/sites/${props.site.data.id}`);
};

/**
 * KPIs agregados a partir dos endpoints do site, usando os campos reais
 * do EndpointResource: `lastest_check.is_successful` e `uptime_percentage`.
 */
const endpointList = computed(() => props.endpoints?.data ?? []);

const totalEndpoints = computed(() => endpointList.value.length);

const onlineCount = computed(
    () =>
        endpointList.value.filter((e) => e.lastest_check?.is_successful === true)
            .length
);

// endpoints sem nenhum check ainda não contam como "offline"
const checkedCount = computed(
    () => endpointList.value.filter((e) => e.lastest_check).length
);

const offlineCount = computed(() => checkedCount.value - onlineCount.value);

const avgUptime = computed(() => {
    const withUptime = endpointList.value.filter(
        (e) => e.uptime_percentage !== null && e.uptime_percentage !== undefined
    );
    if (!withUptime.length) return null;
    const sum = withUptime.reduce((acc, e) => acc + e.uptime_percentage, 0);
    return Math.round(sum / withUptime.length);
});



let pollingInterval = null

const startPolling = () => {
    pollingInterval = setInterval(() => {
        router.reload({
            only: ['endpoints'], // só atualiza endpoints
            preserveScroll: true,
            preserveState: true,
        })
    }, 5000) // 5 segundos
}

const stopPolling = () => {
    if (pollingInterval) {
        clearInterval(pollingInterval)
        pollingInterval = null
    }
}

onMounted(() => {
    startPolling()
})

onUnmounted(() => {
    stopPolling()
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :site="site" :sites="sites" :endpoints="endpoints">
        <div class="space-y-8">
            <template v-if="!site">
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <img
                            :src="'/storage/caixa.gif'"
                            alt="Sem sites"
                            class="w-64 h-64 object-contain mb-6 animate-fade-in"
                        />
                        <h2 class="text-2xl font-bold text-gray-800">
                            Nenhum Endereço criado
                        </h2>
                        <p class="mt-2 text-gray-500 max-w-md">
                            Ainda não tens nenhum Endereço criado. Cria o teu primeiro Endereço para Monitorar.
                        </p>
                    </div>
                </template>

                <template v-else>
                    <!-- ── KPI Cards ── -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

    <!-- Endpoints -->
    <div
        class="group relative overflow-hidden rounded-2xl p-5
        bg-gradient-to-br from-[#243BFF] via-[#495AFF] to-[#7C3AED]
        shadow-[0_10px_35px_rgba(73,90,255,0.35)]
        border border-white/20
        flex flex-col gap-2"
    >
        <!-- Glow -->
        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/20 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-24 h-24 rounded-full bg-[#0ACFFE]/30 blur-3xl"></div>

        <div class="relative flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-white/70">
                Endpoints
            </span>

            <SignalIcon class="w-6 h-6 text-white/80" />
        </div>

        <p class="relative text-3xl font-bold text-white">
            {{ totalEndpoints }}
        </p>

        <p class="relative text-xs text-white/60">
            a monitorar neste site
        </p>
    </div>


    <!-- Uptime -->
    <div
        class="group relative overflow-hidden rounded-2xl p-5
        bg-gradient-to-br from-[#00B4DB] via-[#0ACFFE] to-[#2563EB]
        shadow-[0_10px_35px_rgba(10,207,254,0.30)]
        border border-white/20
        flex flex-col gap-2"
    >
        <!-- Glow -->
        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/25 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-24 h-24 rounded-full bg-[#495AFF]/30 blur-3xl"></div>

        <div class="relative flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-white/70">
                Uptime Médio
            </span>

            <ChartBarIcon class="w-6 h-6 text-white/80" />
        </div>

        <p class="relative text-3xl font-bold text-white">
            {{ avgUptime ?? '—' }}

            <span
                v-if="avgUptime !== null"
                class="text-lg text-white/70 font-medium"
            >
                %
            </span>
        </p>

        <div class="relative h-1.5 bg-black/10 rounded-full overflow-hidden">
            <div
                class="h-full rounded-full bg-white shadow-[0_0_12px_rgba(255,255,255,0.9)] transition-all duration-700"
                :style="`width: ${avgUptime ?? 0}%`"
            ></div>
        </div>
    </div>


    <!-- Online -->
    <div
        class="group relative overflow-hidden rounded-2xl p-5
        bg-gradient-to-br from-[#00B09B] via-[#00C853] to-[#22C55E]
        shadow-[0_10px_35px_rgba(0,200,83,0.30)]
        border border-white/20
        flex flex-col gap-2"
    >
        <!-- Glow -->
        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/20 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-24 h-24 rounded-full bg-emerald-300/30 blur-3xl"></div>

        <div class="relative flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-white/70">
                Online
            </span>

            <CheckCircleIcon class="w-6 h-6 text-white/80" />
        </div>

        <p class="relative text-3xl font-bold text-white">
            {{ onlineCount }}
        </p>

        <p class="relative text-xs text-white/60">
            endpoints a responder bem
        </p>
    </div>


    <!-- Offline -->
    <div
        class="group relative overflow-hidden rounded-2xl p-5
        bg-gradient-to-br from-[#FF416C] via-[#F43F5E] to-[#DC2626]
        shadow-[0_10px_35px_rgba(244,63,94,0.30)]
        border border-white/20
        flex flex-col gap-2"
    >
        <!-- Glow -->
        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/20 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-24 h-24 rounded-full bg-red-300/30 blur-3xl"></div>

        <div class="relative flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-wider text-white/70">
                Offline
            </span>

            <XCircleIcon class="w-6 h-6 text-white/80" />
        </div>

        <p class="relative text-3xl font-bold text-white">
            {{ offlineCount }}
        </p>

        <p class="relative text-xs text-white/60">
            a precisar de atenção
        </p>
    </div>

</div>

                    <!-- ── Novo Endpoint ── -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">
                            Novo Endpoint
                        </p>
                        <form
                            v-on:submit.prevent="storeEndpoint"
                            class="flex items-start gap-2"
                        >
                            <div class="grow">
                                <InputLabel for="location" value="Location" class="sr-only" />
                                <TextInput
                                    id="location"
                                    type="text"
                                    class="block w-full h-9 text-sm"
                                    placeholder="ex: /productos"
                                    v-model="endpointForm.location"
                                />
                                <InputError :message="endpointForm.errors.location" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="frequency" value="Frequency" class="sr-only" />
                                <select
                                    name="frequency"
                                    id="frequency"
                                    class="border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-9 leading-none text-sm"
                                    v-model="endpointForm.frequency"
                                >
                                    <option
                                        :value="frequency.frequency"
                                        v-for="frequency in page.props.endpointFrequencies.data"
                                        :key="frequency.frequency"
                                    >
                                        {{ frequency.label }}
                                    </option>
                                </select>
                            </div>

                            <PrimaryButton>
                                <PlusIcon class="h-4 w-4" color="white" />
                                Adicionar
                            </PrimaryButton>
                        </form>
                    </div>

                    <!-- ── Tabela de Endpoints ── -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <SignalIcon class="w-4 h-4 text-[#495AFF]" />
                                <h2 class="font-semibold text-gray-800 text-sm">Actualmente monitorando</h2>
                                <span class="ml-1 bg-indigo-50 text-[#495AFF] text-xs font-bold px-2 py-0.5 rounded-full">
                                    {{ totalEndpoints }}
                                </span>
                            </div>
                            <span class="text-xs text-gray-400">Actualizando automaticamente</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full table-fixed divide-y divide-gray-100">
                                <thead>
                                    <tr class="bg-gradient-to-r from-[#495AFF] to-[#0ACFFE]">
                                        <th scope="col" class="min-w-[12rem] pl-6 pr-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white/80 sm:pl-6">
                                            Endereço
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white/80">
                                            Frequência
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white/80">
                                            Último check
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white/80">
                                            Último status
                                        </th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white/80">
                                            Uptime
                                        </th>
                                        <th scope="col" class="relative py-3 pl-3 pr-6 text-xs font-semibold uppercase tracking-wider text-white/80">
                                            Acções
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-50 bg-white">
                                    <template v-if="totalEndpoints">
                                        <Endpoint
                                            v-for="endpoint in endpoints.data"
                                            :key="endpoint.id"
                                            :endpoint="endpoint"
                                        />
                                    </template>
                                    <tr v-else>
                                        <td colspan="6" class="py-16 text-center">
                                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                                <SignalIcon class="w-10 h-10 opacity-30" />
                                                <p class="text-sm font-medium">Nenhum endpoint registado ainda</p>
                                                <p class="text-xs text-gray-300">Adiciona um endereço acima para começar a monitorar.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                   <!-- FAB - opções (Notificações / Deletar) -->
<div class="fixed bottom-60 right-20 z-30 flex flex-col items-end gap-3">

    <!-- Opções (aparecem quando showFabOptions = true) -->
    <Transition
        enter-active-class="transition duration-150 ease-out"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-100 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div v-if="showFabOptions" class="flex flex-col items-end gap-3">

            <!-- Notificações -->
            <div class="group flex items-center gap-3">
                <span
                    class="whitespace-nowrap rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-medium text-white
                           opacity-0 group-hover:opacity-100 transition-opacity"
                >
                    Notificações
                </span>
                <button
                    type="button"
                    @click="openNotifications"
                    class="flex h-14 w-14 items-center justify-center rounded-full
                           bg-gradient-to-br from-[#495AFF] to-[#0ACFFE]
                           text-white shadow-[0_8px_25px_rgba(73,90,255,0.35)]
                           border border-white/20
                           transition-all duration-200
                           hover:scale-105 active:scale-95"
                >
                    <BellIcon class="h-6 w-6" />
                </button>
            </div>

            <!-- Deletar site -->
            <div class="group flex items-center gap-3">
                <span
                    class="whitespace-nowrap rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-medium text-white
                           opacity-0 group-hover:opacity-100 transition-opacity"
                >
                    Deletar site
                </span>
                <button
                    type="button"
                    @click="deleteSite"
                    class="flex h-14 w-14 items-center justify-center rounded-full
                           bg-gradient-to-br from-[#FF416C] to-[#DC2626]
                           text-white shadow-[0_8px_25px_rgba(220,38,38,0.35)]
                           border border-white/20
                           transition-all duration-200
                           hover:scale-105 active:scale-95"
                >
                    <TrashIcon class="h-6 w-6" />
                </button>
            </div>
        </div>
    </Transition>

    <!-- Botão principal do FAB -->
    <button
        type="button"
        @click="showFabOptions = !showFabOptions"
        class="flex h-16 w-16 items-center justify-center rounded-full
               bg-gradient-to-br from-[#495AFF] to-[#7C3AED]
               text-white shadow-[0_10px_30px_rgba(73,90,255,0.40)]
               border border-white/20
               transition-all duration-300
               hover:shadow-[0_12px_35px_rgba(73,90,255,0.55)]"
        :class="showFabOptions ? 'rotate-45' : ''"
    >
        <PlusIcon class="h-7 w-7" />
    </button>
</div>
                </template>
            </div>

            <!-- Modal de Notificações -->
<Teleport to="body">
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="showNotificationsModal"
            class="fixed inset-0 z-[100]
                   flex items-center justify-center
                   bg-gray-950/50
                   px-4
                   backdrop-blur-sm"
            @click.self="showNotificationsModal = false"
        >
            <div
                class="w-full max-w-lg
                       overflow-hidden
                       rounded-2xl
                       bg-white
                       shadow-[0_25px_70px_rgba(0,0,0,0.25)]"
            >
                <!-- Header -->
                <div class="relative overflow-hidden
                            bg-gradient-to-r
                            from-[#495AFF]
                            to-[#0ACFFE]
                            px-6 py-5"
                >
                    <div
                        class="absolute -right-10 -top-10
                               h-32 w-32
                               rounded-full
                               bg-white/20
                               blur-3xl"
                    ></div>

                    <div class="relative flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 shrink-0
                                       items-center justify-center
                                       rounded-xl
                                       bg-white/15
                                       border border-white/20
                                       backdrop-blur-md"
                            >
                                <BellIcon class="h-6 w-6 text-white" />
                            </div>

                            <div>
                                <h3 class="text-base font-bold text-white">
                                    Notificações
                                </h3>
                                <p class="mt-0.5 text-xs text-white/75">
                                    Emails avisados quando algo falha
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="showNotificationsModal = false"
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg
                                   text-white/80 hover:bg-white/15 hover:text-white transition"
                        >
                            <XMarkIcon class="h-5 w-5" />
                        </button>
                    </div>
                </div>

                <!-- Conteúdo -->
                <div class="px-6 py-6">
                    <EmailNotifications
                        :site="site"
                        :notification_emails="site.data.notification_emails"
                    />
                </div>
            </div>
        </div>
    </Transition>
</Teleport>

            <!-- Modal de confirmação -->
<Teleport to="body">
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-[100]
                   flex items-center justify-center
                   bg-gray-950/50
                   px-4
                   backdrop-blur-sm"
            @click.self="showDeleteModal = false"
        >
            <div
                class="w-full max-w-md
                       overflow-hidden
                       rounded-2xl
                       bg-white
                       shadow-[0_25px_70px_rgba(0,0,0,0.25)]"
            >

                <!-- Header -->
                <div class="relative overflow-hidden
                            bg-gradient-to-r
                            from-red-500
                            to-rose-600
                            px-6 py-5"
                >
                    <!-- Glow -->
                    <div
                        class="absolute -right-10 -top-10
                               h-32 w-32
                               rounded-full
                               bg-white/20
                               blur-3xl"
                    ></div>

                    <div class="relative flex items-center gap-3">

                        <div
                            class="flex h-11 w-11 shrink-0
                                   items-center justify-center
                                   rounded-xl
                                   bg-white/15
                                   border border-white/20
                                   backdrop-blur-md"
                        >
                            <TrashIcon class="h-6 w-6 text-white" />
                        </div>

                        <div>
                            <h3 class="text-base font-bold text-white">
                                Deletar endereço
                            </h3>

                            <p class="mt-0.5 text-xs text-white/75">
                                Esta ação não pode ser desfeita
                            </p>
                        </div>

                    </div>
                </div>


                <!-- Conteúdo -->
                <div class="px-6 py-6">

                    <div class="flex items-start gap-3">

                        <ExclamationTriangleIcon
                            class="mt-0.5 h-5 w-5 shrink-0 text-red-500"
                        />

                        <div>
                            <p class="text-sm text-gray-600">
                                Tens a certeza que pretendes deletar o
                                endereço:
                            </p>

                            <!-- Domínio destacado -->
                            <div
                                class="mt-3 rounded-xl
                                       border border-red-100
                                       bg-red-50
                                       px-4 py-3"
                            >
                                <p
                                    class="break-all
                                           text-lg font-bold
                                           text-red-600"
                                >
                                    {{ props.site.data.domain }}
                                </p>
                            </div>

                            <p class="mt-3 text-xs text-gray-400">
                                Todos os dados associados a este endereço
                                poderão ser removidos.
                            </p>
                        </div>

                    </div>

                </div>


                <!-- Ações -->
                <div
                    class="flex items-center justify-end gap-3
                           border-t border-gray-100
                           bg-gray-50/70
                           px-6 py-4"
                >

                    <button
                        type="button"
                        @click="showDeleteModal = false"
                        class="rounded-xl
                               px-4 py-2.5
                               text-sm font-semibold
                               text-gray-600
                               transition
                               hover:bg-white
                               hover:text-gray-800"
                    >
                        Cancelar
                    </button>

                    <button
                        type="button"
                        @click="confirmDeleteSite"
                        class="flex items-center gap-2
                               rounded-xl
                               bg-gradient-to-r
                               from-red-500
                               to-rose-600
                               px-4 py-2.5
                               text-sm font-semibold
                               text-white
                               shadow-[0_6px_18px_rgba(239,68,68,0.30)]
                               transition
                               hover:scale-[1.02]
                               hover:shadow-[0_8px_22px_rgba(239,68,68,0.40)]
                               active:scale-95"
                    >
                        <TrashIcon class="h-4 w-4" />
                        Deletar endereço
                    </button>

                </div>

            </div>
        </div>
    </Transition>
</Teleport>
    </AuthenticatedLayout>


</template>