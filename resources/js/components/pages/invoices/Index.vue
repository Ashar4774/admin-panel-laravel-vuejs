<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div>
                                <h5 class="mb-0">Filter</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <form id="filterInvoiceForm" @submit.prevent="filterInvoiceForm" class="p-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_clients_ref" class="form-label">Ref #</label>
                                        <input type="text" list="clientList" class="form-control form-control-sm filter_clients_ref" id="filter_clients_ref" @change="change_clients_list" v-model="formFilter.filter_clients_ref" placeholder="Client Ref No.">
                                        <datalist id="clientList" >
                                            <option v-for="client in clients" :key="client.id" :value="client.ref_no" :data-name="client.name" :data-ref="client.ref_no" :data-id="client.id">{{ client.ref_no }}</option>
                                        </datalist>
                                        <input type="number" class="form-control form-control-sm d-none filter_clients_id" id="filter_clients_id" v-model="formFilter.filter_clients_id" placeholder="Client Ref No." readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_client_name" class="form-label">Client Name</label>
                                        <input type="text" v-model="formFilter.filter_client_name" list="clientNameList" id="filter_client_name" class="form-control form-control-sm filter_client_name" @change="change_clients_list" placeholder="Select Client" >
                                        <datalist id="clientNameList" >
                                            <option v-for="client in clients" :key="client.id" :value="client.name" :data-name="client.name" :data-ref="client.ref_no" :data-id="client.id">{{ client.name }}</option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_amount" class="form-label">Amount</label>
                                        <input type="number" class="form-control form-control-sm" id="filter_amount" v-model="formFilter.filter_amount" placeholder="Amount" >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_due_date" class="form-label">Due Date</label>
                                        <input type="date" class="form-control form-control-sm" id="filter_due_date" v-model="formFilter.filter_due_date" placeholder="Due Date">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_rcd_amount" class="form-label">Received Amount</label>
                                        <input type="number" class="form-control form-control-sm" id="filter_rcd_amount" v-model="formFilter.filter_rcd_amount" placeholder="Received Amount" >
                                        <input type="checkbox" class="ms-2" id="null_rcd_amount" v-model="formFilter.filter_null_rcd_amount">
                                        <label class="form-check-label" for="null_rcd_amount">Nullable</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_rcd_due_date" class="form-label">Received Date</label>
                                        <input type="date" class="form-control form-control-sm" id="filter_rcd_due_date" v-model="formFilter.filter_rcd_due_date" placeholder="Received Date">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_client_status" class="form-label">Client Status</label>
                                        <select class="form-control form-control-sm" v-model="formFilter.filter_client_status" id="filter_client_status">
                                            <option value="" selected>Select client status</option>
                                            <option value="bad_debts">Bad debt</option>
                                            <option value="good">Received</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_payment_type" class="form-label">Payment Type</label>
                                        <select class="form-control form-control-sm" v-model="formFilter.filter_payment_type" id="filter_payment_type">
                                            <option value="" selected>Select payment type</option>
                                            <option value="bank">Bank</option>
                                            <option value="cash">Cash</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_bad_debt_amount" class="form-label">Bad Debt. Amount</label>
                                        <input type="number" class="form-control form-control-sm" id="filter_bad_debt_amount" name="filter_bad_debt_amount" placeholder="Bad Debt. Amount" >
                                        <input type="checkbox" class="ms-2" id="null_bad_debt_amount" v-model="formFilter.filter_null_bad_debt_amount">
                                        <label class="form-check-label" for="null_bad_debt_amount">Nullable</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_status" class="form-label">Status</label>
                                        <select class="form-control form-control-sm" v-model="formFilter.filter_status" id="filter_status">
                                            <option value="" selected>Select Status</option>
                                            <option value="paid">Paid</option>
                                            <option value="unpaid">Unpaid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex gap-2 pt-4">
                                    <button type="button" id="clearFilterBtn" class="btn bg-gradient-secondary w-100 h-50">Clear</button>
                                    <button type="submit" id="submitFilterInvoiceBtn" class="btn bg-gradient-success w-100 h-50">Filter</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div>
                                <h5 class="mb-0">All Invoices</h5>
                            </div>
                            <div class="d-flex flex-row">
                                <a href="#" class="btn bg-gradient-faded-info btn-sm mb-0 text-white mx-1" @click="importInvoiceModelOpen" type="button" >Import Invoice(s)</a>
                                <a href="#" class="btn bg-gradient-primary btn-sm mb-0" id="addInvoice" @click="addInvoiceModelOpen" type="button">+&nbsp; New Invoice</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="invoiceTable">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Invoice #
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Client Ref
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Client Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        amount
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Due date
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Year
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Received Amount
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Received date
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Time gap (days)
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Bad Debt amount
                                    </th>
