<template>
    <tr>

        {{ endpoint.successful_checks_count }}
        <td class="whitespace-nowrap pl-4 sm:pl-6 px-3 text-sm font-medium text-gray-900 w-64">

            <template v-if="editing">
                <TextInput v-model="endpointForm.location" id="location" type="text" class="block w-full h-9 text-sm" />
            </template>
            <template v-else>
                <Link :href="`/endpoints/${endpoint.id}`" class="text-indigo-600 hover:text-indigo-900">
                   {{ endpoint.location }}
                </Link>
            </template>
        </td>
        <td class="whitespace-nowrap px-3 text-sm text-gray-500 w-64">
            <template v-if="editing">
                 <!-- o select -->
                   <select name="frequency" id="frequency" 
                        class="border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-9 leading-none text-sm"
                        v-model="endpointForm.frequency"
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
           <template v-if="endpoint.lastest_check">
            <time :datetime="endpoint.lastest_check.created_at.date_time" :title="endpoint.lastest_check.created_at.date_time">
                 {{ endpoint.lastest_check.created_at.human }} 
            </time>
           </template> 
           <template v-else>
             -
           </template>
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            <template v-if="endpoint.lastest_check">
                <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-sm font-medium " :class="{
                    'bg-green-100 text-green-800': endpoint.lastest_check.is_successful,
                    'bg-red-100 text-red-800': !endpoint.lastest_check.is_successful,
                }">
                    {{ endpoint.lastest_check.response_code }} - {{ endpoint.lastest_check.status_text }}
                </span> 
            </template>
            <template v-else>
                -
            </template>
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
           <template v-if="endpoint.uptime_percentage">
            {{ endpoint.uptime_percentage }}%
           </template>
           <template v-else>
            -
           </template>
        </td>
       
        <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 w-16">
            <div class="justify-space-between">
                 <button class="text-indigo-600 hover:text-indigo-900 px-4" @click="editing = !editing">
                <!-- {{ editing ? 'Salvar' : 'Editar' }} -->
                 <PencilSquareIcon v-if="!editing" class="h-6 w-6 text-yellow-500" />
         <CheckCircleIcon v-else class="h-6 w-6 text-green-600" />

                
            </button>
                <button @click="deleteEndpoint" class="text-red-600 hover:text-red-900">
             
                    <TrashIcon class="h-6 w-6 text-red-500" color="red" />
                </button>
            </div>

        </td>


        
        
    </tr>
</template>


<script setup>

    import { Link, useForm,  } from '@inertiajs/vue3';
    import TextInput from './TextInput.vue';
    import { usePage } from '@inertiajs/vue3';
    import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
    import { CheckCircleIcon } from '@heroicons/vue/24/solid'
    import { ref, watch } from 'vue';
    import debounce from 'lodash.debounce';


    
    const page = usePage();

    const editing = ref(false);

    const props = defineProps({
        endpoint: Object,
        message: String,
    });


    const endpointForm = useForm({
        location: props.endpoint.location,
        frequency: props.endpoint.frequency_value, //aqui precisamos no endpointresource incluir a frequency
    });


    const deleteForm = useForm({
        endpoint_id: props.endpoint.id,
    });


    const deleteEndpoint = () => {
        if (confirm('Tem certeza que deseja deletar este endpoint?')) {
            deleteForm.delete(`/endpoints/${props.endpoint.id}`);
        }
    }


    const editEndpoint = debounce(() => {
        endpointForm.patch(`/endpoints/${props.endpoint.id}`, {
            preserveScroll: true,
        });
    }, 1000);



   //dirty 
   watch(() => endpointForm.isDirty, () => {
    
      editEndpoint();
        editing = false;
    });

</script>