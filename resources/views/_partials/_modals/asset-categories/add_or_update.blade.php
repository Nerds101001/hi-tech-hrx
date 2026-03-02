<!-- Add/Update Asset Category Modal -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddOrUpdateCategory" aria-labelledby="offcanvasCategoryLabel">
  <div class="offcanvas-header border-bottom">
    <h5 id="offcanvasCategoryLabel" class="offcanvas-title">Add Category</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
    <form class="add-new-category pt-0" id="assetCategoryForm" onsubmit="return false">
      <input type="hidden" name="id" id="id">
      
      <div class="mb-3">
        <label class="form-label" for="name">Category Name <span class="text-danger">*</span></label>
        <div class="input-group input-group-merge">
          <span id="nameIcon" class="input-group-text"><i class="bx bx-category"></i></span>
          <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Laptops" aria-label="Category Name" aria-describedby="nameIcon" />
        </div>
      </div>
      
      <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Brief description of this asset category"></textarea>
      </div>

      <div class="mb-6">
        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
    </form>
  </div>
</div>
