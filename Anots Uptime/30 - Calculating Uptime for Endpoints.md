1 - vamos na model Endpoint
  public function uptimePercentage()
    {
        return (10 / $this->checks()->count()) * 100;
       
    }

2 / na model site add no endpoints o withCount

 public function endpoints()
    {
        return $this->hasMany(Endpoint::class)
        ->withCount(['checks as successful_checks_count' => function ($query) {
            $query->where('response_code', '>=', 200)->where('response_code', '<', 300);
        }])
        ->latest();
    }

<!-- no resource endpointresource -->
'successful_checks_count' => $this->successful_checks_count,

<!-- 3 / Endpoint.vue --> ele mostra o valor
 {{ endpoint.successful_checks_count }}


 <!-- vou <!-- no resource endpointresource apago -->
'successful_checks_count' => $this->successful_checks_count, -->


<!-- 4 no no Model Endpoint -->
public function uptimePercentage()
    {
        if(!$this->checks->count()){
            return null;
        }
        
        return number_format(($this->successful_checks_count / $this->checks()->count()) * 100, 2);
       
    }

<!-- 5 / EndpointResource -->
'uptime_percentage' => $this->uptimePercentage(),


<!-- 6 - agora vamos no template colocar as percentagens -->
<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
           <template v-if="endpoint.uptime_percentage">
            {{ endpoint.uptime_percentage }}%
           </template>
           <template v-else>
            -
           </template>
        </td>











