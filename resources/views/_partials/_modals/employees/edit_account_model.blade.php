@php use App\Helpers\StaticDataHelpers; @endphp
<!-- Edit Account Model -->
<div class="modal fade" id="offcanvasEditBankAccount" tabindex="-1" aria-labelledby="offcanvasEditBankAccountLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content border-0 shadow" style="border-radius: 8px; overflow: hidden;">
      <div class="modal-header align-items-center" style="background-color: #0f766e; padding: 16px 24px;">
        <div class="d-flex align-items-center gap-2">
          <i class="bx bx-building-house text-white fs-4"></i>
          <h5 id="offcanvasEditBankAccountLabel" class="modal-title text-white fw-bold mb-0" style="font-size: 1.1rem;">@lang('Edit Bank Account')</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 1; font-size: 10px;"></button>
      </div>

      <div class="modal-body p-4">
        <form id="bankAccountForm" action="{{route('employees.addOrUpdateBankAccount')}}" method="POST">
          @csrf
          <input type="hidden" name="userId" id="userId" value="{{$user->id}}">
          <input type="hidden" name="id" id="id" value="{{$user->bankAccount != null ? $user->bankAccount->id : ''}}">

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted text-uppercase mb-2" for="accountNumber">@lang('Account Number')<span class="text-danger">*</span></label>
              <input type="text" class="form-control px-3 py-2" id="accountNumber" placeholder="@lang('Enter account number')" style="border-radius: 0.5rem;" value="{{$user->bankAccount != null ?$user->bankAccount->account_number: ''}}" name="accountNumber" />
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted text-uppercase mb-2" for="ifscCode">@lang('IFSC Code')<span class="text-danger">*</span></label>
              <input type="text" class="form-control px-3 py-2" id="ifscCode" placeholder="@lang('Enter IFSC code')" style="border-radius: 0.5rem;" value="{{$user->bankAccount != null ?$user->bankAccount->ifsc_code : ''}}" name="ifscCode" />
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted text-uppercase mb-2" for="bankName">@lang('Bank Name')<span class="text-danger">*</span></label>
              <select class="form-select select2 px-3 py-2" id="bankName" name="bankName" style="border-radius: 0.5rem;">
                <option value="">Select Bank</option>
                @foreach (StaticDataHelpers::getIndianBanksList() as $bank)
                  <option value="{{$bank}}" {{($user->bankAccount != null && $user->bankAccount->bank_name == $bank) ? 'selected' : ''}}>{{$bank}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold small text-muted text-uppercase mb-2" for="branch">@lang('Branch')<span class="text-danger">*</span></label>
              <input type="text" class="form-control px-3 py-2" id="branch" placeholder="@lang('Enter branch name')" style="border-radius: 0.5rem;" name="branch" value="{{$user->bankAccount != null ?$user->bankAccount->branch:''}}" />
            </div>
          </div>

          <div class="d-flex justify-content-end gap-3 mt-4 pt-3">
            <button type="reset" class="btn fw-semibold" data-bs-dismiss="modal" style="background-color: #fee2e2; color: #ef4444; border-radius: 2rem; padding: 0.6rem 2rem;">@lang('Cancel')</button>
            <button type="submit" class="btn d-flex align-items-center gap-2 fw-semibold" style="background-color: #0f766e; color: white; border-radius: 2rem; padding: 0.6rem 2rem;">
              @lang('Save Changes') <i class="bx bx-check-circle fs-5"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /Edit Account Model -->
