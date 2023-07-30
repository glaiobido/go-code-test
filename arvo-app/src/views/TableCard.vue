<script setup>
import { defineProps } from 'vue';
import { useCustomersStore } from '@/stores/customers'

import CsvTable from '../components/CsvTable.vue';
import ParsedDataTable from '../components/ParsedDataTable.vue';

const customersStore = useCustomersStore();

const props = defineProps(['title']);

</script>
<template>
    <div class="card w-full bg-base-100 shadow-xl overflow-x-auto overflow-y-auto">
        <div class="card-body items-center text-center">
            <h2 class="card-title">{{ props.title.value }}</h2>
            <div class="max-h-80" v-if="props.title.value=='Uploaded Data'">
                <CsvTable />
            </div>
            <div class="max-h-80" v-if="props.title.value=='Parsed Data'">
                
                <download-csv
                    :data   = "customersStore.parsedDataList"
                    name    = "ParsedData.csv">
                    <button class="btn btn-link text-accent">Download</button>
                </download-csv>	
                <ParsedDataTable />
            </div>
        </div>
    </div>
</template>