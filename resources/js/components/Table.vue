<template>
    <div>
        <div v-if="hasUpperFilers" class="w-full flex my-4 py-2  items-center" :class="[!showFiltering && !hasDateFilter ? 'justify-end' : 'justify-between']">
            <div class="flex-initial w-full">
                <div class="flex items-center" :class="[!showFiltering && !hasDateFilter ? 'justify-end' : 'justify-between']">
                    <div :class="[!showFiltering && !hasDateFilter   ? 'w-1/2' : 'w-2/3', !showFiltering && !hasDateFilter ? '' : 'mr-3']" v-show="showSearch">
                        <SearchInput label="Search" @search="handleSearch"></SearchInput>
                    </div>
                    <div v-if="showFiltering || hasDateFilter" class="w-2/3 flex items-center gap-2">
                        <slot name="customButtons"></slot>
                    </div>
                </div>
            </div>
            <div class="flex-initial w-1/4" v-if="hasDateFilter || showFiltering">
                <div class="flex justify-end">
                    <Popover v-if="hasDateFilter" class="relative">
                        <PopoverButton class="px-1 ml-1 h-[33px] tracking-wider">
                            <CalendarDaysIcon class="w-6 h-6 text-[#808080] dark:text-darkmode-444" />
                        </PopoverButton>
                        <transition enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                            <PopoverPanel
                                class="absolute -left-8 z-30 mt-2 flex w-screen max-w-max -translate-x-1/2 px-4">
                                <div
                                    class="bg-white dark:bg-darkmode-600 w-52 p-2 mt-1 border border-1 dark:border-darkmode-555 border-gray-300 rounded-lg">
                                    <div class="flex flex-col gap-2">
                                        <div class="relative pt-2">
                                            <div class="border border-emsGray rounded-md overflow-hidden">
                                                <p class="absolute text-xs top-0 left-2 bg-white">Start Date:</p>
                                                <input type="text" onfocus="(this.type='date')"
                                                    class="border-none w-full placeholder-gray-300"
                                                    placeholder="mm / dd / yyyy" v-model="start_date" />
                                            </div>
                                        </div>
                                        <div class="relative pt-2">
                                            <div class="border border-emsGray rounded-md overflow-hidden ">
                                                <p class="absolute text-xs top-0 left-2 bg-white">End Date:</p>
                                                <input type="text" onfocus="(this.type='date')"
                                                    class="border-none w-full placeholder-gray-300"
                                                    placeholder="mm / dd / yyyy" v-model="to_date" />
                                            </div>
                                        </div>
                                        <div class="px-1 flex flex-col gap-2">
                                            <button
                                                class="p-1.5 px-3 h-[33px] bg-blue-400 dark:bg-darkmode-600 dark:text-darkmode-444 tracking-wider text-[11px] text-white w-full rounded-md"
                                                @click="filterDate">Filter
                                            </button>
                                            <button v-if="hasExport"
                                                :class="['p-1.5 px-3 h-[33px] dark:bg-darkmode-600 dark:text-darkmode-444 tracking-wider text-[11px] text-white w-full rounded-md', start_date != '' && to_date != '' ? 'bg-bluer' : 'bg-emsGray cursor-not-allowed']"
                                                @click="isExporting || (start_date == '' || to_date == '') ? null : $emit('export', { start_date: start_date, to_date: to_date })">
                                                <div v-if="isExporting"
                                                    :class="['flex items-center justify-center gap-2', { 'hover:cursor-not-allowed': isExporting }]">
                                                    <ArrowPathIcon class="animate-spin h-5 w-5 shrink-0" />
                                                    <span class="animate-pulse">Processing...</span>
                                                </div>
                                                <p v-else>Export</p>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </PopoverPanel>
                        </transition>
                    </Popover>

                    <Popover v-if="showFiltering" class="relative">
                        <PopoverButton class="px-1 ml-1 uppercase h-[33px] tracking-wider ">
                            <FunnelIcon class="w-6 h-6 text-[#808080] dark:text-darkmode-444" />
                        </PopoverButton>
                        <transition enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                            <PopoverPanel
                                class="absolute left-[-50px] z-50 mt-2 flex w-screen max-w-max -translate-x-32 px-4">
                                <slot name="customFilter">
                                    <div
                                        class="bg-white dark:bg-darkmode-600 w-48 border border-1 dark:border-darkmode-444 border-gray-300 py-2 px-2">
                                        <div class="mx-auto w-full ">
                                            <div class="flex items-center border-b dark:border-b-slate-600"
                                                v-for="thead in tableHeader">
                                                <input :id="thead.title" type="checkbox" class="hidden checkboxToogable"
                                                    @change.prevent="changeTable($event, thead)" :checked="thead.onSet">
                                                <label :for="thead.title"
                                                    class="cursor-pointer text-[12px] bg-white dark:bg-darkmode-600 px-2 py-2 transition w-full flex justify-between items-center dark:text-darkmode-444">
                                                    <div>{{ thead.title }}</div>
                                                    <div class="shrink-0 text-white check-icon">
                                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                                            <circle cx="12" cy="12" r="12" fill="#fff"
                                                                fill-opacity="0.2" />
                                                            <path d="M7 13l3 3 7-7" stroke="#fff" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </slot>
                            </PopoverPanel>
                        </transition>
                    </Popover>
                    <div v-if="hasFilter" class="flex items-center pl-1 relative">

                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1 flow-root dark:bg-darkmode-700">
            <div class="-mx-4 -my-2 overflow-x-auto">
                <div class="w-full py-2 align-middle sm:px-4 lg:px-4">
                    <div class="border-b dark:border-gray-600 rounded-t-lg overflow-hidden">
                        <div class="w-full max-h-[700px] overflow-auto  min-h-[450px] bg-white">
                            <table class="w-full table-fixed overflow-auto " id="dynamic-table">
                                <thead class="bg-blue-800 dark:bg-darkmode-600 text-white  sticky top-0 z-10">
                                    <tr>
                                        <th v-show="showSelect" scope="col" class="py-2.5 pl-2 text-sm w-10">
                                            <input type="checkbox" class="parentCheckbox focus:ring-0 rounded-sm dark:bg-darkmode-700 dark:border-gray-500"
                                                @change="checkAll($event)" />
                                        </th>
                                        <th v-for="(th, index) in filteredHeader" :key="th" 
                                            v-show="th.title != 'APPROVERS' && !th.hide"
                                            
                                            :class="[th.freeze ? 'dark:bg-darkmode-600 bg-bluer ':''  ,'py-2.5  text-sm dark:text-darkmode-555  font-normal ',isAttendanceReport ? '' : 'whitespace-nowrap uppercase', th.title == 'ACTIONS' || th.title == 'ID' ? 'w-32' : (th.columnWidth ?? 'w-44 min-w-44')]"
                                            :style="th.freeze ? { left: getLeftPosition(index) + 'px', position: 'sticky', zIndex: 10,  } : {}" :ref="'header-' + index">
                                            <div 
                                                :class="['flex px-2 gap-2', 'justify-'+th.textAlign , { 'justify-center': th.title == 'ACTIONS' }, { 'ml-5': index == 0 }]">
                                                <span class="font-normal">{{ th.title }}</span>
                                                <span v-show="th.sortable" @mouseenter="setSort(th.title)">
                                                    <BarsArrowDownIcon class="w-4 h-4" @click="sort(th.query)"
                                                        v-if="sortOrder == 'ASC' && th.title == sortTitle">
                                                    </BarsArrowDownIcon>
                                                    <BarsArrowUpIcon class="w-4 h-4" @click="sort(th.query)" v-else>
                                                    </BarsArrowUpIcon>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="relative text-darkgray">
                                    <TableLoader :showLoader="isLoading"
                                        :colLength="showSelect ? filteredHeader.length + 1 : filteredHeader.length" />
                                        <!-- data-aos="fade-zoom-in" data-aos-easing="ease-in-back" :data-aos-delay="ii * 25" data-aos-offset="0" -->
                                    <tr v-if="!isLoading && from_count > 0" v-for="(item, ii) in formattedData" :key="item.id" :id="'col-' + item.id" 
                                        :class="[hasRowClick ? 'hover:cursor-pointer hover:bg-slate-100' : '', 'border-b border-lightgray dark:border-gray-600 dark:hover:bg-slate-700']"
                                        @click="hasRowClick ? tableRowClick(item) : ''">
                                        <td v-if="(item.hasOwnProperty('id') && showSelect == true) || item.hasOwnProperty('employee_id')"
                                            class="pl-2 text-center">
                                            <input @change="checkItem($event)" type="checkbox" :value="item.id"
                                                :checked="item.checked" class="checkTrigger focus:ring-0 rounded-sm dark:bg-darkmode-700 dark:border-gray-500">
                                        </td>
                                        <td v-for="(column, index) in filteredHeader" :key="column.title"  v-show="!column.hide"
                                            :class="['text-gray-600 text-' + column.textAlign, { 'highlight': index == 0 }, 'my-2 py-2 px-2 ', { 'pl-8': index == 0 }, column.freeze ? 'dark:bg-darkmode-700 bg-white':'']"
                                            :style=" 'line-height:0;',column.freeze ? { left: getLeftPosition(index) + 'px', position: 'sticky' } : {}">
                                            <template v-if="column.hasInlineEdit">
                                                <slot :name="column.query" :data="item"></slot>
                                            </template>
                                            <template v-if="column.title === 'ACTIONS'" class="grid justify-center">
                                                <slot :data="item"></slot>
                                            </template>

                                           
                                            <template v-else >

                                                <span v-if="!column.hasInlineEdit"
                                                    class="dark:text-darkmode-444 text-sm">
                                                    {{ item[column.query] }}
                                                </span>

                                            </template>
                                        </td>
                                    </tr>

                                    <td :colspan="filteredHeader.length" v-if="!isLoading && formattedData.length == 0"
                                        class="text-center">
                                        <div class="full">
                                            <!-- <img class="h-60 mx-auto mix-blend-multiply" src="/images/web_no_result.jpg" alt=""> -->
                                            <LottieAnimation  class="w-96 h-96 block mx-auto" :animation-data="NoDataLottie" :auto-play="true" :loop="true" :speed="1" ref="anim" />
                                           
                                            <!-- <p class="text-center pb-5 text-aside dark:text-darkmode-555">No records found</p> -->
                                        </div>
                                    </td>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div v-show="showPaginate"
                        class="flex flex-col sm:flex-row items-center bg-white dark:bg-darkmode-700 px-4 py-3 sm:px-6">
                        <div class="flex-initial w-full md:1/3 " v-if="hasShowEntries">
                            <div
                                class="flex items-center justify-center md:justify-start text-[11px] text-gray-700 dark:text-darkmode-555">
                                <span>SHOW</span>
                                <select @change="showEntries($event)" name="number-entries"
                                    class="block w-24 dark:bg-darkmode-600 dark:ring-slate-700 dark:text-darkmode-444 rounded-sm border-0 mx-1 py-1 pl-3 pr-10 text-gray-700 ring-1 ring-inset ring-gray-300 bg-white focus:ring-1 focus:ring-gray-600 text-[11px]">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                    <option value="1000">1,000</option>
                                    <option v-if="hasThousandRecords" value="5000">5,000</option>
                                    <option v-if="hasThousandRecords" value="10000">10,000</option>
                                </select>
                                <span>ENTRIES</span>
                            </div>
                        </div>
                        <div class="flex-initial w-full md:1/3 items-center text-center justify-between py-2 md:py-0 ">
                            <p class=" text-gray-700 dark:text-darkmode-555 text-[11px]"> SHOWING <span class="">{{
                                from_count }}</span>
                                TO <span class="">{{ to_count }}</span> OF <span class="">{{ total_query_count }}</span>
                                RESULTS </p>
                        </div>
                        <div class="flex-initial w-full md:1/3 text-center md:text-end">
                            <nav>
                                <ul class="isolate inline-flex -space-x-px rounded-md shadow-sm text-[11px]">
                                    <li class="page-item relative inline-flex items-center rounded-l-md "
                                        :class="[currentPage === 1 ? 'text-gray-400' : 'text-gray-700']">
                                        <button
                                            class="px-2 py-2  ring-1 ring-inset ring-gray-300 dark:ring-slate-700 rounded-l-md hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                            tabindex="-1" @click.prevent="firstEntries()"
                                            :disabled="currentPage === 1">First</button>
                                    </li>
                                    <li class=" relative inline-flex items-center"
                                        :class="[currentPage === 1 ? 'text-gray-400' : 'text-gray-700']">
                                        <button
                                            class="px-4 py-2  text-[11px] ring-1 ring-inset ring-gray-300 dark:ring-slate-700 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                            tabindex="-1" @click.prevent="prevEntries()"
                                            :disabled="currentPage === 1">Prev</button>
                                    </li>
                                    <li v-for="page in pageIndexes" :key="page"
                                        class="relative z-10 inline-flex items-center ">
                                        <button
                                            class="text-[11px]  px-4 py-2  font-semibold  focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2"
                                            :class="[page === currentPage ? 'bg-blue-400 dark:bg-darkmode-500 text-white border-1 border-blue-600' : 'text-blue ring-1 ring-inset ring-gray-300']"
                                            @click.prevent="paginateEvent(page)">{{ page }}</button>
                                    </li>
                                    <li class="page-item relative inline-flex items-center ">
                                        <button
                                            class="px-4 py-2  text-[11px] ring-1 ring-inset ring-gray-300 dark:ring-slate-700 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                            :class="[currentPage === allPages ? 'text-gray-400' : 'text-gray-700']"
                                            tabindex="-1" @click.prevent="nextEntries()"
                                            :disabled="currentPage === allPages">Next</button>
                                    </li>
                                    <li
                                        class="page-item relative inline-flex items-center rounded-r-md px-2 py-2 ring-1 ring-inset ring-gray-300 dark:ring-slate-700 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                        <button :class="[currentPage === allPages ? 'text-gray-400' : 'text-gray-700']"
                                            tabindex="-1" @click.prevent="lastEntries()"
                                            :disabled="currentPage === allPages">Last</button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <slot name="buttons" :checkedItems="checkedItems">

        </slot>
    </div>
