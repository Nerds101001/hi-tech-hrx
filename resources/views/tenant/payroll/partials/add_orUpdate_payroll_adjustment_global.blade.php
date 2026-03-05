<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddAdjustmentGlobal" aria-labelledby="offcanvasAddAdjustmentGlobalLabel">
    <div class="offcanvas-header bg-label-primary py-3">
        <h5 id="offcanvasAddAdjustmentGlobalLabel" class="offcanvas-title fw-bold text-primary">Add Global Payroll Adjustment</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 p-4">
        <form action="{{ route('settings.addOrUpdatePayrollAdjustment') }}" method="POST" id="globalAdjustmentForm">
            @csrf
            <input type="hidden" name="id" id="adjustmentIdGlobal">

            <div class="mb-4">
                <label class="form-label fw-bold text-dark smallest text-uppercase" for="adjustmentNameGlobal">Adjustment Name</label>
                <input type="text" class="form-control rounded-3" id="adjustmentNameGlobal" name="adjustmentName" placeholder="e.g. Health Insurance" required />
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark smallest text-uppercase" for="adjustmentCodeGlobal">Adjustment Code</label>
                <input type="text" class="form-control rounded-3" id="adjustmentCodeGlobal" name="adjustmentCode" placeholder="e.g. HI_2024" required />
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark smallest text-uppercase" for="adjustmentTypeGlobal">Adjustment Type</label>
                <select id="adjustmentTypeGlobal" name="adjustmentType" class="form-select rounded-3" required>
                    <option value="benefit">Benefit (Addition)</option>
                    <option value="deduction">Deduction (Subtraction)</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark smallest text-uppercase" for="adjustmentCategoryGlobal">Amount Type</label>
                <select id="adjustmentCategoryGlobal" class="form-select rounded-3">
                    <option value="fixed">Fixed Amount</option>
                    <option value="percentage">Percentage of Base Salary</option>
                </select>
            </div>

            <div class="mb-4 d-none" id="percentageDivGlobal">
                <label class="form-label fw-bold text-dark smallest text-uppercase" for="adjustmentPercentageGlobal">Percentage (%)</label>
                <div class="input-group">
                    <input type="number" step="0.01" class="form-control rounded-3" id="adjustmentPercentageGlobal" name="adjustmentPercentage" placeholder="0.00" />
                    <span class="input-group-text">%</span>
                </div>
            </div>

            <div class="mb-4" id="amountDivGlobal">
                <label class="form-label fw-bold text-dark smallest text-uppercase" for="adjustmentAmountGlobal">Amount ({{ $settings->currency_symbol }})</label>
                <div class="input-group">
                    <span class="input-group-text">{{ $settings->currency_symbol }}</span>
                    <input type="number" step="0.01" class="form-control rounded-3" id="adjustmentAmountGlobal" name="adjustmentAmount" placeholder="0.00" />
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark smallest text-uppercase" for="adjustmentNotesGlobal">Notes / Description</label>
                <textarea class="form-control rounded-3" id="adjustmentNotesGlobal" name="adjustmentNotes" rows="3" placeholder="Additional details..."></textarea>
            </div>

            <div class="mt-5 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill shadow-sm">Save Adjustment</button>
                <button type="button" class="btn btn-label-secondary flex-grow-1 rounded-pill" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('adjustmentCategoryGlobal');
        const percentageDiv = document.getElementById('percentageDivGlobal');
        const amountDiv = document.getElementById('amountDivGlobal');

        categorySelect.addEventListener('change', function() {
            if (this.value === 'percentage') {
                percentageDiv.classList.remove('d-none');
                amountDiv.classList.add('d-none');
            } else {
                amountDiv.classList.remove('d-none');
                percentageDiv.classList.add('d-none');
            }
        });
        
        // Modal edit logic if needed
        window.editAdjustmentGlobal = function(adjustment) {
            document.getElementById('offcanvasAddAdjustmentGlobalLabel').innerText = 'Edit Global Adjustment';
            document.getElementById('adjustmentIdGlobal').value = adjustment.id;
            document.getElementById('adjustmentNameGlobal').value = adjustment.name;
            document.getElementById('adjustmentCodeGlobal').value = adjustment.code;
            document.getElementById('adjustmentTypeGlobal').value = adjustment.type;
            document.getElementById('adjustmentNotesGlobal').value = adjustment.notes;
            
            if (adjustment.percentage) {
                categorySelect.value = 'percentage';
                percentageDiv.classList.remove('d-none');
                amountDiv.classList.add('d-none');
                document.getElementById('adjustmentPercentageGlobal').value = adjustment.percentage;
            } else {
                categorySelect.value = 'fixed';
                amountDiv.classList.remove('d-none');
                percentageDiv.classList.add('d-none');
                document.getElementById('adjustmentAmountGlobal').value = adjustment.amount;
            }
            
            new bootstrap.Offcanvas(document.getElementById('offcanvasAddAdjustmentGlobal')).show();
        };
    });
</script>
