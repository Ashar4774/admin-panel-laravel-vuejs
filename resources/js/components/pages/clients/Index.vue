<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div>
                                <h5 class="mb-0">All Clients</h5>
                            </div>
                            <div class="d-flex flex-row">
                                <a href="#" class="btn bg-gradient-faded-info btn-sm mb-0 text-white mx-1" type="button" data-bs-toggle="modal" data-bs-target="#importClientModel">Import User(s)</a>
                                <a href="#" class="btn bg-gradient-primary btn-sm mb-0" id="addClient" type="button" @click="addClientModel">+&nbsp; New Client</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="clientTable">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ref #
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Arrears
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Bad Debts
                                    </th>


                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--    Add client modal-->
    <div v-if="isClientAddModalOpen" class="modal" id="addClientModel" aria-labelledby="addClientModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModelLabel">Add Client</h5>
                    <button type="button" class="btn-close bg-dark" @click="closeClientAddModal">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="clientForm">
                        <ul v-for="error in formState.errors">
                            <p class="text-danger">
                                {{ error }}
                            </p>
                        </ul>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control" id="ref_no" v-model="formState.ref_no" placeholder="e.g. 101">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="client_name" v-model="formState.client_name" placeholder="e.g. Afzal">
                                </div>
                            </div>

                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn bg-gradient-secondary" @click="closeClientAddModal">Close</button>
                            <button type="submit" id="submitClientBtn" class="btn bg-gradient-success" @click="AddClient">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!--    End add client modal-->

</template>

<script setup>
    import { ref, reactive } from 'vue';
    import axios from "@/axios.js";
    import store from "@/state/index.js";

    const formState = reactive({
        ref_no: '',
        client_name: '',
        errors: []
    })

    const isClientAddModalOpen = ref(false)

    const addClientModel = () => {
        isClientAddModalOpen.value = true
    }

    const closeClientAddModal = () => {
        isClientAddModalOpen.value = false
    }

    const AddClient = () => {
        // axios.get('/sanctum/csrf-cookie').then(response => {
            axios.post('/api/clients', {
                ref_no: formState.ref_no,
                client_name: formState.client_name
            }).then(response => {
                alert(response);
                console.log(response)
            }).catch(error => {
                alert(error);
                console.error(error)
                if (error.response.status == 422) {
                    formState.errors = Object.values(error.response.data.errors).flat()
                } else {
                    formState.errors = Object.values([error.response.data.errors]).flat()
                }
            })
        // });
    }
</script>

<style>
/* Basic styles for modal */
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
