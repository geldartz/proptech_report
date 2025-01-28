<template>
    <tr class="table-loader" v-for="i in rowLength " :key="i" v-show="showLoader">
        <td v-for="item in colLength" :key="item"  class="text-center py-2">
            <free-style-shimmer class="pl-4 " :is-loading="true"  height="20px" width="80%" border-radius="5px" />
        </td>
        <td v-if="withAction " class="text-center flex justify-content-between p-2">
            <free-style-shimmer :is-loading="true"  height="22px" width="40px" border-radius="5px"/>
            <free-style-shimmer :is-loading="true"  height="22px" width="40px" border-radius="5px"/>
        </td>
    </tr>

</template>

<script>
export default{
    name:"TableLoader",
    props: {
        showLoader:{
            type:Boolean,
            default: false,
        },
        colLength:{
            type:Number,
            default: 5
        },
        rowLength:{
            type:Number,
            default: 10
        },
        withAction: {
            type:Boolean,
            default: false,
        }

    },
}
</script>
