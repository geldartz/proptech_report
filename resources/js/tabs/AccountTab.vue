<template>
    <div class="h-full px-4">
        <Table :url="'/api/accounts'" :headers="tableHeaders" :key="tableKey" :searchKey="searchTerm" :hasThousandRecords="true">
            <template #customButtons>
                <DropdownButton :buttons="buttonsOR"></DropdownButton>
                <v-select  class="text-sm w-96 font-thin v-select-style" :options="project_sites" @option:selected="filterProjectSite"  label="title" placeholder="Project Site"></v-select>
                <v-select v-model="selectedMonth" class="text-sm w-44 font-thin v-select-style" :options="months" @option:selected="filterPerMonth"  label="title" placeholder="Filter per Month"></v-select>
                <v-select v-model="selectedYear" class="text-sm w-44 font-thin v-select-style" :options="years" @option:selected="filterPerYear"  label="title" placeholder="Select Year"></v-select>
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
   { title: 'MEMBERSHIP FEE(PFTM)', onSet: true, sortable: true, query: 'pftm_membership_fee', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'left' },
]);

const searchTerm = ref('')
const project_sites = ref([])
const tableKey = ref(0)

const months = ref([])
const years = ref([])
const selectedMonth = ref(null);
const selectedYear = ref(null);

function exportData(){

}

function filterProjectSite(search){
    searchTerm.value = search.id
}
function filterPerMonth(search){
    searchTerm.value = search.title+'-'+selectedYear.value.id
}
function filterPerYear(search){
    searchTerm.value = selectedMonth.value.title+'-'+search.id
}

async function fetchProjectSites(){
    await axios.get('/api/project-sites').then((response) => {
        project_sites.value = response.data.data
    })
}
const generateMonths = () => {
    const monthsArray = [];
    for (let i = 1; i <= 12; i++) {
    const monthName = new Date(2023, i - 1, 1).toLocaleString('default', { month: 'long' });
    monthsArray.push({ id: i, title: monthName });
    }
    return monthsArray;
};

const generateYears = () => {
    const yearsArray = [];
    const currentYear = new Date().getFullYear(); // Get the current year
    for (let i = currentYear - 5; i <= currentYear + 5; i++) {
    yearsArray.push({ id: i, title: i.toString() });
    }
    return yearsArray;
};

onMounted(() => {
    fetchProjectSites();
    months.value = generateMonths();
    years.value = generateYears()

    const currentYear = new Date().getFullYear();
    selectedYear.value = years.value.find((year) => year.id === currentYear);
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