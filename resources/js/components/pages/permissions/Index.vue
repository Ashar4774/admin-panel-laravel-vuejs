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
                                                @click="updateRole(permission.id)">
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
</template>

<script setup>
import {ref, onMounted} from 'vue';
import axios from '@/axios.js';

const permissions = ref([]);
const FetchPermissions = async () => {
    await axios.get('/api/getPermissions')
    .then(response=>{
        console.log(response.data);
        permissions.value = response.data.permissions;
    }).catch(error=>{
        console.error(error)
    })
}

onMounted(()=>{
    FetchPermissions();
})

</script>
