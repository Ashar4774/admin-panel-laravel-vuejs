<template>
    <div v-if="isClientImportModalOpen" class="modal" id="importClientModel" tabindex="-1" role="dialog" aria-labelledby="importClientModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importClientModelLabel">Import File</h5>
                    <button type="button" class="btn-close bg-dark" @click="closeClientImportModal">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="importClientForm" enctype="multipart/form-data" @submit.prevent="importClientForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="file" class="" id="client_file" @change="handleFileChange">
                                    <small v-if="props.formState.errors.client_file" class="error text-danger">
                                        {{ props.formState.errors.client_file }}
                                    </small>
                                </div>
                            </div>

                        </div>
                        <div>
                            <button type="submit" id="submitImportClientBtn" class="btn bg-gradient-success">Import</button>

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

const props = defineProps(['isClientImportModalOpen', 'formState'])
const emit = defineEmits(['close','fetchClients'])

const closeClientImportModal = () => {
    emit('close');
}

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        props.formState.client_file = file; // Update the file in the form state
    }
}

const importClientForm = async () => {
    await axios.post('/api/clients/import',{
        client_file: props.formState.client_file
    },{
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    }).then(response=>{
        emit('fetchClients');
        closeClientImportModal();
    }).catch(error=>{
        console.error(error)
        if (error.response.status == 422) {
            props.formState.errors = Object.keys(error.response.data.errors).reduce((acc, field) => {
                acc[field] = error.response.data.errors[field][0]; // Get the first error message
                return acc;
            }, {});
        } else {
            props.formState.errors = {
                general: 'An unexpected error occurred. Please try again later.'
            };
        }
    })
}
</script>

<style scoped>

</style>
