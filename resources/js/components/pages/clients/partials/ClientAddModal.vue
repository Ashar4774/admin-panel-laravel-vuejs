<template>
    <div v-if="isClientAddModalOpen" class="modal" id="addClientModel" aria-labelledby="addClientModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModelLabel">Add Client</h5>
                    <button type="button" class="btn-close bg-dark" @click="closeClientAddModal">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="clientForm" @submit.prevent="AddClient">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="ref_no" v-model="formState.ref_no" placeholder="e.g. 101">
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
                            <button type="button" class="btn bg-gradient-secondary" @click="closeClientAddModal">Close</button>
                            <button type="submit" id="submitClientBtn" class="btn bg-gradient-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, reactive } from 'vue';

    const props = defineProps(['isClientAddModalOpen', 'formState']);
    const emit = defineEmits(['close','clientAdded'])

    /*const formState = reactive({
        ref_no: '',
        client_name: '',
        errors: {}
    })*/
    const closeClientAddModal = () => {
        emit('close')
    }
    const AddClient = async () => {
        await axios.post('/api/clients',{
            ref_no: props.formState.ref_no,
            client_name: props.formState.client_name
        }).then(response=>{
            emit('clientAdded')
            props.formState.ref_no = '';
            props.formState.client_name = '';
            closeClientAddModal()
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
