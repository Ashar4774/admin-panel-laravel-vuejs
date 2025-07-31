<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-xl-4 mb-xl-0 mb-4">
                        <div class="card bg-transparent shadow-xl">
                            <div class="overflow-hidden position-relative border-radius-xl" style="background-image: url('../assets/img/curved-images/curved14.jpg');">
                                <span class="mask bg-gradient-dark"></span>
                                <div class="card-body position-relative z-index-1 p-3">
                                    <h5 class="text-white mt-4 mb-5 pb-2">{{ client.name }}</h5>
                                    <div class="d-flex">
                                        <div class="d-flex">
                                            <div class="me-4">
                                                <p class="text-white text-sm opacity-8 mb-0">Ref #</p>
                                                <h6 class="text-white mb-0">{{ client.ref_no }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header mx-4 p-3 text-center">
                                        <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                                            <i class="far fa-money-bill-alt opacity-10"></i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 p-3 text-center">
                                        <h6 class="text-center mb-0 text-sm font-weight-bold">Total Payment</h6>
                                        <hr class="horizontal dark my-3">
<!--                                        <h5 class="mb-0">$0</h5>-->
                                        <h5 class="mb-0">${{ (client.payment).toFixed(2) }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header mx-4 p-3 text-center">
                                        <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                                            <i class="fas fa-thumbs-up opacity-10"></i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 p-3 text-center">
                                        <h6 class="text-center mb-0 text-sm font-weight-bold">Arrears</h6>
                                        <hr class="horizontal dark my-3">
                                        <h5 class="mb-0">${{ ( client.arrears - client.bad_debt ).toFixed(2) }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-md-0 mt-4">
                                <div class="card">
                                    <div class="card-header mx-4 p-3 text-center">
                                        <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                                            <i class="fas fa-thumbs-down opacity-10"></i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 p-3 text-center">
                                        <h6 class="text-center mb-0 text-sm font-weight-bold">Bad Debts</h6>
                                        <hr class="horizontal dark my-3">
                                        <h5 class="mb-0">${{ (client.bad_debt).toFixed(2) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card" style="height: 450px;">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <h6 class="mb-0">Invoices</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-0" style="max-height: 450px; overflow-x: auto;">
                        <ul class="list-group">
                            <li v-if="client.invoices" v-for="invoice in client.invoices" :key="invoice.id" class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ formatDate(invoice.due_date) }}</h6>
                                    <span class="text-xs">#{{ invoice.id }}</span>

                                </div>
                                <div class="d-flex align-items-center text-sm">
                                    ${{ invoice.amount }}
                                </div>
                            </li>
                            <li v-else class="list-group-item border-0 d-flex justify-content-center ps-0 mb-2 border-radius-lg">
                                <span class="text-xs">No invoice generated yet.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import { format } from 'date-fns';

const prop = defineProps(['id']);
const client = ref('');
const fetchUserDetail = async () => {
    await axios.get(`/api/clients/${prop.id}`)
        .then(response=>{
            if(response.status == 201){
                client.value = response.data.client;
            }
        }).catch(error=>{
            console.error(error)
        })
}

const formatDate = (date) => {
    return date ? format(new Date(date), 'MMMM dd, yyyy') : 'N/A';
};

onMounted(()=>{
    fetchUserDetail();
    formatDate();
})



</script>

<style scoped>

</style>
