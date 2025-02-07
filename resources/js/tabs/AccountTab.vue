<template>
    <div class="h-full px-4">
        <Table :url="'/api/accounts?month='+month+'&year='+year" :headers="tableHeaders" :key="tableKey" :searchKey="searchTerm" :hasThousandRecords="true">
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
    
   { title: 'PROJECT NAME', onSet: true, sortable: true, query: 'name', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'between' },
   { title: 'CUSTOMER NAME', onSet: true, sortable: true, query: 'customer_name', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'between' },
   { title: 'LOT AREA', onSet: true, sortable: true, query: 'lot_area', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'between' },
   { title: 'FLOOR AREA', onSet: true, sortable: true, query: 'floor_area', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'between' },
   { title: 'FIRST BILL PERIOD', onSet: true, sortable: true, query: 'first_bill_period', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'between' },
   { title: 'RECEIPT DATE', onSet: true, sortable: true, query: 'or_receipt_date', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'between' },
   { title: 'ACCOUNT NUMBER', onSet: true, sortable: true, query: 'account_number', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'between' },
   { title: 'TURN-OVER DATE', onSet: true, sortable: true, query: 'turned_over_date', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'between' },
   { title: 'MEMBERSHIP FEE(PFTM)', onSet: true, sortable: true, query: 'pftm_membership_fee', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-green-700' },
   { title: 'ASSOCIATION DUES(PFTM)', onSet: true, sortable: true, query: 'pftm_assoc_dues', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-green-700' },
   { title: 'INSURANCE(PFTM)', onSet: true, sortable: true, query: 'pftm_insurance', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-green-700' },
   { title: 'PREVENTIVE MAINTENANCE(PFTM)', onSet: true, sortable: true, query: 'pftm_maintenance', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-green-700' },
   { title: 'REAL PROPERTY TAX(PFTM)', onSet: true, sortable: true, query: 'pftm_tax', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-green-700' },
   { title: 'UTILITY(PFTM)', onSet: true, sortable: true, query: 'pftm_utility', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-green-700' },
   { title: 'OTHER INCOME(PFTM)', onSet: true, sortable: true, query: 'pftm_others', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-green-700' },
   { title: 'BLDG IMPROVEMENT(PFTM)', onSet: true, sortable: true, query: 'pftm_bldg', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-green-700' },
   { title: 'VIOLATIONS(PFTM)', onSet: true, sortable: true, query: 'pftm_violations', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-green-700' },
   { title: 'ARREARS(HOA)', onSet: true, sortable: true, query: 'arrears_hoa', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-cyan-700' },
   { title: 'CURRENT(HOA)', onSet: true, sortable: true, query: 'current_hoa', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-cyan-700' },
   { title: 'ADVANCE(HOA)', onSet: true, sortable: true, query: 'advance_hoa', date_filtered: false, searchable: true, checked: true, hasInlineEdit: false, textAlign: 'right', columnWidth: 'w-60', bgColor: 'bg-cyan-700' },
]);

const searchTerm = ref('')
const month = ref(new Date(0, new Date().getMonth()).toLocaleString('default', { month: 'long' }))
const year = ref(new Date().getFullYear())
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
    month.value = search.title
    tableKey.value++;
}
function filterPerYear(search){
    year.value = search.title
    tableKey.value++;
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

    const currentMonth = new Date().getMonth() + 1;
    selectedMonth.value = months.value.find((month) => month.id === currentMonth);
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