<script setup>

</script>

<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4 mx-4" id="state_of_account_card">
                            <div class="card-header pb-0">
                                <div class="d-flex flex-column flex-md-row justify-content-between">
                                    <div class="d-flex flex-column">
                                        <h5 class="mb-3 ">State of Account</h5>
                                        <span class="d-flex flex-row">
                                            <span class="d-flex flex-column">
                                                <span class="mb-2 text-xs">Client Ref No: <span class="text-dark ms-sm-2 font-weight-bold" id="soa_client_name">{{ client.ref_no ?? '001' }}</span></span>
                                                <span class="mb-2 text-xs">Client Name: <span class="text-dark ms-sm-2 font-weight-bold" id="soa_client_name">{{ client.name ?? 'Client Name' }}</span></span>
                                                <span class="text-xs">Date: <span class="text-dark ms-sm-2 font-weight-bold">{{ formattedDate }}</span></span>
                                            </span>
                                            <span class="">
                                                <button id="downloadPDF" class="btn btn-link mb-0" @click="downloadPDF" v-if="client?.invoices?.length > 0">Download PDF</button>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table table-striped align-items-center mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Description
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Date
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Amount
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="soa_table_body">
                                        <template v-if="client?.invoices?.length > 0">
                                            <template v-for="(invoice, index) in client.invoices" :key="index">
                                                <tr>
                                                    <td class="ps-4">
                                                        <p class="text-xs font-weight-bold mb-0"></p>
                                                    </td>
                                                    <td class="ps-4">
                                                        <p class="text-xs font-weight-bold mb-0">{{ formatDueDate(invoice.due_date) }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ invoice.amount }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-4">
                                                        <p class="text-xs font-weight-bold mb-0">Client paid</p>
                                                    </td>
                                                    <td class="ps-4">
                                                        <p class="text-xs font-weight-bold mb-0">{{ invoice.rcd_due_date ? formatDueDate(invoice.rcd_due_date) : '-' }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ invoice.rcd_amount ?? '-' }}</p>
                                                    </td>
                                                </tr>
                                            </template>

                                            <!-- Balance outstanding row -->
                                            <tr class="bg-warning text-white">
                                                <td colspan="2" class="ps-4">Balance outstanding now</td>
                                                <td>{{ client.arrears - client.bad_debt }}</td>
                                            </tr>

                                            <!-- Bad debts row -->
                                            <tr class="bg-gradient-danger text-white">
                                                <td colspan="2" class="ps-4">Bad debts</td>
                                                <td>{{ client.bad_debt }}</td>
                                            </tr>
                                        </template>

                                        <!-- No data fallback -->
                                        <tr v-else class="text-center">
                                            <td colspan="3">
                                                <p class="text-xs font-weight-bold mb-0">No data yet.</p>
                                            </td>
                                        </tr>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {onMounted, ref} from "vue";

    const prop = defineProps(['id']);
    const formattedDate = ref('')

    const client = ref([]);

    const fetchUserDetail = () => {
        axios.get(`/api/clients/${prop.id}`)
            .then(response=>{
                if(response.status == 201){
                    client.value = response.data.client;
                }
            }).catch(error=>{
                console.error(error)
            })
    }

    const formatDueDate = (date) => {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    }

    onMounted(()=>{
        fetchUserDetail();
        formatDueDate();

        const now = new Date()
        const day = String(now.getDate()).padStart(2, '0')
        const month = String(now.getMonth() + 1).padStart(2, '0')
        const year = String(now.getFullYear()).slice(-2)

        formattedDate.value = `${day}/${month}/${year}`
    })

    const downloadPDF = () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const soa_client_name = $('#soa_client_name').text();

        $('#downloadPDF').addClass('d-none');
        $('.table').removeClass('table-striped');

        // Capture the HTML content
        const content = document.getElementById('state_of_account_card');
        doc.html(content, {
            callback: function (doc) {
                doc.save(`${soa_client_name} - State of Account`);
                $('#downloadPDF').removeClass('d-none');
                $('.table').addClass('table-striped');
            },
            x: 10,
            y: 10,
            orientation: 'landscape',
            format: 'a4',
            width: doc.internal.pageSize.getWidth() - 20,
            windowWidth: content.scrollWidth,
            height: doc.internal.pageSize.getHeight()
        });
    }
</script>

<style scoped>

</style>