<!--                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        client status
                                    </th>-->
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Payment Type
                                    </th>
<!--                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Notes
                                    </th>-->
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Invoice status
                                    </th>

                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                </tr>
                                </thead>
<!--                                <tbody>
                                    <FetchInvoices :invoices="invoices" :updateInvoiceModelOpen="updateInvoiceModelOpen" :deleteInvoiceModelOpen="deleteInvoiceModelOpen" />
                                </tbody>-->
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
                    <div class="card-footer px-0 pt-2 pb-0 ps-4">
                        <div class="d-flex " style="gap: 20px">
                                    <span class="mb-2 text-xs d-flex">Unpaid:
                                        <span class="text-dark font-weight-bold ms-sm-2 unpaid" style="width: 40px;background-image: linear-gradient(310deg, #627594, #a8b8d8);height: 15px;display: inline-block;">
                                        </span>
                                    </span>
                            <span class="mb-2 text-xs d-flex">Paid:
                                        <span class="text-dark ms-sm-2 font-weight-bold" style="width: 40px;background-image: linear-gradient(310deg, #17ad37, #98ec2d);height: 15px;display: inline-block;"></span></span>
                            <span class="text-xs d-flex">Bad Debt: <span class="text-dark ms-sm-2 font-weight-bold" style="width: 40px;background-image: linear-gradient(310deg, #ea0606, #ff667c);height: 15px;display: inline-block;"></span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--    Modal for Add invoice-->
    <InvoiceAddModal :addInvoiceModel="addInvoiceModel" :formState="formState" :clients="clients" @close="addInvoiceModelClose" @fetchInvoices="fetchInvoices"  />
<!--    End Modal for Add invoice-->

<!--    Modal for Update invoice-->
    <InvoiceUpdateModal :updateInvoiceModel="updateInvoiceModel" :formState="formState" :clients="clients" :selectedInvoiceId="selectedInvoiceId" @fetchInvoices="fetchInvoices" @updateInvoiceModelClose="updateInvoiceModelClose" />
<!--    End Modal for Update invoice-->

<!-- Modal for Delete invoice -->
    <InvoiceDeleteModal :deleteInvoiceModel="deleteInvoiceModel" :formState="formState" :invoice_alert="invoice_alert" @fetchInvoices="fetchInvoices" @closeDeleteInvoiceModel="closeDeleteInvoiceModel" />
<!-- End Modal for Delete invoice -->

<!--    Modal for Import Invoice-->
    <InvoiceImportModal :importInvoiceModel="importInvoiceModel" :formState="formState" @fetchInvoices="fetchInvoices" @importInvoiceModelClose="importInvoiceModelClose" />
<!--    End Modal for Import Invoice-->
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import InvoiceAddModal from "@/components/pages/invoices/partials/InvoiceAddModal.vue";
import InvoiceUpdateModal from "@/components/pages/invoices/partials/InvoiceUpdateModal.vue";
import InvoiceDeleteModal from "@/components/pages/invoices/partials/InvoiceDeleteModal.vue";
import InvoiceImportModal from "@/components/pages/invoices/partials/InvoiceImportModal.vue";
import FetchInvoices from "@/components/pages/invoices/partials/FetchInvoices.vue";
import axios from "@/axios.js";
import 'datatables.net';
import 'datatables.net-bs5';

// variables for modal
const addInvoiceModel = ref(false);
const deleteInvoiceModel = ref(false);
const importInvoiceModel = ref(false);
const updateInvoiceModel = ref(false);

