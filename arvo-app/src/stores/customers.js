import { defineStore } from 'pinia';

export const useCustomersStore = defineStore('customers', {
    state: () => {
        return {
            customerList: [],
            parsedDataList: [],
            errorMessage: ""
        }
    },
    actions: {
        setCustomers(newCustomerArray) {
            this.customerList = newCustomerArray
        }
    }
});