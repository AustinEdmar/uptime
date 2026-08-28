<template>
    <div
        class="relative overflow-hidden
               rounded-2xl
               border border-gray-100
               bg-white
               shadow-sm"
    >
        <!-- Glow decorativo -->
        <div
            class="absolute -top-20 -right-20
                   h-40 w-40
                   rounded-full
                   bg-[#495AFF]/5
                   blur-3xl
                   pointer-events-none"
        ></div>

        <div class="relative p-5">

            <!-- Header -->
            <div class="flex items-start justify-between gap-4">

                <div class="flex items-center gap-3">

                    <!-- Icon -->
                    <div
                        class="flex h-10 w-10 shrink-0
                               items-center justify-center
                               rounded-xl
                               bg-gradient-to-r
                               from-[#495AFF]
                               to-[#0ACFFE]
                               shadow-[0_5px_15px_rgba(73,90,255,0.25)]"
                    >
                        <EnvelopeIcon class="h-5 w-5 text-white" />
                    </div>

                    <div>
                        <h2 class="text-sm font-bold text-gray-800">
                            Notificações por Email
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-400">
                            Receba alertas quando um endpoint ficar offline.
                        </p>
                    </div>

                </div>

                <!-- Contador -->
                <span
                    class="shrink-0 rounded-full
                           bg-indigo-50
                           px-2.5 py-1
                           text-xs font-bold
                           text-[#495AFF]"
                >
                    {{ notification_emails?.length ?? 0 }}
                </span>

            </div>


            <!-- Form -->
            <form
                @submit.prevent="
                    form.post(
                        `/sites/${site.data.id}/notifications/emails`,
                        {
                            preserveScroll: true,
                            onSuccess: () => form.reset(),
                        }
                    )
                "
                class="mt-5"
            >

                <div class="flex items-start gap-2">

                    <!-- Email -->
                    <div class="min-w-0 flex-1">

                        <InputLabel
                            for="email"
                            value="Email"
                            class="sr-only"
                        />

                        <div class="relative">

                            <EnvelopeIcon
                                class="pointer-events-none
                                       absolute left-3 top-1/2
                                       h-4 w-4
                                       -translate-y-1/2
                                       text-gray-400"
                            />

                            <TextInput
                                id="email"
                                type="email"
                                class="block w-full h-10
                                       pl-9 text-sm
                                       rounded-xl
                                       border-gray-200
                                       focus:border-[#495AFF]
                                       focus:ring-[#495AFF]/20"
                                placeholder="ex: austin@gmail.com"
                                v-model="form.email"
                            />

                        </div>

                        <InputError
                            :message="form.errors.email"
                            class="mt-2"
                        />

                    </div>


                    <!-- Add -->
                    <PrimaryButton
                        type="submit"
                        :disabled="form.processing"
                        class="h-10 shrink-0
                               rounded-xl
                               bg-gradient-to-r
                               from-[#495AFF]
                               to-[#0ACFFE]
                               shadow-[0_5px_15px_rgba(73,90,255,0.20)]
                               transition-all
                               hover:scale-[1.02]
                               hover:shadow-[0_7px_20px_rgba(73,90,255,0.30)]
                               disabled:cursor-not-allowed
                               disabled:opacity-60"
                    >
                        <PlusIcon
                            v-if="!form.processing"
                            class="mr-1.5 h-4 w-4"
                        />

                        <span v-if="form.processing">
                            Adicionando...
                        </span>

                        <span v-else>
                            Adicionar
                        </span>
                    </PrimaryButton>

                </div>

            </form>


            <!-- Emails -->
            <div class="mt-6">

                <div
                    v-if="notification_emails?.length"
                    class="space-y-2"
                >

                    <div
                        v-for="email in notification_emails"
                        :key="email"
                        class="group flex items-center
                               justify-between gap-3
                               rounded-xl
                               border border-gray-100
                               bg-gray-50/70
                               px-3 py-2.5
                               transition-all
                               hover:border-indigo-100
                               hover:bg-indigo-50/40"
                    >

                        <div class="flex min-w-0 items-center gap-3">

                            <!-- Email icon -->
                            <div
                                class="flex h-8 w-8 shrink-0
                                       items-center justify-center
                                       rounded-lg
                                       bg-white
                                       border border-gray-100
                                       shadow-sm"
                            >
                                <EnvelopeIcon
                                    class="h-4 w-4 text-[#495AFF]"
                                />
                            </div>

                            <span
                                class="truncate text-sm
                                       font-medium text-gray-700"
                            >
                                {{ email }}
                            </span>

                        </div>


                        <!-- Delete -->
                        <button
                            type="button"
                            @click="
                                router.delete(
                                    `/sites/${site.data.id}/notifications/emails`,
                                    {
                                        data: { email },
                                        preserveScroll: true,
                                    }
                                )
                            "
                            class="flex h-8 w-8 shrink-0
                                   items-center justify-center
                                   rounded-lg
                                   text-gray-400
                                   transition-all
                                   hover:bg-red-50
                                   hover:text-red-500
                                   hover:scale-105"
                            title="Remover email"
                        >
                            <TrashIcon class="h-4 w-4" />
                        </button>

                    </div>

                </div>


                <!-- Empty -->
                <div
                    v-else
                    class="flex flex-col items-center
                           justify-center
                           rounded-xl
                           border border-dashed
                           border-gray-200
                           bg-gray-50/50
                           px-4 py-7
                           text-center"
                >

                    <div
                        class="flex h-10 w-10
                               items-center justify-center
                               rounded-xl
                               bg-indigo-50"
                    >
                        <EnvelopeIcon
                            class="h-5 w-5 text-[#495AFF]"
                        />
                    </div>

                    <p
                        class="mt-3 text-sm
                               font-medium text-gray-600"
                    >
                        Nenhum email configurado
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Adicione um email para receber notificações.
                    </p>

                </div>

            </div>

        </div>
    </div>
</template>


<script setup>
import TextInput from '../Components/TextInput.vue';
import InputLabel from '../Components/InputLabel.vue';
import InputError from '../Components/InputError.vue';
import PrimaryButton from '../Components/PrimaryButton.vue';

import { useForm, router } from '@inertiajs/vue3';

import {
    PlusIcon,
    EnvelopeIcon,
    TrashIcon,
} from '@heroicons/vue/24/outline';


const props = defineProps({
    site: Object,
    notification_emails: {
        type: Array,
        default: () => [],
    },
});


const form = useForm({
    email: '',
});
</script>