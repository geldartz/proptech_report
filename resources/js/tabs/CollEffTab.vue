<template>
    <div class="h-full px-4">
        <Table :headers="tableHeaders"></Table>
    </div>
</template>


<script setup>
 import { onMounted, reactive, ref, watch, computed, provide, inject, onUpdated, nextTick  } from "vue";
 import Table from '@/components/Table.vue';


const tableHeaders = reactive([
   
   { title: 'DATE CREATED', onSet: true, sortable: true, query: 'full_name', date_filtered: false, searchable: true, checked: true, hasInlineEdit: true, textAlign: 'left' },
   { title: 'BILL DATE', onSet: true, sortable: true, query: 'role', date_filtered: false, searchable: true, checked: true, hasInlineEdit: true, textAlign: 'left' },
   { title: 'CREDIT MEMO #', onSet: true, sortable: true, query: 'email', date_filtered: true, searchable: true, checked: false, hasInlineEdit: true, textAlign: 'left' },
   { title: 'AMOUNT', onSet: true, sortable: true, query: 'mobile', date_filtered: true, searchable: true, checked: false, hasInlineEdit: true, textAlign: 'left' },
   { title: 'BALANCE', onSet: true, sortable: false, query: 'created_at', date_filtered: false, searchable: false, checked: false, hasInlineEdit: true, textAlign: 'left' },
   { title: 'STATUS', onSet: true, sortable: true, query: 'online', date_filtered: true, searchable: true, checked: false, hasInlineEdit: true, textAlign: 'left' },
   { title: 'BILL TYPE', onSet: true, sortable: true, query: 'online', date_filtered: true, searchable: true, checked: false, hasInlineEdit: true, textAlign: 'left' },
   { title: 'ITEM TYPE', onSet: true, sortable: true, query: 'online', date_filtered: true, searchable: true, checked: false, hasInlineEdit: true, textAlign: 'left' },
   { title: 'ACCOUNT NUMBER', onSet: true, sortable: true, query: 'online', date_filtered: true, searchable: true, checked: false, hasInlineEdit: true, textAlign: 'left' },
   { title: 'CUSTOMER NAME', onSet: true, sortable: true, query: 'online', date_filtered: true, searchable: true, checked: false, hasInlineEdit: true, textAlign: 'left' },
   { title: 'DESCRIPTION', onSet: true, sortable: true, query: 'online', date_filtered: true, searchable: true, checked: false, hasInlineEdit: true, textAlign: 'left' },
   { title: 'ACTIONS', onSet: true, sortable: false, query: 'actions', date_filtered: false, searchable: false, checked: false, hasInlineEdit: true, textAlign: 'center' },
]);

</script>
