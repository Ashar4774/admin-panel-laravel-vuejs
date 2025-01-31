<template>
    <tr v-if="clients" v-for="client in clients" :key="client.id">
        <td class="ps-4">{{ client.ref_no }}</td>
        <td>{{ client.name }}</td>
        <td>{{ client.arrears ?? 0  }}</td>
        <td>{{ client.bad_debts ?? 0 }}</td>
        <td class="text-center">
            <a href="#" class="mx-3" data-bs-toggle="tooltip" title="View User">
                <i class="fa-solid fa-eye text-secondary"></i>
            </a>
            <router-link :to="{ name: 'client.state_of_account', params:{id: client.id} }" class="" data-bs-toggle="tooltip" title="State of Account">
                <i class="fa-solid fa-file-invoice text-secondary"></i>
            </router-link>
            <a href="#" class="mx-3" @click="editClient(client.id)" data-bs-toggle="tooltip" title="Edit User" data-id="{{ client.id  }}">
                <i class="fas fa-user-edit text-secondary"></i>
            </a>
            <span>
                 <i class="cursor-pointer fas fa-trash text-secondary" @click="deleteClient(client.id)" id="deleteClient" data-bs-toggle="tooltip" title="Delete User" data-id="{{ client.id  }}"></i>
            </span>
        </td>
    </tr>
</template>

<script setup>
const prop = defineProps(['clients', 'editClient', 'deleteClient'])
</script>

<style scoped>

</style>
