<template>
    <div v-if="importInvoiceModel" class="modal" id="importInvoiceModel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importInvoiceModelLabel">Import File</h5>
                    <button type="button" class="btn-close bg-dark" @click="importInvoiceModelClose">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="importInvoiceForm" enctype="multipart/form-data" @submit.prevent="importInvoiceForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="file" class="" id="invoice_file" @change="handleFileChange">
                                    <small v-if="prop.formState.errors.invoice_file" class="error text-danger">
                                        {{ prop.formState.errors.invoice_file }}
                                    </small>
                                </div>
                            </div>

                        </div>
                        <div>
                            <button type="submit" id="submitImportInvoiceBtn" class="btn bg-gradient-success">Import</button>

                            <div id="loader" style="display: none; margin-left: 10px;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import axios from "@/axios.js";

const prop = defineProps(['importInvoiceModel', 'formState']);
const emit = defineEmits(['fetchInvoices', 'importInvoiceModelClose']);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if(file){
        prop.formState.invoice_file = file;
    }
}

const importInvoiceForm = async () => {
    await axios.post('/api/invoices/import', {
        invoice_file: prop.formState.invoice_file
    },{
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    }).then(response=>{
        emit('fetchInvoices');
        importInvoiceModelClose();
    }).catch(error=>{
        console.error(error)
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
const importInvoiceModelClose = () => {
    emit('importInvoiceModelClose');
}
</script>

<style scoped>

</style>
