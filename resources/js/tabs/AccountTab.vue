<template>
    <div class="h-full px-4">
        <Table :url="'/api/accounts'" :headers="tableHeaders" :key="tableKey" :searchKey="searchTerm" :hasThousandRecords="true">
            <template #customButtons>
                <DropdownButton :buttons="buttonsOR"></DropdownButton>
                <v-select  class="text-sm w-96 font-thin v-select-style" :options="project_sites" @option:selected="filterProjectSite"  label="title" placeholder="Project Site"></v-select>
                <!-- <v-select  class="text-sm w-60 font-thin v-select-style" :options="status" @option:selected="filterPeriod"  label="title" placeholder="Status"></v-select> -->
            </template>
        </Table>
    </div>
</template>


<script setup>
 import { onMounted, reactive, ref, watch, computed, provide, inject, onUpdated, nextTick  } from "vue";
 import Table from '@/components/Table.vue';
 import DropdownButton from '@/components/DropdownButton.vue';

 const buttonsOR = reactive([
   { label: 'Export', key: 'button', function: exportData },
]);

const tableHeaders = reactive([
    
   { title: 'PROJECT NAME', onSet: true, sortable: true, query: 'name', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'left' },
   { title: 'CUSTOMER NAME', onSet: true, sortable: true, query: 'customer_name', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'left' },
   { title: 'LOT AREA', onSet: true, sortable: true, query: 'lot_area', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'left' },
   { title: 'FLOOR AREA', onSet: true, sortable: true, query: 'floor_area', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'left' },
   { title: 'FIRST BILL PERIOD', onSet: true, sortable: true, query: 'first_bill_period', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'left' },
   { title: 'ACCOUNT NUMBER', onSet: true, sortable: true, query: 'account_number', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'left' },
   { title: 'TURN-OVER DATE', onSet: true, sortable: true, query: 'turned_over_date', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'left' },
]);

const searchTerm = ref('')
const project_sites = ref([])
const tableKey = ref(0)

function exportData(){

}

function filterProjectSite(search){
    searchTerm.value = search.id
    
}
async function fetchProjectSites(){
    await axios.get('/api/project-sites').then((response) => {
        project_sites.value = response.data.data
    })
}

onMounted(() => {
    fetchProjectSites();
})

</script>
<style>
.v-select-style .vs__search::placeholder,
.v-select-style .vs__dropdown-toggle {
  background: #fff;
  border: none;
  color: #fff;
  /* text-transform: lowercase;*/ 
  
  font-size: 14px;
  padding: 2px 0;
  border:1px solid #2a60b8;
  background: #2a60b8;
}
.v-select-style .vs__selected{
    color: #fff;
}
.v-select-style .vs__clear,
.v-select-style .vs__open-indicator {
  fill: #fff;

}

</style>