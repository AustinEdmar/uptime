1 - 
<script setup>

    import { useForm } from '@inertiajs/vue3';
    import TextInput from './TextInput.vue';

    import { ref } from 'vue';

    const editing = ref(false);

2 - 
 <td class="whitespace-nowrap pl-4 sm:pl-6 px-3 text-sm font-medium text-gray-900 w-64">

            <template v-if="editing">
                <TextInput v-model="endpoint.location" id="location" type="text" class="block w-full h-9 text-sm" />
            </template>
            <template v-else>
                <a href="/" class="text-indigo-600 hover:text-indigo-900">
                   {{ endpoint.location }}
                </a>
            </template>
        </td>

/* 3 final */

<template>
    <tr>
        <td class="whitespace-nowrap pl-4 sm:pl-6 px-3 text-sm font-medium text-gray-900 w-64">
          {/* 1 */}
            <template v-if="editing">
                <TextInput v-model="endpoint.location" id="location" type="text" class="block w-full h-9 text-sm" />
            </template>
            <template v-else>
                <a href="/" class="text-indigo-600 hover:text-indigo-900">
                   {{ endpoint.location }}
                </a>
            </template>
        </td>
        <td class="whitespace-nowrap px-3 text-sm text-gray-500 w-64">
            <template v-if="editing">
                {/* 2 */}
                   <select name="frequency" id="frequency" 
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-9 leading-none text-sm"
                       >
                            <option :value="frequency.frequency" v-for="frequency in page.props.endpointFrequencies.data" :key="frequency.frequency">
                                {{ frequency.label }}
                            </option> 
                            
                        </select>
            </template>
            <template v-else>
                <!-- o que ja esta la -->
                {{ endpoint.frequency_label }}
            </template>
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            Last check
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            Status
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            x%
        </td>
        <td class="whitespace-nowrap pl-3 pr-4 text-right text-sm font-medium sm:pr-6 w-32">

            {/* 1.3 */}
            <button class="text-indigo-600 hover:text-indigo-900" @click="editing = !editing">
                Edit
            </button>
        </td>
        <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 w-16">
            <button @click="deleteEndpoint" class="text-red-600 hover:text-red-900">
                Delete
            </button>

        </td>


        
        
    </tr>
</template>


<script setup>

    import { useForm } from '@inertiajs/vue3';
    import TextInput from './TextInput.vue';
    {/* 3 */}
    import { usePage } from '@inertiajs/vue3';
 
    import { ref } from 'vue';

    {/* 4  esta vindo do middleware share*/}
    const page = usePage();
{/* 1.1 */}
    const editing = ref(false);

    const props = defineProps({
        endpoint: Object,
        message: String,
    });


    const deleteForm = useForm({
        endpoint_id: props.endpoint.id,
    });


    const deleteEndpoint = () => {
        if (confirm('Tem certeza que deseja deletar este endpoint?')) {
            deleteForm.delete(`/endpoints/${props.endpoint.id}`);
        }
    }

</script>