</template>



<script setup>
import { LottieAnimation } from "lottie-web-vue";
import NoDataLottie from "/resources/assets/no_data.json"

</script>
<script>
import TableLoader from "@/components/TableLoader.vue";
import SearchInput from "@/components/Search.vue";
import { ChevronLeftIcon, ChevronRightIcon, BarsArrowDownIcon, BarsArrowUpIcon, InboxArrowDownIcon, CalendarDaysIcon, FunnelIcon, ArrowPathIcon } from '@heroicons/vue/24/outline'
import { RadioGroup, RadioGroupLabel, RadioGroupDescription, RadioGroupOption, } from '@headlessui/vue'
import { successMessage, errorMessage } from "@/utilities/toast.js";
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'

import axios from "axios";
import { provide } from 'vue'

export default {
    name: 'CustomTable',
    components: {
        ChevronLeftIcon,
        ChevronRightIcon,
        Popover,
        PopoverButton,
        PopoverPanel,
        InboxArrowDownIcon,
        CalendarDaysIcon,
        FunnelIcon,
        TableLoader,
        BarsArrowDownIcon,
        BarsArrowUpIcon,
        SearchInput,
        RadioGroup,
        RadioGroupLabel,
        RadioGroupDescription,
        RadioGroupOption,
        ArrowPathIcon,
    },
    emits: ['export', 'filter-employee', 'item-selected', 'row-click'],
    props: {
        url: String,
        headers: {
            type: Array,
            default: [],
        },
        hasDateFilter: {
            type: Boolean,
            default: true,
        },
        hasExport: {
            type: Boolean,
            default: false,
        },
        isExporting: {
            type: Boolean,
            default: false,
        },
        showSelect: {
            type: Boolean,
            default: true,
        },
        showSearch: {
            type: Boolean,
            default: true,
        },
        searchLg: {
            type: Boolean,
            default: false,
        },
        showFiltering: {
            type: Boolean,
            default: true,
        },
        hasFilter: {
            type: Boolean,
            default: false,
        },
        showPaginate: {
            type: Boolean,
            default: true,
        },
        hasShowEntries: {
            type: Boolean,
            default: true,
        },
        hasRowClick: {
            type: Boolean,
            default: true,
        },
        isFormatDateTime: {
            type: Boolean,
            default: false,
        },
        injectData: {
            type: Array,
            default: [],
        },
        hasUpperFilers: {
            type: Boolean,
            default: true,
        },
        searchKey: {
            type: String,
            default: ''
        },
        hasThousandRecords: {
            type: Boolean,
            default: false,
        },
        isAttendanceReport: {
            type: Boolean,
            default: false,
        },
        hide: {
            type: Boolean,
            default: false,
        },
        

    },
    data() {
        return {
            isLoading: false,
            data: [],
            checkedItems: [],
            timer: null,
            pageIndexes: 0,
            currentPage: 1,
            allPages: 0,
            page: 1,
            per_page: 10,
            from_count: 0,
            to_count: 0,
            total_query_count: 0,
            sortOrder: 'DESC',
            sortTitle: '',
            sortQuery: '',
            querableColumns: '',
            dropdownToggleExport: false,
            dropdownToggleFilter: false,
            slideoverOpen: true,
            start_date: '',
            to_date: '',
            date_column: '',
            search_term: '',
            max_date: new Date(),
            employee_ids: null,
        }
    },
    methods: {
        handleSearch(text) {
            this.search_term = text
            this.loadData()
        },
        checkAll(evt) {
            if (evt.target.checked) {
                jQuery('.checkTrigger').prop("checked", true);
                jQuery('.checkTrigger').closest('tr').addClass('highlight-color dark:bg-darkmode-500');
                this.formattedData.forEach(item => {
                    // item.checked = true;
                    this.checkedItems.push(item);
                });
            } else {
                jQuery('.checkTrigger').closest('tr').removeClass('highlight-color dark:bg-darkmode-500');
                jQuery('.checkTrigger').prop("checked", false);
                this.formattedData.forEach(item => {
                    item.checked = false;
                });
                this.checkedItems = [];
            }
            this.$emit('item-selected', this.checkedItems);
        },
        checkItem(evt) {
            const value = evt.target.value;
            const isChecked = evt.target.checked;

            const parentRow = evt.target.closest("tr");
            // const nextHighlightedTd = parentRow.querySelector("");
            if (isChecked) {
                parentRow.classList.add("highlight-color");
                parentRow.classList.add("dark:bg-darkmode-500");
                const itemData = this.formattedData.filter((item) => item.id == value);
                this.checkedItems.push(...itemData)
            } else {
                parentRow.classList.remove("highlight-color");
                parentRow.classList.remove("dark:bg-darkmode-500");
                jQuery('.parentCheckbox').prop('checked', false);
                const itemData = this.formattedData.filter((item) => item.id == value);
                const index = this.checkedItems.indexOf(...itemData);
                if (index !== -1) {
                    this.checkedItems.splice(index, 1);
                }
            }
            this.$emit('item-selected', this.checkedItems);
        },
        changeTable(evt, obj) {
            obj.onSet = evt.target.checked
            this.loadData()
        },
        filterDate() {
            const filteredHeaders = this.filteredHeader.filter(header => header.date_filtered);
            if (filteredHeaders[0] == undefined) return errorMessage("Oops!", "Kindly specify a column field that can be filtered by date.", "top-right")
            this.date_column = filteredHeaders[0].query
            this.loadData()
        },
        sort(query) {
            this.sortOrder = this.sortOrder == 'ASC' ? 'DESC' : 'ASC'
            this.sortQuery = query
            this.loadData()
        },
        setSort(title) {
            this.sortTitle = title
        },
        showEntries(evt) {
            this.per_page = evt.target.value
            this.loadData()
        },
        prevEntries() {
            this.currentPage = this.currentPage - 1
            this.page = this.currentPage
            this.loadData()
        },
        nextEntries() {
            this.currentPage = this.currentPage + 1
            this.page = this.currentPage
            this.loadData()
        },
        firstEntries() {
            this.page = 1
            this.loadData()
        },
        lastEntries() {
            this.page = this.allPages
            this.loadData()
        },
        getFiveNumbers(currentIndex, totalIndex) {
            let left = Array.from({ length: Math.min(currentIndex - 1, 2) }, (_, i) => currentIndex - i - 1).sort((a, b) => a - b);
            let right = Array.from({ length: Math.min(totalIndex - currentIndex, 2) }, (_, i) => currentIndex + i + 1);
            let nearest = left.concat([currentIndex], right);
            this.pageIndexes = nearest
        },
        paginateEvent(index) {
            this.page = index
            this.loadData()
        },
        isDate(value) {
            return (
                typeof value === 'string' &&
                value !== "" &&
                !isNaN(Date.parse(value)) && value.length !== 4
            );
        },
        extractObjectName(obj, key) {

            if (obj && typeof obj === 'object') {
                return obj[key];
            }
            return "";
        },
        extractArrayValue(obj, key) {
            if (obj.length > 0) {
                return obj.map(item => item).join(',');
            }

            return "";
        },
        handleArrayValue(arr, key) {
            return arr;
        },
        renderTable(data) {
            let html = '<table class="table">';
            html += '<thead><tr><th>From Currency</th><th>To Currency</th></tr></thead>';
            html += '<tbody>';

            data.forEach(item => {
                const fromCurrencyLabel = item.from_currency.label;
                const toCurrencyLabel = item.to_currency.label;
                const fromCurrencyName = item.from_currency.currency_name;
                const toCurrencyName = item.to_currency.currency_name;

                html += `<tr><td>${fromCurrencyLabel} (${fromCurrencyName})</td><td>${toCurrencyLabel} (${toCurrencyName})</td></tr>`;
            });

            html += '</tbody></table>';

            return html;
        },
        isJSON(value) {
            try {
                JSON.parse(value);
                return true;
            } catch (error) {
                return false;
            }
        },
       async loadData() {
            this.isLoading = true;
            let url = this.$props.url;
            if (this.$props.url[this.$props.url.length - 1] == '/') {
                url = this.$props.url.slice(0, -1);
            }
            // axios.get(this.$props.url, {
           await axios.get(url, {
                params: {
                    page: this.page,
                    columns: this.queryList,
                    search_term: this.search_term,
                    search: this.searchList,
                    per_page: this.per_page,
                    sort_by: this.sortOrder,
                    date_column: this.date_column,
                    start_date: this.start_date,
                    to_date: this.to_date,
                    sort_query: this.sortQuery,
                    additional_queries: this.$props.injectData,
                    // company_id: this.company.getCompanyId,
                    employee_ids: this.employee_ids
                }

            })
                .then((data) => {
                    this.isLoading = false;
                    let newData = data.data.data.data;
                    this.currentPage = data.data.data.current_page
                    this.allPages = data.data.data.last_page
                    this.from_count = data.data.data.from
                    this.to_count = data.data.data.to
                    this.total_query_count = data.data.data.total
                    this.resultCount = this.total_query_count
                    this.getFiveNumbers(this.currentPage, this.allPages)
                    this.data = newData
                })
                .catch((e) => {
                    // console.log(e);
                    errorMessage("Oops!", e.message, "top-right")
                });
        },
        tableRowClick(item) {
            this.$emit('row-click', item);
        },
       async filterEmployee(event) {
            this.employee_ids = event;
            this.$emit('filter-employee', event);
           await this.loadData()
        }
    },
    computed: {
        tableHeader() {
            return this.headers || []
        },
        
        filteredHeader() {
            return this.headers.filter((th) => th.onSet);
        },
        searchList() {
            return this.headers.map((th) => {
                if (th.searchable) {
                    return th.query
                }
            }).filter((value) => value !== undefined).concat();
        },
        getColumns() {
            return this.headers.filter((th) => {
                if (th.onSet) {
                    return th.query
                }
            });
        },
        queryList() {
            return this.headers.map((th) => {
                if (th.onSet) {
                    return th.query
                }
            }).filter((value) => value !== undefined).concat();

        },
        getLeftPosition() {
        return (index) => {
            let left = 0;
            for (let i = 0; i < index; i++) {
                if (this.filteredHeader[i].freeze) {
                    const ref = this.$refs['header-' + i];
                    if (ref && ref[0]) {
                        left += ref[0].offsetWidth;
                    }
                }
            }
            return left;
        };
    },
        formattedData() {
            return this.data.map(obj => {
                const formattedObj = { ...obj };
                Object.entries(obj).forEach(([key, value]) => {
                    //this.isDate(value)
                    if (key !== "id" && key == 'created_at') {
                        const createdAt = new Date(value);
                        const formattedDate = this.isFormatDateTime
                            ? createdAt.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric', hour12: true, hour: '2-digit', minute: '2-digit' })
                            : createdAt.toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });
                        formattedObj[key] = `${formattedDate}`;
                    } else
                        if (this.isJSON(value)) {

                            try {
                                const parsedValue = JSON.parse(value);
                                if (Array.isArray(parsedValue)) {

                                    formattedObj[key] = parsedValue //this.handleArrayValue(value,key);
                                }
                            } catch (error) {
                                formattedObj[key] = value;
                            }
                        } else if (value && typeof value === "object") {

                            if (Array.isArray(value)) {
                                // console.log("approvers", key)
                                if (key === 'periods') {
                                    formattedObj[key] = value;
                                }else if (key === 'documents') {
                                    formattedObj[key] == value;
                                } else {
                                    formattedObj[key] = this.extractArrayValue(value, key);
                                }

                                //
                            } else {

                                if (key == 'employee') {
                                    formattedObj[key] == value;
                                } else {
                                    formattedObj[key] = this.extractObjectName(value, key);
                                }
                            }

                        }
                });

                return formattedObj;
            });
        },
        paginatedItems() {
            const startIndex = (this.currentPage - 1) * this.allPages;
            const endIndex = startIndex + this.allPages;
            return this.formattedData.slice(startIndex, endIndex);
        }
    },
    mounted(){
        this.max_date = this.max_date.getDate() + 60;
        if (this.$props.url) {
            if (this.searchKey !== "") {
                this.search_term = this.searchKey;
            }
            this.loadData();
        }
    },
    watch: {
        checkedItems: {
            handler() {

                if (this.to_count == this.checkedItems.length) {
                    jQuery('.parentCheckbox').prop('checked', true);
                }
            }
        },
        isLoading: {
            handler() {
                this.$emit('onLoad', this.isLoading);
            }
        },
        searchKey: {
            handler(newValue, oldValue) {
                this.search_term = newValue;
                this.loadData()
            }
        },
    },


}

</script>
<style>
/* Add your CSS styles here */
.highlight-color {
    background: #dbeafe;
}

#dynamic-table :checked+label {
    background-color: #1e40af;
    color: #fff;
}

#dynamic-table :not(:checked)+label .check-icon {
    display: none;
}
</style>
