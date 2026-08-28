<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },

    width: {
        type: String,
        default: '48',
    },

    placement: {
        type: String,
        default: 'bottom',
    },

    contentClasses: {
        type: String,
        default: 'py-1 bg-white',
    },
});

const open = ref(false);

const close = () => {
    open.value = false;
};

const toggle = () => {
    open.value = !open.value;
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape') {
        close();
    }
};

onMounted(() => {
    document.addEventListener('keydown', closeOnEscape);
});

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
});

const widthClass = computed(() => {
    return {
        48: 'w-48',
        56: 'w-56',
    }[props.width.toString()];
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'ltr:origin-top-left rtl:origin-top-right start-0';
    }

    if (props.align === 'right') {
        return 'ltr:origin-top-right rtl:origin-top-left end-0';
    }

    return 'origin-top';
});

const placementClasses = computed(() => {
    if (props.placement === 'top') {
        return 'bottom-full mb-2';
    }

    return 'mt-2';
});
</script>

<template>
    <div class="relative">
        <!-- Trigger -->
        <div @click="toggle">
            <slot name="trigger" />
        </div>

        <!-- Overlay -->
        <div
            v-if="open"
            class="fixed inset-0 z-40"
            @click="close"
        ></div>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="open"
                class="absolute z-50 rounded-md shadow-lg"
                :class="[widthClass, alignmentClasses, placementClasses]"
            >
                <div
                    class="rounded-md ring-1 ring-black ring-opacity-5"
                    :class="contentClasses"
                >
                    <slot name="content" />
                </div>
            </div>
        </Transition>
    </div>
</template>