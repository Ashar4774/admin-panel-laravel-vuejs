<script setup>

</script>

<template>
    <td class="text-center text-white">
        <a href="#" :class="( invoice.rcd_amount == null && invoice.bad_debt_amount == null ) ? 'mx-2' : '' " data-bs-toggle="tooltip" title="View Invoice">
            <i class="fa-solid fa-eye text-white-50"></i>
        </a>
        <a v-if="invoice.rcd_amount == null && invoice.bad_debt_amount == null" href="#" class="" id="payInvoice" :data-id="invoice.id" data-bs-toggle="tooltip" title="Pay Invoice">
            <i class="fa-solid fa-file-invoice text-white-50"></i>
        </a>
        <a href="#" class="mx-2" id="editInvoice" @click="updateInvoiceModelOpen(invoice.id)" :data-id="invoice.id" data-bs-toggle="tooltip" title="Edit Invoice">
            <i class="fas fa-user-edit text-white-50"></i>
        </a>
        <span>
                 <i class="cursor-pointer fas fa-trash text-white-50" id="deleteInvoice" @click="deleteInvoiceModelOpen(invoice.id)" :data-id="invoice.id" data-bs-toggle="tooltip" title="Delete Invoice"></i>
            </span>
    </td>
</template>

<style scoped>

</style>
