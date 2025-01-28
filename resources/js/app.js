import './bootstrap';

import { createApp } from 'vue';
import Shimmer from 'vue3-shimmer';
import moshaToast from "mosha-vue-toastify";
import "vue-select/dist/vue-select.css";
import vSelect from 'vue-select';

import App from './App.vue';

const app = createApp(App);
app.use(moshaToast);
app.use(Shimmer);
app.component("v-select", vSelect);
app.mount('#app');