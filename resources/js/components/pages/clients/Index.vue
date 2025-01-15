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
                                <a href="#" class="btn bg-gradient-faded-info btn-sm mb-0 text-white mx-1" type="button" @click="importClientModel">Import User(s)</a>
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
                                    <tr v-if="clients" v-for="client in clients" :key="client.id">
                                        <td class="ps-4">{{ client.ref_no }}</td>
                                        <td>{{ client.name }}</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td class="text-center">
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" title="View User">
                                                <i class="fa-solid fa-eye text-secondary"></i>
                                            </a>
                                            <a href="#" class="" data-bs-toggle="tooltip" data-bs-original-title="State of Account">
                                                <i class="fa-solid fa-file-invoice text-secondary"></i>
                                            </a>
                                            <a href="#" class="mx-3" @click="editClient(client.id)" data-bs-toggle="tooltip" data-bs-original-title="Edit User" data-id="{{ client.id  }}">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <span>
                                                <i class="cursor-pointer fas fa-trash text-secondary" @click="deleteClient(client.id)" id="deleteClient" data-bs-toggle="tooltip" data-bs-original-title="Delete User" data-id="{{ client.id  }}"></i>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-else>
                                        <td colspan="5">No record found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination mt-3 justify-content-end pe-3 gap-3">
                            <button
                                class="btn btn-sm btn-secondary"
                                :disabled="pagination.current_page === 1"
                                @click="changePage(pagination.current_page - 1)"
                            >
                                Previous
                            </button>
                            <span>Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
                            <button
                                class="btn btn-sm btn-secondary"
                                :disabled="pagination.current_page === pagination.last_page"
                                @click="changePage(pagination.current_page + 1)"
                            >
                                Next
                            </button>
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
                    <form id="clientForm" @submit.prevent="AddClient">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control" id="ref_no" v-model="formState.ref_no" placeholder="e.g. 101">
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
<!--    End add client modal-->

<!--    Edit client modal-->
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
<!--    End edit client modal-->

<!--    Delete client modal-->
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
                                    <p class="client_alert">{{client_alert}}</p>
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
<!--    End Delete client modal-->

<!--    Import client modal-->
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
                                    <small v-if="formState.errors.client_file" class="error text-danger">
                                        {{ formState.errors.client_file }}
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
<!--    End Import client modal-->

</template>

<script setup>
import {ref, reactive, onMounted} from 'vue';
    import axios from "@/axios.js";
    import store from "@/state/index.js";

    const clients = ref([]);
    const pagination = ref({
        current_page: 1,
        last_page: 1,
        total: 0
    });


    const fetchClients = async (page=1) => {
        await axios.get(`/api/clients?page=${page}`)
            .then(response=>{
                clients.value = response.data.data;
                pagination.value = reactive({
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    total: response.data.total
                });

                console.log(response.data)
            }).catch(error=>{

            })
    }

    const changePage = (page) => {
        if(page !== pagination.value.current_page){
            fetchClients(page);
        }
    }

    onMounted(()=>{
        fetchClients();
    });

    const formState = reactive({
        id: '',
        ref_no: '',
        client_name: '',
        client_file: '',
        errors: {}
    })

    const isClientAddModalOpen = ref(false)
    const isClientUpdateModalOpen = ref(false)
    const isClientDeleteModalOpen = ref(false)
    const isClientImportModalOpen = ref(false)

    // --------------Add client module-------------
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
                console.log(response);
                fetchClients();
                formState.ref_no = '';
                formState.client_name = '';
            }).catch(error => {
                if (error.response.status == 422) {
                    formState.errors = Object.keys(error.response.data.errors).reduce((acc, field) => {
                        acc[field] = error.response.data.errors[field][0]; // Get the first error message
                        return acc;
                    }, {});
                } else {
                    formState.errors = {
                        general: 'An unexpected error occurred. Please try again later.'
                    };
                }
            })
        // });
    }
    // --------------End Add client module---------

    // --------------Update client module-----------
    const editClient = async (id) => {
        await axios.get(`/api/clients/${id}`)
            .then(response=>{
                console.log(response.data.client)
                if(response.status === 201 && response.data.client){
                    formState.id = response.data.client.id;
                    formState.ref_no = response.data.client.ref_no;
                    formState.client_name = response.data.client.name;


                } else {
                    console.error('Client data not found or invalid status:', response.data);
                }
            }).catch(error=>{
                console.log(error)
            })
        isClientUpdateModalOpen.value = true
    }

    const closeClientUpdateModal = () => {
        isClientUpdateModalOpen.value = false
    }

    const UpdateClient = () => {
        axios.put(`/api/clients/${formState.id}`, {
            ref_no: formState.ref_no,
            client_name: formState.client_name
        }).then(response=>{
            if(response.status === 201 && response.data.client) {
                fetchClients();
                formState.ref_no = '';
                formState.client_name = '';
            }
        }).catch(error=>{
            if (error.response.status == 422) {
                formState.errors = Object.keys(error.response.data.errors).reduce((acc, field) => {
                    acc[field] = error.response.data.errors[field][0]; // Get the first error message
                    return acc;
                }, {});
            } else {
                formState.errors = {
                    general: 'An unexpected error occurred. Please try again later.'
                };
            }
        })
    }

    // ---------------End update client module-----------

    // ---------------Delete client module---------------

    const client_alert = ref('');
    const deleteClient = async (id) => {
        alert(id);
        formState.id = id;
        await axios.get(`/api/clients/${id}`)
            .then(response=> {
                client_alert.value = `Do you really want to delete user with ref: ${response.data.client.ref_no} ?`;
                isClientDeleteModalOpen.value = true;

            }).catch(error => {

            })
    }
    const deleteClientForm = async () => {
        await axios.delete(`/api/clients/${formState.id}`)
            .then(response=>{
                console.log(response)
                if(response.status === 201) {
                    fetchClients();
                    closeClientDeleteModal();
                }
            }).catch(error=>{
                console.error(error)
                if (error.response.status == 422) {
                    formState.errors = Object.keys(error.response.data.errors).reduce((acc, field) => {
                        acc[field] = error.response.data.errors[field][0]; // Get the first error message
                        return acc;
                    }, {});
                } else {
                    formState.errors = {
                        general: 'An unexpected error occurred. Please try again later.'
                    };
                }
            })
    }

    const closeClientDeleteModal = () => {
        isClientDeleteModalOpen.value = false
    }
    // ---------------End Delete client module-----------


    // ---------------Import client module---------------

    const importClientModel = () => {
        isClientImportModalOpen.value = true
    }
    const closeClientImportModal = () => {
        isClientImportModalOpen.value = false
    }

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            formState.client_file = file; // Update the file in the form state
        }
    }
    const importClientForm = async () => {
        await axios.post('/api/clients/import',{
            client_file: formState.client_file
        },{
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        }).then(response=>{
            fetchClients();
            closeClientImportModal();
        }).catch(error=>{
            console.error(error)
            if (error.response.status == 422) {
                formState.errors = Object.keys(error.response.data.errors).reduce((acc, field) => {
                    acc[field] = error.response.data.errors[field][0]; // Get the first error message
                    return acc;
                }, {});
            } else {
                formState.errors = {
                    general: 'An unexpected error occurred. Please try again later.'
                };
            }
        })
    }
    // ---------------End Import client module-----------
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
