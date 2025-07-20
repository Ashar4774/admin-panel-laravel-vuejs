<template>
    <tr v-for="invoice in invoices" :key="invoice.id" class="text-center text-capitalize" :class="invoice.rcd_amount == null ? (invoice.status == 'bad_debts' ? 'bg-gradient-danger text-white' : 'bg-gradient-secondary text-white') : (invoice.status == 'bad_debts' ? 'bg-gradient-danger text-white' : 'bg-gradient-success text-white')">
        <td>{{ invoice.id }}</td>
        <td>{{ invoice.clients.ref_no }}</td>
        <td>{{ invoice.clients.name }}</td>
        <td>{{ invoice.amount }}</td>
        <td>{{ invoice.formatted_due_date }}</td>
        <td>{{ invoice.invoice_year }}</td>
        <td>{{ invoice.rcd_amount ?? 0 }}</td>
        <td>{{ invoice.formatted_rcd_due_date ?? '-' }}</td>
        <td>{{ invoice.rcd_due_date ? invoice.time_gap : '-' }}</td>
        <td>{{ invoice.bad_debt_amount ?? 0 }}</td>
<!--        <td>{{ invoice.status }}</td>-->
        <td>{{ invoice.payment_type ?? '-' }}</td>
<!--        <td>{{ invoice.notes ?? '-' }}</td>-->
        <td>{{ (invoice.rcd_amount != null || invoice.bad_debt_amount != null) ? 'Paid' : 'Unpaid' }}</td>
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
    </tr>
</template>

<script setup>

const prop = defineProps(['invoices', 'editInvoice', 'updateInvoiceModelOpen', 'deleteInvoiceModelOpen']);

</script>

<style scoped>

</style>
