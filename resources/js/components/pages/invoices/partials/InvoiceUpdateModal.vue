<template>
    <div v-if="updateInvoiceModel" class="modal" id="addInvoiceModel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInvoiceModelLabel">Create Invoice</h5>
                    <button type="button" class="btn-close bg-dark" @click="updateInvoiceModelClose">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="invoiceForm" @submit.prevent="updateInvoiceForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="invoice_year" v-model="prop.formState.invoice_year" placeholder="Year of Invoice" maxlength="5">
                                    <span v-if="prop.formState.errors.invoice_year" class="text-sm text-danger ps-2">{{ prop.formState.errors.invoice_year }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <!--                                    <input type="number" list="clientList" class="form-control form-control-sm" id="inv_clients_ref" placeholder="Client Ref No.">-->
                                    <select id="clientList" class="form-control form-control-sm" @change="inv_clients_ref"  v-model="selectedRefNo">
                                        <option value="" disabled selected>Select client</option>
                                        <option v-for="client in clients"
                                                :value="client.ref_no"
                                                >{{ client.ref_no }}</option>
                                    </select>
                                    <span v-if="prop.formState.errors.clients_id" class="text-sm text-danger ps-2">{{ prop.formState.errors.clients_id }}</span>
                                    <input type="number" class="form-control form-control-sm d-none" id="inv_clients_id" v-model="prop.formState.clients_id" placeholder="Client Ref No." readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input id="inv_client_name" :value="selectedClientName" class="form-control form-control-sm inv_client_name" placeholder="Select Client" autocomplete="off" readonly>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-sm" id="amount" v-model="prop.formState.amount" placeholder="Amount" >
                                    <span v-if="prop.formState.errors.amount" class="text-sm text-danger ps-2">{{ prop.formState.errors.amount }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="date" class="form-control form-control-sm" id="due_date" v-model="formState.due_date" placeholder="Due Date">
                                    <span v-if="prop.formState.errors.due_date" class="text-sm text-danger ps-2">{{ prop.formState.errors.due_date }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-sm" id="rcd_amount" v-model="prop.formState.rcd_amount" placeholder="Received Amount" >
                                    <span v-if="prop.formState.errors.rcd_amount" class="text-sm text-danger ps-2">{{ prop.formState.errors.rcd_amount }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="date" class="form-control form-control-sm" id="rcd_due_date" v-model="prop.formState.rcd_due_date" placeholder="Received Date">
                                    <span v-if="prop.formState.errors.rcd_due_date" class="text-sm text-danger ps-2">{{ prop.formState.errors.rcd_due_date }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" v-model="prop.formState.status" >
                                        <option disabled >Select client status</option>
                                        <option value="bad_debts">Bad debt</option>
                                        <option value="good" selected>Good debt</option>
                                    </select>
                                    <span v-if="prop.formState.errors.status" class="text-sm text-danger ps-2">{{ prop.formState.errors.status }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" v-model="prop.formState.payment_type" >
                                        <option disabled>Select payment type</option>
                                        <option value="bank" selected>Bank</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                    <span v-if="prop.formState.errors.payment_type" class="text-sm text-danger ps-2">{{ prop.formState.errors.payment_type }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-sm" id="bad_debt_amount" v-model="prop.formState.bad_debt_amount" placeholder="Bad Debt. Amount" >
                                    <span v-if="prop.formState.errors.bad_debt_amount" class="text-sm text-danger ps-2">{{ prop.formState.errors.bad_debt_amount }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form-control form-control-sm" v-model="prop.formState.notes" id="notes"></textarea>
                                    <span v-if="prop.formState.errors.notes" class="text-sm text-danger ps-2">{{ prop.formState.errors.notes }}</span>
                                </div>
                            </div>

                        </div>
                        <div>
                            <button type="button" class="btn bg-gradient-secondary" @click="updateInvoiceModelClose">Close</button>
                            <button type="submit" id="submitInvoiceBtn" class="btn bg-gradient-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import {watch, ref} from "vue";

const prop = defineProps(['updateInvoiceModel', 'formState', 'clients', 'selectedInvoiceId']);
const emit = defineEmits(['fetchInvoices', 'updateInvoiceModelClose']);

const fetchClients = prop.clients;

const selectedRefNo = ref('');
const selectedClientName = ref('');

watch(() => prop.selectedInvoiceId,()=>{
    selected_clients_detail();
})
const selected_clients_detail = () => {
    const selectedClientId = prop.formState.clients_id;
    const selectedClientDetail = prop.clients.find(fetchClient => {
        return fetchClient.id === selectedClientId; // The condition to find the correct client
    });

    if (selectedClientDetail) {
        selectedRefNo.value = selectedClientDetail.ref_no;
        selectedClientName.value = selectedClientDetail.name;
    }
}

const inv_clients_ref = (e) => {
    const selectedRefNo = e.target.value;
    const selectedClient = fetchClients.find(fetchClient => {
        return fetchClient.ref_no === selectedRefNo; // The condition to find the correct client
    });
    if (selectedClient) {
        prop.formState.clients_id = selectedClient.id; // Set the selected client's ID
        selectedClientName.value = selectedClient.name; // Update the client name input
    } else {
        prop.formState.clients_id = ''; // Reset if no valid client is selected
        selectedClientName.value = '';
    }
}

const updateInvoiceForm = () => {
    axios.put(`/api/invoices/${prop.formState.id}`,{
        invoice_year : prop.formState.invoice_year,
        clients_id : prop.formState.clients_id,
        amount : prop.formState.amount,
        due_date : prop.formState.due_date,
        rcd_amount : prop.formState.rcd_amount,
        rcd_due_date : prop.formState.rcd_due_date,
        status : prop.formState.status,
        payment_type : prop.formState.payment_type,
        bad_debt_amount : prop.formState.bad_debt_amount,
        notes : prop.formState.notes
    }).then(response=>{
        emit('fetchInvoices');
        updateInvoiceModelClose();
    }).catch(error=>{
        if (error.response.status == 422) {
            prop.formState.errors = Object.keys(error.response.data.errors).reduce((acc, field) => {
                acc[field] = error.response.data.errors[field][0]; // Get the first error message
                return acc;
            }, {});
        } else {
            prop.formState.errors = {
                general: 'An unexpected error occurred. Please try again later.'
            };
        }
    })
}

const updateInvoiceModelClose = () => {
    emit('updateInvoiceModelClose');
}


</script>

<style scoped>

</style>
