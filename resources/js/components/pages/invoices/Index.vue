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
                        <form method="POST" id="filterInvoiceForm" class="p-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_clients_ref" class="form-label">Ref #</label>
                                        <input type="number" list="clientList" class="form-control form-control-sm filter_clients_ref" id="filter_clients_ref" name="clients_id" placeholder="Client Ref No.">
                                        <datalist id="clientList" >
<!--                                            @foreach($clients as $client)
                                            <option value="{{ $client->ref_no }}" data-name="{{ $client->name }}" data-ref="{{ $client->ref_no }}" data-id="{{ $client->id }}">{{ $client->ref_no }}</option>
                                            @endforeach-->
                                        </datalist>
                                        <input type="number" class="form-control form-control-sm d-none filter_clients_id" id="filter_clients_id" name="clients_id" placeholder="Client Ref No." readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_client_name" class="form-label">Client Name</label>
                                        <input name="inv_client_name" list="clientNameList" id="filter_client_name" class="form-control form-control-sm filter_client_name" placeholder="Select Client" autocomplete="off">
                                        <datalist id="clientNameList" >
<!--                                            @foreach($clients as $client)
                                            <option value="{{ $client->name }}" data-name="{{ $client->name }}" data-ref="{{ $client->ref_no }}" data-id="{{ $client->id }}">{{ $client->name }}</option>
                                            @endforeach-->
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_amount" class="form-label">Amount</label>
                                        <input type="number" class="form-control form-control-sm" id="filter_amount" name="amount" placeholder="Amount" >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_due_date" class="form-label">Due Date</label>
                                        <input type="date" class="form-control form-control-sm" id="filter_due_date" name="due_date" placeholder="Due Date">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_rcd_amount" class="form-label">Received Amount</label>
                                        <input type="number" class="form-control form-control-sm" id="filter_rcd_amount" name="rcd_amount" placeholder="Received Amount" >
                                        <input type="checkbox" class="ms-2" id="null_rcd_amount">
                                        <label class="form-check-label" for="null_rcd_amount">Nullable</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_rcd_due_date" class="form-label">Received Date</label>
                                        <input type="date" class="form-control form-control-sm" id="filter_rcd_due_date" name="rcd_due_date" placeholder="Received Date">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_client_status" class="form-label">Client Status</label>
                                        <select class="form-control form-control-sm" name="status" id="filter_client_status">
                                            <option value="" selected>Select client status</option>
                                            <option value="bad_debts">Bad debt</option>
                                            <option value="good">Received</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_payment_type" class="form-label">Payment Type</label>
                                        <select class="form-control form-control-sm" name="payment_type" id="filter_payment_type">
                                            <option value="" selected>Select payment type</option>
                                            <option value="bank">Bank</option>
                                            <option value="cash">Cash</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_bad_debt_amount" class="form-label">Bad Debt. Amount</label>
                                        <input type="number" class="form-control form-control-sm" id="filter_bad_debt_amount" name="bad_debt_amount" placeholder="Bad Debt. Amount" >
                                        <input type="checkbox" class="ms-2" id="null_bad_debt_amount">
                                        <label class="form-check-label" for="null_bad_debt_amount">Nullable</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="filter_status" class="form-label">Status</label>
                                        <select class="form-control form-control-sm" name="invoice_status" id="filter_status">
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        client status
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Payment Type
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Notes
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Invoice status
                                    </th>

                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                            </table>
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
    <div v-if="addInvoiceModel" class="modal" id="addInvoiceModel" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInvoiceModelLabel">Create Invoice</h5>
                    <button type="button" class="btn-close bg-dark" @click="addInvoiceModelClose">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="invoiceForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="invoice_year" name="invoice_year" placeholder="Year of Invoice" maxlength="5">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" list="clientList" class="form-control form-control-sm" id="inv_clients_ref" name="clients_id" placeholder="Client Ref No.">
                                    <datalist id="clientList" >
<!--                                        @foreach($clients as $client)
                                        <option value="{{ $client->ref_no }}" data-ref="{{ $client->name }}" data-id="{{ $client->id }}">{{ $client->ref_no }}</option>
                                        @endforeach-->
                                    </datalist>
                                    <input type="number" class="form-control form-control-sm d-none" id="inv_clients_id" name="clients_id" placeholder="Client Ref No." readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input name="inv_client_name" id="inv_client_name" class="form-control form-control-sm" placeholder="Select Client" autocomplete="off" readonly>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-sm" id="amount" name="amount" placeholder="Amount" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="date" class="form-control form-control-sm" id="due_date" name="due_date" placeholder="Due Date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-sm" id="rcd_amount" name="rcd_amount" placeholder="Received Amount" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="date" class="form-control form-control-sm" id="rcd_due_date" name="rcd_due_date" placeholder="Received Date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" name="status" id="status">
                                        <option disabled selected>Select client status</option>
                                        <option value="bad_debts">Bad debt</option>
                                        <option value="good">Good debt</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" name="payment_type" id="payment_type">
                                        <option disabled selected>Select payment type</option>
                                        <option value="bank">Bank</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-sm" id="bad_debt_amount" name="bad_debt_amount" placeholder="Bad Debt. Amount" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form-control form-control-sm" name="notes" id="notes"></textarea>
                                </div>
                            </div>

                        </div>
                        <div>
                            <button type="button" class="btn bg-gradient-secondary" @click="addInvoiceModelClose">Close</button>
                            <button type="submit" id="submitInvoiceBtn" class="btn bg-gradient-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--    End Modal for Add invoice-->

<!--    Modal for Import Invoice-->
<!--    <div class="modal fade" id="importInvoiceModel" tabindex="-1" role="dialog" aria-labelledby="importInvoiceModelLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importInvoiceModelLabel">Import File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="importInvoiceForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="file" class="" id="invoice_file" name="invoice_file">
                                </div>
                            </div>

                        </div>
                        <div>
                            <button type="submit" id="submitImportInvoiceBtn" class="btn bg-gradient-success">Import</button>

                            <div id="loader" style="display: none; margin-left: 10px;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>-->
<!--    End Modal for Import Invoice-->
</template>

<script setup>
import {ref} from "vue";

const addInvoiceModel = ref(false)

const addInvoiceModelOpen = () => {
    addInvoiceModel.value = true
}

const addInvoiceModelClose = () => {
    addInvoiceModel.value = false
}
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
