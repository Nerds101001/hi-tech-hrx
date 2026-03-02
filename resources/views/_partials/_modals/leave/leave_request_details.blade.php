<div class="modal fade" id="modalLeaveRequestDetails" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-hitech">
            <!-- Premium HITECH Header -->
            <div class="modal-header modal-header-hitech">
                <div class="d-flex align-items-center">
                    <div class="modal-icon-header me-3">
                        <i class="bx bx-file-blank fs-3"></i>
                    </div>
                    <h5 class="modal-title modal-title-hitech">Review Leave: <span id="userNameHeader"></span></h5>
                </div>
                <button type="button" class="btn-close-hitech" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Section: Details (The "Carded" Design) -->
                    <div class="col-md-5 p-4" style="background: #f8fafc; border-right: 1px solid #edf2f7;">
                        <div class="mb-4">
                            <label class="hitech-label-caps">EMPLOYEE INFO</label>
                            <div class="card border-0 shadow-sm p-3 mt-2" style="border-radius: 12px; background: #fff;">
                                <div class="d-flex align-items-center gap-3">
                                    <div id="userAvatarContainer"></div>
                                    <div class="overflow-hidden">
                                        <h6 class="mb-0 fw-bold text-dark text-truncate" id="userNameLabel">...</h6>
                                        <small class="text-muted fw-semibold" id="userCode">...</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="hitech-label-caps">LEAVE TYPE</label>
                            <div class="card border-0 shadow-sm p-3 mt-2 d-flex flex-row align-items-center gap-3" style="border-radius: 12px; background: #fff;">
                                <div class="icon-sq-teal">
                                    <i class="bx bx-purchase-tag"></i>
                                </div>
                                <span class="fw-bold text-dark" id="leaveType">...</span>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="hitech-label-caps">START DATE</label>
                                <div class="card border-0 shadow-sm p-3 mt-2 text-center fw-bold text-dark fs-6" style="border-radius: 12px; background: #fff;" id="fromDate">...</div>
                            </div>
                            <div class="col-6">
                                <label class="hitech-label-caps">END DATE</label>
                                <div class="card border-0 shadow-sm p-3 mt-2 text-center fw-bold text-dark fs-6" style="border-radius: 12px; background: #fff;" id="toDate">...</div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="hitech-label-caps">REASON FOR LEAVE</label>
                            <div class="card border-0 shadow-sm p-3 mt-2 text-dark" style="border-radius: 12px; background: #fff; min-height: 80px; font-size: 0.95rem; line-height: 1.5;" id="userNotes">...</div>
                        </div>
                        
                        <div id="documentHide" style="display: none;" class="mt-4">
                            <label class="hitech-label-caps">ATTACHMENT</label>
                            <div class="card border-0 shadow-sm p-2 mt-2" style="border-radius: 12px; background: #fff;">
                                <a href="#" class="glightbox">
                                    <img id="document" src="" class="img-fluid rounded shadow-sm" style="max-height: 120px; width: 100%; object-fit: cover;">
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section: Administrative Action -->
                    <div class="col-md-7 p-4 bg-white d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <label class="hitech-label-caps m-0">ADMINISTRATIVE ACTION</label>
                            <div id="statusDiv"></div>
                        </div>

                        <div class="mb-1">
                             <label class="hitech-label-caps mb-2">OFFICIAL REMARKS</label>
                        </div>

                        <form id="leaveRequestForm" action="{{ route('leaveRequests.actionAjax') }}" method="POST" style="display:none;" class="flex-grow-1 d-flex flex-column">
                            @csrf
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="status" id="statusInput">
                            
                            <div class="mb-4 flex-grow-1">
                                <textarea class="form-control workspace-textarea-original" id="adminNotes" name="adminNotes" rows="10" placeholder="Document the reason for this decision here..."></textarea>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center gap-2 text-muted">
                                    <i class="bx bx-time-five fs-5"></i>
                                    <span class="small fw-semibold">Requested: <span id="createdAt">...</span></span>
                                </div>
                                <span class="badge bg-label-info px-4 py-2 rounded-pill fw-bold" style="font-size: 0.85rem;"><span id="totalDays">0</span> <span id="dayLabel">Days</span></span>
                            </div>

                            <div class="d-flex gap-3 mt-auto">
                                <button type="button" class="btn btn-label-secondary flex-grow-1 fw-bold py-2 fs-6" data-bs-dismiss="modal" style="border-radius: 10px;">CLOSE</button>
                                <button type="button" onclick="submitDecision('rejected')" class="btn btn-danger flex-grow-1 fw-bold py-2 fs-6" id="btnReject" style="border-radius: 10px; background: #ff4d4d; border: none;">
                                    <i class="bx bx-x me-1 fs-5"></i>REJECT
                                </button>
                                <button type="button" onclick="submitDecision('approved')" class="btn flex-grow-1 fw-bold py-2 fs-6 text-white" id="btnApprove" style="border-radius: 10px; background: #00695c; border: none;">
                                    <i class="bx bx-check me-1 fs-5"></i>APPROVE
                                </button>
                            </div>
                            <span id="remarksRequired" class="text-danger text-center mt-2 small fw-bold" style="display:none;">* REASON IS REQUIRED FOR REJECTION</span>
                        </form>

                        <div id="alreadyRespondedNotice" class="text-center my-auto py-5" style="display: none">
                            <div class="mb-3">
                                <i class="bx bx-lock-alt text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="fw-bold text-dark">Decision Finalized</h4>
                            <p class="text-muted small px-5">This request has reached a final state and is now locked for auditing purposes.</p>
                            <button type="button" class="btn btn-secondary px-5 py-2 mt-3" data-bs-dismiss="modal" style="border-radius: 10px;">Return</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hitech-label-caps {
    display: block;
    font-size: 0.68rem;
    font-weight: 800;
    text-transform: uppercase;
    color: #94a3b8;
    letter-spacing: 0.1em;
}

.icon-sq-teal {
    width: 32px;
    height: 32px;
    background: rgba(0, 105, 92, 0.1);
    color: #00695c;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.workspace-textarea-original {
    background: #ffffff !important;
    border: 2px solid #e0f2f1 !important;
    border-radius: 12px !important;
    padding: 1.25rem !important;
    font-size: 1rem;
    color: #333 !important;
    resize: none !important;
    transition: all 0.2s ease;
}
.workspace-textarea-original:focus {
    border-color: #00695c !important;
    box-shadow: 0 5px 15px rgba(0, 105, 92, 0.05) !important;
}

.bg-label-info { background-color: #e1f5fe !important; color: #039be5 !important; }

/* Custom Badge Overrides to match High-End UX */
.badge.bg-label-warning { background-color: #fff8e1 !important; color: #ff8f00 !important; font-weight: 700 !important; }
.badge.bg-label-success { background-color: #e8f5e9 !important; color: #2e7d32 !important; font-weight: 700 !important; }
.badge.bg-label-danger { background-color: #ffebee !important; color: #c62828 !important; font-weight: 700 !important; }

.btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }
</style>
