<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div>
                                <h5 class="mb-0">All Roles</h5>
                            </div>
                            <div class="d-flex flex-row">
                                <a href="#" class="btn bg-gradient-primary btn-sm mb-0" @click="openRoleAddModal"
                                    id="addRole" type="button">+&nbsp; New Role</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 " id="roleTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Role
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="role in roles" :key="role.id">
                                        <td class="text-capitalize ps-4 text-sm">
                                            {{ role.name }}
                                        </td>
                                        <td class="text-center">
                                            <router-link
                                                :to="{ name: 'client.state_of_account', params: { id: role.id } }"
                                                class="" data-bs-toggle="tooltip" title="Assign Permission">
                                                <i class="fa-solid fa-file-invoice text-secondary"></i>
                                            </router-link>
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" title="Edit Role"
                                                data-id="{{ role.id  }}">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <span>
                                                <i class="cursor-pointer fas fa-trash text-secondary delete-role-btn" id="deleteRole"
                                                    @click="deleteRole(role.id)"
                                                    data-bs-toggle="tooltip" title="Delete Role"
                                                    data-id="{{ role.id }}"></i>
                                            </span>
                                        </td>
                                    </tr>
                                    <!-- <FetchClients :clients="clients" :editClient="editClient" :deleteClient="deleteClient"/> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role modal -->
    <div v-if="isRoleAddModalOpen" class="modal" id="addRoleModel" aria-labelledby="addRoleModelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleModelLabel">Add Role</h5>
                    <button type="button" class="btn-close bg-dark" @click="closeRoleAddModal">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="roleForm" @submit.prevent="AddRole">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name" v-model="formState.name"
                                        placeholder="e.g., admin, staff">
                                    <span v-if="formState.errors.name" class="text-danger ps-2">{{ formState.errors.name
                                        }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn bg-gradient-secondary"
                                @click="closeRoleAddModal">Close</button>
                            <button type="submit" id="submitRoleBtn" class="btn bg-gradient-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Role modal -->

     <!-- Delete Role modal -->
      <div v-if="isRoleDeleteModalOpen" class="modal" id="addRoleModel" aria-labelledby="addRoleModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleModelLabel">Delete Role</h5>
                    <button type="button" class="btn-close bg-dark" @click="closeRoleDeleteModal">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" @submit.prevent="deleteRoleForm">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="Role_id" v-model="formState.id">
                                    <p class="role_alert">{{role_alert}}</p>
                                </div>
                            </div>

                        </div>
                        <div v-if="formState.name == 'admin'">
                            <button type="button" class="btn bg-gradient-danger" @click="closeRoleDeleteModal">ok</button>
                        </div>
                        <div v-else>
                            <button type="button" class="btn bg-gradient-danger" @click="closeRoleDeleteModal">No</button>
                            <button type="submit" id="deleteRoleBtn" class="btn bg-gradient-success">Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <!-- End Delete Role modal -->
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue';
import axios from '@/axios.js';

const roles = ref([]);
const formState = reactive({
    id: '',
    name: '',
    errors: []
});

const FetchRoles = async () => {
    await axios.get('/api/getRoles')
        .then(response => {
            roles.value = response.data.roles;
        }).catch(error => {

        });
}

// Add role module
const isRoleAddModalOpen = ref(false);

const openRoleAddModal = () => {
    isRoleAddModalOpen.value = true;
}

const closeRoleAddModal = () => {
    isRoleAddModalOpen.value = false;
}

const AddRole = async () => {
    await axios.post('/api/roles', {
        name: formState.name
    }).then(response => {
        formState.name = '';
        FetchRoles();
        closeRoleAddModal();
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
}

// Add role module

// Delete role module
const isRoleDeleteModalOpen = ref(false);
const role_alert = ref('');

const deleteRole = async (id) => {
    formState.id = id;
    await axios.get(`/api/roles/${id}`)
    .then(response=>{
        formState.name = response.data.role.name;
        if(response.data.role.name == 'admin'){
            role_alert.value = `You are not able to delete ${formState.name} role?`
            isRoleDeleteModalOpen.value = true;
        } else {
            role_alert.value = `Do you really want to delete ${formState.name} role?`
            isRoleDeleteModalOpen.value = true;
        }
    }).catch(error=>{
        console.error(error);
    });
}

const deleteRoleForm = () => {

}

const closeRoleDeleteModal = () => {
    isRoleDeleteModalOpen.value = false;
}

// Delete role module
onMounted(() => {
    FetchRoles();
})
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
