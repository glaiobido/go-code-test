<script setup>
import { useCustomersStore } from '@/stores/customers'

const customersStore = useCustomersStore();

const tableHeaders = [
    'Customer #',
    'First Name',
    'Email',
    'Phone',
    'Transaction Date',
    'Transaction Time',
    'Transaction Type'
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
            <tr class="hover" v-for="customer in customersStore.customerList">
                <th>{{ customer.cust_num }}</th>
                <td>{{ customer.cust_fname }}</td>
                <td>{{ customer.cust_email }}</td>
                <td>{{ customer.cust_phone }}</td>
                <td>{{ customer.trans_date }}</td>
                <td>{{ customer.trans_time }}</td>
                <td>{{ customer.trans_type }}</td>
            </tr>
        </tbody>
    </table>
</template>