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
                                <a href="#" class="btn bg-gradient-primary btn-sm mb-0" id="addRole"
                                    type="button">+&nbsp; New Role</a>
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
                                            <a href="#" class="mx-3"
                                                data-bs-toggle="tooltip" title="Edit Role" data-id="{{ role.id  }}">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <span>
                                                <i class="cursor-pointer fas fa-trash text-secondary"
                                                    id="deleteClient"
                                                    data-bs-toggle="tooltip" title="Delete Role"
                                                    data-id="{{ role.id  }}"></i>
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
import { onMounted, ref } from 'vue';
import axios from '@/axios.js';

const roles = ref([]);

const FetchRoles = async () => {
    await axios.get('/api/getRoles')
        .then(response => {
            roles.value = response.data.roles;
            console.log(response.data);
        }).catch(error => {

        });
}

onMounted(() => {
    FetchRoles();
})
</script>
