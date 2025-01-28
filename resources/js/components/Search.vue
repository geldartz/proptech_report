<template>
    <div>
        <form @submit.prevent="search()" name="search" class="ring-0">
            <label for="default-search"
                class="mb-2 text-sm font-medium text-darkgray sr-only dark:text-darkmode-444">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-darkmode-555" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" :id="name" v-model="searchText" :placeholder="placeholder" autofocus @input="onInputChange($event.target.value)"
                    class="block w-full px-4 py-2 pl-10 text-sm text-darkgray border border-gray-300 rounded-lg bg-gray-50  dark:bg-darkmode-800 dark:border-slate-700 dark:placeholder-gray-400 dark:text-white ">
                <button type="submit"
                    class="text-white bg-[#2465C7] absolute right-[3px] bottom-[3px] bg-[#2465C7 ] hover:bg-blue-800  font-medium rounded-r-md text-sm px-4 py-1.5 dark:bg-darkmode-600 dark:hover:bg-darkmode-500">
                    {{ label }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const emits = defineEmits(['search']);
const props = defineProps({
    name: {
        type: String,
        default: "",
    },
    icon: {
        type: String,
        default: "",
    },
    modelValue: {
        type: [String, Number],
    },
    placeholder: {
        type: String,
        default: null,
    },
    label: {
        type: String,
        default: 'Search',
    }
})

const searchText = ref('');

const search = () => {
    emits("search", searchText.value);
}

const onInputChange = (value) => {
    if(value == '') {
        emits("search", searchText.value);
    }
}

</script>
