<template>
    <div v-if="deleteInvoiceModel" class="modal" id="deleteInvoiceModel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteInvoiceModelLabel">Delete Invoice</h5>
                    <button type="button" class="btn-close bg-dark" @click="closeDeleteInvoiceModel">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="deleteInvoiceForm">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="invoice_id" v-model="formState.id">
                                    <p class="invoice_alert">{{ prop.invoice_alert }}</p>
                                </div>
                            </div>

                        </div>
                        <div>
                            <button type="button" class="btn bg-gradient-danger" @click="closeDeleteInvoiceModel" data-bs-dismiss="modal">No</button>
                            <button type="submit" id="deleteInvoiceBtn" @click="deleteInvoice" class="btn bg-gradient-success">Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>

import axios from "@/axios.js";

const prop = defineProps(['deleteInvoiceModel', 'formState', 'invoice_alert'])
const emit = defineEmits(['closeDeleteInvoiceModel', 'fetchInvoices'])

const deleteInvoice = () =>{
    axios.delete(`/api/invoices/${prop.formState.id}`)
        .then(response=>{
            if(response.status === 201) {
                emit('fetchInvoices');
                closeDeleteInvoiceModel();
            }
        }).catch(error=>{

    })
}
const closeDeleteInvoiceModel = () => {
    emit('closeDeleteInvoiceModel');
}
</script>

<style scoped>

</style>
