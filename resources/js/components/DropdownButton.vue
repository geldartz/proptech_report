<template>
    <Listbox as="div" v-model="selected">
      <ListboxLabel class="sr-only">Change action</ListboxLabel>
      <div class="relative">
        <div class="inline-flex divide-x divide-blue-700 dark:divide-gray-600 rounded-md shadow-sm">
          <div class="inline-flex items-center border-bluer gap-x-1.5 rounded-l-md bg-[#2465C7] px-3 py-2 text-white shadow-sm dark:bg-darkmode-700 border dark:border-gray-600">
 
            <p class="text-sm font-normal px-6">Action</p>
          </div>
          <ListboxButton class="inline-flex items-center rounded-l-none rounded-r-md bg-[#2465C7] px-2 hover:bg-blue-700 dark:hover:bg-darkmode-700
          dark:bg-darkmode-500  dark:focus:ring-gray-600" @click="rotate">
            <span class="sr-only">Change published status</span>
            <ChevronDownIcon :class="{'rotate-180' : rotateIcon}"  class="h-5 w-5 transform transition-transform duration-300 ease-in-out text-white" aria-hidden="true" />
          </ListboxButton>
        </div>
  
        <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
          <ListboxOptions class="absolute left-0 z-20 mt-2 w-48 origin-top-right divide-y divide-gray-200 dark:divide-gray-600 overflow-hidden rounded-md bg-white shadow-lg ring-1 ring-blue-800 ring-opacity-5 focus:outline-none dark:bg-darkmode-700 border border-blue-800 dark:border-gray-600">
            <ListboxOption as="template" v-for="option in buttons" :key="option.title" :value="option" v-slot="{ active, selected }">
              <li :class="[active ? 'bg-[#2465C7] text-white dark:text-darkmode-444' : 'text-gray-900 dark:text-darkmode-444', 'cursor-pointer select-none dark:hover:bg-darkmode-500']">
                <download-excel class="w-full h-full block py-2 px-4 text-sm" v-if="option.key == 'export_button'" :data="json_data">
                  {{ option.label }}
                </download-excel>
                <router-link v-else-if="option.key == 'link'" :to="option.link" class="w-full h-full block py-2 px-4 text-sm">{{ option.label }}</router-link>
                <a v-else class="w-full h-full block py-2 px-4 text-sm" href="#" @click.prevent="option.function">{{ option.label }}</a>
              </li>
            </ListboxOption>
          </ListboxOptions>
        </transition>
      </div>
    </Listbox>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue'
  import { CheckIcon, ChevronDownIcon } from '@heroicons/vue/20/solid'
  
  const props = defineProps({
    buttons: {
        type: Array,
        required: true,
    },
    json_data:{
      type: [Array, Object],
        required: false,
    },
});
const rotateIcon = ref(false)

function rotate(){
    rotateIcon.value = !rotateIcon.value
}

  </script>