const formFilter = reactive({
    filter_clients_ref: '',
    filter_clients_id: '',
    filter_client_name: '',
    filter_amount: '',
    filter_due_date: '',
    filter_rcd_amount: '',
    filter_null_rcd_amount: false,
    filter_rcd_due_date: '',
    filter_client_status: '',
    filter_payment_type: '',
    filter_bad_debt_amount: '',
    filter_null_bad_debt_amount: false,
    filter_status: '',

});

const change_clients_list = (e) => {
    console.log(formFilter.filter_clients_ref);
    console.log(clients.value);

    const client = clients.value.find(c => c.ref_no === formFilter.filter_clients_ref) || clients.value.find(c => c.name === formFilter.filter_client_name)
    console.log(client);
    if (client) {
        formFilter.filter_clients_ref = client.ref_no
        formFilter.filter_clients_id = client.id
        formFilter.filter_client_name = client.name
    } else {
        formFilter.filter_clients_ref = ''
        formFilter.filter_clients_id = ''
        formFilter.filter_client_name = ''
    }
}

const filterInvoiceForm = () => {
    //
}

const formState = reactive({
    id: '',
    invoice_year: '',
    clients_id: '',
    amount: '',
    due_date: '',
    rcd_amount: '',
    rcd_due_date: '',
    status: '',
    payment_type: '',
    bad_debt_amount: '',
    notes: '-',
    invoice_file: '',
    errors: {}
});

const clients = ref([]);
const invoices = ref([]);
const selectedInvoiceId = ref('');


const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0
});

const perPage = ref(10);


// Get the current date
const today = new Date();

// Get the current year and month
const currentYear = today.getFullYear(); // e.g., 2024
const currentMonth = today.getMonth(); // January = 0, June = 5

// Calculate the fiscal year
const startYear = ref(''), endYear= ref('');
if (currentMonth >= 4) {
    startYear.value = currentYear % 100; // Last two digits of the current year
    endYear.value = (currentYear + 1) % 100; // Last two digits of the next year
} else { // If it's before June
    startYear.value = (currentYear - 1) % 100; // Last two digits of the previous year
    endYear.value = currentYear % 100; // Last two digits of the current year
}

// Set the value of the input field
formState.invoice_year = (startYear.value + '-' + endYear.value);

onMounted(() => {
    fetchClients();
    fetchDataTableInvoices(1);
    fetchInvoices();
})

const fetchInvoices = async (page) => {
    await axios.get(`/api/invoices?page=${page}&per_page=${perPage.value}`)
        .then(response=>{
            invoices.value = response.data.data;
            pagination.value = reactive({
                current_page: response.data.current_page,
                last_page: response.data.last_page,
                total: response.data.total
            });
        }).catch(error=>{

        })
}

