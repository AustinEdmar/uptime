1 - mostrando os endpoints
import Endpoint from '../Components/Endpoint.vue';

 <div class="mt-8 flex flex-col">
                    <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                        Currently monitoring (0)
                    </h2>
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8 mt-3">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="relative overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full table-fixed divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="min-w-[12rem] pl-4 py-3.5 px-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Location
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Frequency
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Last check
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Last status
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Uptime
                                            </th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">Delete</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <Endpoint/>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


<!-- 2 - endpoint dashboard-->

 docker-compose run --rm artisan make:resource EndpointResource

 return Inertia::render('Dashboard', [
            'site' => SiteResource::make($site),
            'sites' => SiteResource::collection(Site::get()),
            'endpoints' => EndpointResource::collection($site->endpoints()->get()),
        ]);


<!--   public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'location' => $this->location,
            'frequency_label' => EndpointFrequency::from($this->frequency)->label(),
            
        ];
    }-->

    

<!-- 3 - dashboard.vue -->

const props = defineProps({
    site: Object,
    sites: Object,
    endpoints: Object, aqui
    
});


coloco no 
<tbody class="divide-y divide-gray-200 bg-white">
                                        <Endpoint v-for="endpoint in endpoints.data" :key="endpoint.id" :endpoint="endpoint"/>
                                    </tbody>

<!-- 2 endpoint.vue receber props -->

<template>
    <tr>
        <td class="whitespace-nowrap pl-4 sm:pl-6 px-3 text-sm font-medium text-gray-900 w-64">
            <a href="/" class="text-indigo-600 hover:text-indigo-900">
               {{ endpoint.id }}
            </a>
        </td>
        <td class="whitespace-nowrap px-3 text-sm text-gray-500 w-64">
            {{ endpoint.frequency_label }}
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
            <button class="text-indigo-600 hover:text-indigo-900">
                Edit
            </button>
        </td>
        <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 w-16">
            <button class="text-red-600 hover:text-red-900">
                Delete
            </button>
        </td>
    </tr>
</template>


<script setup>

    const props = defineProps({
        endpoint: Object,
    });

</script>









