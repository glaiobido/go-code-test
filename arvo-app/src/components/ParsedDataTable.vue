<script setup>
import { useCustomersStore } from '@/stores/customers'

const customersStore = useCustomersStore();

const tableHeaders = [
    'Customer #',
    'Customer',
    'Email',
    'Phone',
    'Trans Date',
    'Trans Time',
    'Type',
    'Notified',
    'Notified Via',
    'Error Message',
]


</script>
<template>
    <table class="table">
        <!-- head -->
        <thead>
            <tr>
               <th v-for="header in tableHeaders">{{ header }}</th>
            </tr>
        </thead>
        <tbody>
            <tr class="hover" v-for="customer in customersStore.parsedDataList">
                <th>{{ customer.cust_num }}</th>
                <td>{{ customer.cust_fname }}</td>
                <td>{{ customer.cust_email }}</td>
                <td>{{ customer.cust_phone }}</td>
                <td>{{ customer.trans_date }}</td>
                <td>{{ customer.trans_time }}</td>
                <td>{{ customer.trans_type }}</td>
                <td>{{ customer.notify ? 'Yes' : 'No' }}</td>
                <td>{{ customer.via }}</td>
                <td>{{ customer.error_message || 'N/A' }}</td>
            </tr>
        </tbody>
    </table>
</template>