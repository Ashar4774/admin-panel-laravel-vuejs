<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div>
                                <h5 class="mb-0">All Permissions</h5>
                            </div>
                            <div class="d-flex flex-row">
                                <a href="#" class="btn bg-gradient-primary btn-sm mb-0" @click="openPermissionAddModal"
                                    id="addPermission" type="button">+&nbsp; New Permission</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 " id="roleTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Permission
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="permission in permissions" :key="permission.id">
                                        <td class="text-capitalize ps-4 text-sm">
                                            {{ permission.name }}
                                        </td>
                                        <td class="text-center">
                                            <router-link
                                                :to="{ name: 'client.state_of_account', params: { id: permission.id } }"
                                                class="" data-bs-toggle="tooltip" title="Assign to Role">
                                                <i class="fa-solid fa-file-invoice text-secondary"></i>
                                            </router-link>
                                            <a href="#" class="mx-3" data-bs-toggle="tooltip" title="Edit Permission"
                                                @click="updatePermission(permission.id)">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <span>
                                                <i class="cursor-pointer fas fa-trash text-secondary delete-role-btn"
                                                    id="deleteRole" @click="deleteRole(permission.id)"
                                                    data-bs-toggle="tooltip" title="Delete Permission"
                                                    data-id="{{ permission.id }}"></i>
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

    <!-- Add permission modal -->
    <div v-if="isPermissionAddModalOpen" class="modal" id="addPermissionModel" aria-labelledby="addPermissionModelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPermissionModelLabel">Add Permission</h5>
                    <button type="button" class="btn-close bg-dark" @click="closePermissionAddModal">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="permissionForm" @submit.prevent="AddPermission">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name" v-model="formState.name"
                                        placeholder="can-edit, can-delete">
                                    <span v-if="formState.errors.name" class="text-danger ps-2">{{ formState.errors.name
                                        }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn bg-gradient-secondary"
                                @click="closePermissionAddModal">Close</button>
                            <button type="submit" id="submitPermissionBtn" class="btn bg-gradient-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add permission modal -->

    <!-- Update permission modal -->
    <div v-if="isPermissionUpdateModalOpen" class="modal" id="updatePermissionModel"
        aria-labelledby="updatePermissionModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePermissionModelLabel">Update Permission</h5>
                    <button type="button" class="btn-close bg-dark" @click="closePermissionUpdateModal">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="permissionForm" @submit.prevent="UpdatePermissionForm">
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
                                @click="closePermissionUpdateModal">Close</button>
                            <button type="submit" id="submitPermissionBtn"
                                class="btn bg-gradient-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Update permission modal -->
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from '@/axios.js';

const permissions = ref([]);
const formState = ({
    id: '',
    name: '',
    errors: []
});

const FetchPermissions = async () => {
    await axios.get('/api/getPermissions')
        .then(response => {
            permissions.value = response.data.permissions.map(p => {
                return {
                    id: p.id,
                    name: p.name.replace(/-/g, ' ')
                };
            });
        }).catch(error => {
            console.error(error)
        })
}

// Add Permission module
const isPermissionAddModalOpen = ref(false);
const openPermissionAddModal = () => {
    isPermissionAddModalOpen.value = true;
}

const AddPermission = async () => {
    formState.name = formState.name.replace(/ /g, '-')
    await axios.post('/api/permissions', {
        name: formState.name
    }).then(response => {
        formState.name = '';
        FetchPermissions();
        closePermissionAddModal();
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

const closePermissionAddModal = () => {
    formState.name = '';
    isPermissionAddModalOpen.value = false;
}

// Update Permission module
const isPermissionUpdateModalOpen = ref(false);

const updatePermission = async (id) => {
    formState.id = id;
    await axios.get(`/api/permissions/${id}`)
        .then(response => {
            formState.name = response.data.permission.name.replace(/-/g, ' ');
            isPermissionUpdateModalOpen.value = true;
        }).catch(error => {
            console.error(error);
            if (error.status == 422) {
                formState.errors = Object.keys(error.data.errors).reduce((acc, field) => {
                    acc[field] = error.data.errors[field][0]; // Get the first error message
                    return acc;
                }, {});
            } else {
                formState.errors = {
                    general: 'An unexpected error occurred. Please try again later.'
                };
            }
        })
}

const UpdatePermissionForm = async () => {
    formState.name.replace(/ /g, '-');
    await axios.put(`/api/permissions/${formState.id}`,{
        name: formState.name
    }).then(response=>{
        FetchPermissions();
        formState.name = '';
        closePermissionUpdateModal();
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

const closePermissionUpdateModal = () => {
    isPermissionUpdateModalOpen.value = false;
}

onMounted(() => {
    FetchPermissions();
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
