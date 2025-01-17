<template>
    <div v-if="isClientDeleteModalOpen" class="modal" id="addClientModel" aria-labelledby="addClientModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteClientModelLabel">Delete Client</h5>
                    <button type="button" class="btn-close bg-dark" @click="closeClientDeleteModal">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" @submit.prevent="deleteClientForm">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="client_id" v-model="formState.id">
                                    <p class="client_alert">{{props.client_alert}}</p>
                                </div>
                            </div>

                        </div>
                        <div>
                            <button type="button" class="btn bg-gradient-danger" @click="closeClientDeleteModal">No</button>
                            <button type="submit" id="deleteClientBtn" class="btn bg-gradient-success">Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";

const emit = defineEmits(['close', 'fetchClients'])
const props = defineProps(['formState', 'isClientDeleteModalOpen', 'client_alert'])
const closeClientDeleteModal = () => {
    emit('close')
}



const deleteClientForm = () => {
    axios.delete(`/api/clients/${props.formState.id}`)
        .then(response=>{
            if(response.status === 201) {
                emit('fetchClients');
                closeClientDeleteModal();
            }
        }).catch(error=>{
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