const fetchDataTableInvoices = (page) => {
    $('#invoiceTable').dataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: `/api/invoices/getInvoices?page=${page}&per_page=${perPage.value}`,
            type: 'GET',
            data: function(d){
                console.log(d);
                d.clients_id = formFilter.filter_clients_id
                d.inv_client_name = formFilter.filter_client_name
                d.amount = formFilter.filter_amount
                d.due_date = formFilter.filter_due_date
                d.rcd_amount = formFilter.filter_rcd_amount
                d.rcd_due_date = formFilter.filter_rcd_due_date
                d.status = formFilter.filter_client_status
                d.payment_type = formFilter.filter_payment_type
                d.bad_debt_amount = formFilter.filter_bad_debt_amount
                d.rcd_amount_nullable = formFilter.filter_null_rcd_amount ? 1 : null
                d.bad_debt_amount_nullable = formFilter.filter_null_bad_debt_amount ? 1 : null
                d.invoice_status = formFilter.filter_status
            },
            beforeSend: function (xhr) {
                const token = localStorage.getItem('token');
                if (token) {
                    xhr.setRequestHeader('Authorization', `Bearer ${token}`);
                }
            }
        },
        columns: [
            { data: 'id' },
            { data: 'ref_no' },
            { data: 'client_name' },
            { data: 'amount' },
            { data: 'due_date' },
            { data: 'invoice_year' },
            { data: 'rcd_amount' },
            { data: 'rcd_due_date' },
            { data: 'time_gap' },
            { data: 'bad_debt_amount' },
            // { data: 'status' },
            { data: 'payment_type' },
            { data: 'invoice_status' },
            { data: 'actions', searchable: true }
        ],
        createdRow: function (row, data, dataIndex) {
            // $(row).addClass(data.row_class);
            $('td', row).eq(0).addClass('ps-4 invoice_id');
            $('td', row).eq(1).addClass('ref_no');
            $('td', row).eq(2).addClass('client_name');
            $('td', row).eq(3).addClass('amount');
            $('td', row).eq(4).addClass('due_date');
            $('td', row).eq(5).addClass('invoice_year');
            $('td', row).eq(6).addClass('rcd_amount');
            $('td', row).eq(7).addClass('rcd_date');
            $('td', row).eq(8).addClass('time_gap');
            $('td', row).eq(9).addClass('bad_debt_amount');
            $('td', row).eq(10).addClass('client_status');
            $('td', row).eq(11).addClass('payment_status');
            // $('td', row).eq(12).addClass('notes');
            $('td', row).eq(13).addClass('invoice_status');
            $('td', row).eq(14).addClass('text-center bg-white');
        },
        pageLength: 10,
        lengthMenu: [10, 20, 50, 100]
    })
}

const changePage = (page) => {
    if(page !== pagination.value.current_page){
        fetchInvoices(page);
    }
}

const changePerPage = () => {
    fetchInvoices(1);
}

// -------- Add Invoice module --------------
const fetchClients = async () => {
    await axios.get(`/api/invoices/fetchClients`)
        .then(response=>{
            clients.value = response.data;
        }).catch(error=>{
            console.log(error)
        })
}

const addInvoiceModelOpen = () => {
    addInvoiceModel.value = true
};

const addInvoiceModelClose = () => {
    addInvoiceModel.value = false
};
// --------- End Add Invoice module ----------

// --------- Update Invoice module ------------
const updateInvoiceModelOpen = (id) => {
    formState.id = id
    axios.get(`/api/invoices/${id}`)
        .then(response=>{
            if(response.status === 201 && response.data.invoice){

                formState.id = response.data.invoice.id,
                formState.invoice_year = response.data.invoice.invoice_year,
                formState.clients_id = response.data.invoice.clients_id,
                formState.amount = response.data.invoice.amount,
                formState.due_date = response.data.invoice.due_date,
                formState.rcd_amount = response.data.invoice.rcd_amount,
                formState.rcd_due_date = response.data.invoice.rcd_due_date,
                formState.status = response.data.invoice.status,
                formState.payment_type = response.data.invoice.payment_type,
                formState.bad_debt_amount = response.data.invoice.bad_debt_amount,
                formState.notes =  response.data.invoice.notes

                updateInvoiceModel.value = true
                selectedInvoiceId.value = id;
            } else {
                console.error('Invoice data not found or invalid status:', response.data);
            }

        }).catch(error=>{

    })
};

const updateInvoiceModelClose = () => {
    updateInvoiceModel.value = false
};
// --------- End Update Invoice module --------

// --------- Delete Invoice module -------------
const invoice_alert = ref('');
const deleteInvoiceModelOpen = (id) => {
    formState.id = id
    axios.get(`/api/invoices/${id}`)
        .then(response=>{
            console.log(response.data);
            deleteInvoiceModel.value = true
            invoice_alert.value = `Do you really want to delete Invoice # ${response.data.invoice.id} ?`
        }).catch(error=>{

    })

}

const closeDeleteInvoiceModel = () => {
    invoice_alert.value = '';
    deleteInvoiceModel.value = false
}
// --------- End Delete Invoice module ---------

// ------------ Import Invoice module ------------------
const importInvoiceModelOpen = () => {
    importInvoiceModel.value = true;
}

const importInvoiceModelClose = () => {
    importInvoiceModel.value = false;
}
// ------------ End Import Invoice module --------------

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
