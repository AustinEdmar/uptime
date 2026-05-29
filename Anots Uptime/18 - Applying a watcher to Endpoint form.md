1 - preencher o form dashboard.vue

 const endpointForm = useForm({
        location: null,
        frequency: null,
    });

    <!-- no template -->
 <template v-if="editing">
 ################### aqui
                <TextInput v-model="endpointForm.location" id="location" type="text" class="block w-full h-9 text-sm" />
            </template>
            <template v-else>
                <a href="/" class="text-indigo-600 hover:text-indigo-900">
                   {{ endpoint.location }}
                </a>
            </template>
 <template v-if="editing">
                 <!-- o select -->
                   <select name="frequency" id="frequency" 
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-9 leading-none text-sm"
                        v-model="endpointForm.frequency" ############# aqui
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


<!-- 2  -->

 const endpointForm = useForm({
        location: props.endpoint.location,
        frequency: null, //aqui precisamos no endpointresource incluir a frequency
    });


<!-- 2.1 -->

  public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'location' => $this->location,
            'frequency_label' => EndpointFrequency::from($this->frequency)->label(),

            'frequency_value' => EndpointFrequency::from($this->frequency)->value,
            
        ];
    }
}

<!-- 3 -->

 const endpointForm = useForm({
        location: props.endpoint.location,
        frequency: props.endpoint.frequency_value, //aqui precisamos no endpointresource incluir a frequency
    });


<!-- 4 vamos criar o watcher -->

o watcher vai ser ativado sempre que o endpointForm mudar, ele dispara a cada letra que digita, por vou 
usar o lodash debounce 

 watch(endpointForm, () => {
        console.log("enviando a api");
    });
<!-- docker-compose run --rm npm i lodash.debounce --force -->

 import debounce from 'lodash.debounce';


  watch(endpointForm, debounce(() => {
        console.log("enviando a api");
    }, 500));