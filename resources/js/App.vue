<template>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-4 rounded-lg border-t border-blue-800 my-5 shadow-md">
        <TabsComponent @click="switchTab" :tabData="tabData" >
            <template  v-if="tabIndex == 0" #highlight>
                <HighLightTab></HighLightTab>
            </template>
            <template v-if="tabIndex == 1"  #accounts>
                <AccountTab></AccountTab>
            </template>
            <template  v-if="tabIndex == 2" #colloff>
                <CollEffTab></CollEffTab>
            </template>

        </TabsComponent>
    </div>
    </div>
  </template>
  
  <script setup>
    import { onMounted, reactive, ref, watch, computed, provide, inject, onUpdated, nextTick  } from "vue";
    import { PlusIcon, MinusIcon, PencilSquareIcon, Square3Stack3DIcon, EyeIcon, EyeSlashIcon, ListBulletIcon, UserGroupIcon, UserCircleIcon, PercentBadgeIcon } from '@heroicons/vue/24/outline'
    import TabsComponent from '@/components/Tab.vue';

    import HighLightTab from '@/tabs/HighLightTab.vue';
    import AccountTab from '@/tabs/AccountTab.vue';
    import CollEffTab from '@/tabs/CollEffTab.vue';
    
    const tabIndex = ref(0)
    const tabData = ref([
        { name: 'highlight', label: 'PM Highlights', icon: UserCircleIcon, disabled: false, show: true },
        { name: 'accounts', label: 'Accounts', icon: UserGroupIcon, disabled: false, show: true }, 
        { name: 'colloff', label: 'Collection Efficiency', icon: PercentBadgeIcon, disabled: false, show: true },
    ])

    function switchTab(index){
        tabIndex.value = index
    }

  </script>
  