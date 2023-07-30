<script setup>
import { onMounted, ref, computed } from 'vue';
import TableCard from './views/TableCard.vue';
import axios from 'axios';
import { useCustomersStore } from '@/stores/customers' // Pinia Store


const fileInputRef = ref(null);
const hideTable = ref(true);
const customersStore = useCustomersStore();
const tableTitle = ref({value: 'Uploaded Data'});

const addHiddenClass = computed(() => ({
  'hidden': hideTable.value,
}))

onMounted(() => {
	axios.get('/sanctum/csrf-cookie'); 
});


const onAttach = async (event) => {
	
	if (event.target.files) {
		const formData = new FormData();
		formData.append('csv_file', event.target.files[0]);

		    await axios.post('api/customers', formData, {
				headers: {
					'Content-Type': 'multipart/form-data' // Important! Don't forget this header
				},
				}).then(response => {
					let { uploaded_data, parsed_data } = response.data;
					// mutate changes to customer store
					customersStore.$patch({customerList: uploaded_data });
					customersStore.$patch({parsedDataList: parsed_data });
					customersStore.$patch({errorMessage: '' });

					hideTable.value = false;
					fileInputRef.value.value = '';
			}).catch(err => {
				if (err.response.status == 422) {
					let { message } = err.response.data;
					customersStore.$patch({errorMessage: message });	
				}
				hideTable.value = true;
			});
	}
}

</script>

<template>
	<div id="main" class="relative">
		<div class="hero w-full min-h-screen bg-base-200">
			<div class="hero-content text-center flex-col">
				<div class="w-52 rounded-full ring-offset-base-100">
					<img src="./assets/icons/Jobs-11.png" /> </div>
				<h1 class="text-5xl font-bold py-3">Arvo CSV Parser</h1>

				<div class="card w-fit h-1/2 bg-base-100 shadow-xl">
					<!-- IMAGE HERE -->
					<div class="card-body text-center flex justify-center">
						
						<div class="alert alert-error w-full" v-if="customersStore.errorMessage!=''">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
							<span class="text-center">{{ customersStore.errorMessage  }}</span>
						</div>
						
						<h2 class="text-center">Upload CSV File Here</h2>
						<div class="flex justify-center">
							<form @submit.prevent>    
								<div class="form-control w-full max-w-xs">
									<input type="file" ref="fileInputRef" @change="onAttach" class="file-input file-input-bordered max-w-xs" />
								</div>
							</form>
						</div>
						<div class="" :class="addHiddenClass">
							<div class="py-4">
								<ul class="menu menu-vertical lg:menu-horizontal bg-base-200 rounded-box">
									<li><a @click="tableTitle.value='Uploaded Data'">Uploaded Data</a></li>
									<li><a @click="tableTitle.value='Parsed Data'">Parsed Data</a></li>
								</ul>
							</div>
							
							<TableCard :class="addHiddenClass" :title="tableTitle" />
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>
</template>

<style scoped>

</style>
