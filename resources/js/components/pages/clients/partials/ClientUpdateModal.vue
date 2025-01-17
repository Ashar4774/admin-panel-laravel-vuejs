<template>
    <div v-if="isClientUpdateModalOpen" class="modal" id="addClientModel" aria-labelledby="addClientModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModelLabel">Update Client</h5>
                    <button type="button" class="btn-close bg-dark" @click="closeClientUpdateModal">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="clientForm" @submit.prevent="UpdateClient">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="client_id" v-model="formState.id" placeholder="e.g. 101">
                                    <input type="number" class="form-control" id="ref_no" v-model="formState.ref_no" placeholder="e.g. 101">
                                    <span v-if="formState.errors.id" class="text-danger ps-2">{{ formState.errors.id }}</span>
                                    <span v-if="formState.errors.ref_no" class="text-danger ps-2">{{ formState.errors.ref_no }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="client_name" v-model="formState.client_name" placeholder="e.g. Afzal">
                                    <span v-if="formState.errors.client_name" class="text-danger ps-2">{{ formState.errors.client_name }}</span>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn bg-gradient-secondary" @click="closeClientUpdateModal">Close</button>
                            <button type="submit" id="submitClientBtn" class="btn bg-gradient-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

const emit = defineEmits(['close', 'fetchClients']);
const props = defineProps(['formState', 'isClientUpdateModalOpen'])
const closeClientUpdateModal = () => {
    emit('close')
}

const UpdateClient = () => {
    axios.put(`/api/clients/${props.formState.id}`,{
        ref_no: props.formState.ref_no,
        client_name: props.formState.client_name
    }).then(response=>{
        if(response.status === 201 && response.data.client) {
            emit('fetchClients');
            props.formState.ref_no = '';
            props.formState.client_name = '';
            closeClientUpdateModal();
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
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>
