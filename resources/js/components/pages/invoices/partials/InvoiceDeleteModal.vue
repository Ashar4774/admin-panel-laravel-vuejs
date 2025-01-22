<template>
    <div v-if="deleteInvoiceModel" class="modal" id="deleteInvoiceModel" tabindex="-1" role="dialog" aria-labelledby="deleteInvoiceModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteInvoiceModelLabel">Delete Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="deleteInvoiceForm">
                        @method('DELETE')
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="invoice_id" name="invoice_id">
                                    <p class="invoice_alert"></p>
                                </div>
                            </div>

                        </div>
                        <div>
                            <button type="button" class="btn bg-gradient-danger" data-bs-dismiss="modal">No</button>
                            <button type="submit" id="deleteInvoiceBtn" class="btn bg-gradient-success">Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>


</script>

<style scoped>

</style>
