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
                                    <FetchClients :clients="clients" :editClient="editClient" :deleteClient="deleteClient"/>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="d-flex justify-content-start align-items-center px-3 mb-2">
                                <label for="perPage" class="me-2 mb-0">Show:</label>
                                <select id="perPage" v-model="perPage" @change="changePerPage" class="form-select form-select-sm w-auto">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span class="ms-2">entries</span>
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
    </div>

<!--    Add client modal-->
    <ClientAddModal :isClientAddModalOpen="isClientAddModalOpen" :formState="formState" @close="closeClientAddModal" @clientAdded="fetchClients"/>
<!--    End add client modal-->

<!--    Edit client modal-->
    <ClientUpdateModal :isClientUpdateModalOpen="isClientUpdateModalOpen" :formState="formState" @close="closeClientUpdateModal" @fetchClients="fetchClients"   />
<!--    End edit client modal-->

<!--    Delete client modal-->
    <ClientDeleteModal  :formState="formState" :isClientDeleteModalOpen="isClientDeleteModalOpen" :client_alert="client_alert" @close="closeClientDeleteModal" @fetchClients="fetchClients" />
<!--    End Delete client modal-->

<!--    Import client modal-->
    <ClientImportModal :isClientImportModalOpen="isClientImportModalOpen" :formState="formState" @fetchClients="fetchClients" @close="closeClientImportModal" />
<!--    End Import client modal-->

</template>

<script setup>
import {ref, reactive, onMounted, nextTick} from 'vue';
    import axios from "@/axios.js";
    import store from "@/state/index.js";
    import FetchClients from "@/components/pages/clients/partials/FetchClients.vue";
    import ClientAddModal from "@/components/pages/clients/partials/ClientAddModal.vue";
    import ClientUpdateModal from "@/components/pages/clients/partials/ClientUpdateModal.vue";
    import ClientDeleteModal from "@/components/pages/clients/partials/ClientDeleteModal.vue";
    import ClientImportModal from "@/components/pages/clients/partials/ClientImportModal.vue";

    const clients = ref([]);
    const pagination = ref({
        current_page: 1,
        last_page: 1,
        total: 0
    });
    const perPage = ref(10);

    const fetchClients = async (page=1) => {
        await axios.get(`/api/clients?page=${page}&per_page=${perPage.value}`)
            .then(response=>{
                clients.value = response.data.data;
                pagination.value = reactive({
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    total: response.data.total
                });


            }).catch(error=>{

            })
    }

    const changePage = (page) => {
        if(page !== pagination.value.current_page){
            fetchClients(page);
        }
    }

    const changePerPage = () => {
        fetchClients(1);
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
        isClientAddModalOpen.value = true;

    }

    const closeClientAddModal = () => {
        formState.ref_no = '';
        formState.client_name = '';
        isClientAddModalOpen.value = false
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
                    isClientUpdateModalOpen.value = true

                } else {
                    console.error('Client data not found or invalid status:', response.data);
                }
            }).catch(error=>{
                console.log(error)
            })

    }

    const closeClientUpdateModal = () => {
        formState.ref_no = '';
        formState.client_name = '';
        isClientUpdateModalOpen.value = false
    }
    // ---------------End update client module-----------

    // ---------------Delete client module---------------

    const client_alert = ref('');
    const deleteClient = async (id) => {
        formState.id = id;
        await axios.get(`/api/clients/${id}`)
            .then(response=> {
                client_alert.value = `Do you really want to delete user with Ref. No. ${response.data.client.ref_no}?`;
                isClientDeleteModalOpen.value = true;

            }).catch(error => {

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
    // ---------------End Import client module-----------
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
