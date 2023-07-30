import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios';
import VueAxios from 'vue-axios'
import JsonCSV from 'vue-json-csv';

import App from './App.vue'
import router from './router'


// Axious Base Url
axios.defaults.baseURL = 'http://arvo-api.test/';

const app = createApp(App)
app.component('downloadCsv', JsonCSV);
app.use(createPinia())
app.use(router)
app.use(VueAxios, axios);
app.provide('axios', app.config.globalProperties.axios);

app.mount('#app')
