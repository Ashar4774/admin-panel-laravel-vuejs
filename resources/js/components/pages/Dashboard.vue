<template>
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Invoice Amount</p>
                                <h5 class="font-weight-bolder mb-0">
                                    ${{ totals.totalAmounts }}
<!--                                    <span class="text-success text-sm font-weight-bolder">{{ $totalAmounts }}</span>-->
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Payment</p>
                                <h5 class="font-weight-bolder mb-0">
                                    ${{ totals.totalPayments }}
<!--                                    <span class="text-success text-sm font-weight-bolder">+3%</span>-->
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="far fa-money-bill-alt text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Arrears</p>
                                <h5 class="font-weight-bolder mb-0">
                                    ${{ totals.totalArrears }}
<!--                                    <span class="text-danger text-sm font-weight-bolder">-2%</span>-->
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-thumbs-up text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Bad Debts</p>
                                <h5 class="font-weight-bolder mb-0">
                                    ${{ totals.totalBad_debts }}
<!--                                    <span class="text-success text-sm font-weight-bolder">+5%</span>-->
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                <i class="fas fa-thumbs-down text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import {ref, onMounted} from 'vue';
import axios from "@/axios.js";

const totals = ref([]);
const fetchData = async () => {
    await axios.get('/api/dashboard')
        .then(response => {
            console.log(response.data)
            totals.value = response.data
            console.log(totals)
        }).catch(error=>{
            console.error(error);
        })
}

onMounted(()=>{
    fetchData();
})
</script>
