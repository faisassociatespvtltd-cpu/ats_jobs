{{-- Reusable Form Layout Component --}}
<div class="form-container">
    {{-- Form Header: Title on left, Actions on right --}}
    <div class="form-header">
        <h1 class="form-title">@yield('form-title', 'Form Title')</h1>
        <div class="form-actions">
            <a href="@yield('create-route')" class="btn btn-primary">
                <span>+</span> Add New
            </a>
            <a href="@yield('export-excel-route')" class="btn btn-secondary">Export Excel</a>
            <a href="@yield('export-pdf-route')" class="btn btn-secondary">Export PDF</a>
            <label for="import-excel" class="btn btn-secondary" style="cursor: pointer;">
                Import Excel
                <input type="file" id="import-excel" accept=".xlsx,.xls" style="display: none;" onchange="handleExcelImport(this)">
            </label>
        </div>
    </div>
    
    {{-- Summary Section --}}
    @hasSection('summaries')
    <div class="summary-section">
        @yield('summaries')
    </div>
    @endif
    
    {{-- Filters Section --}}
    @hasSection('filters')
    <div class="filters-section">
        <form method="GET" action="@yield('filter-action')">
            @yield('filters')
            <div style="margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="@yield('filter-action')" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
    @endif
    
    {{-- Table Section --}}
    <div class="table-container">
        @yield('table')
    </div>
    
    {{-- Pagination --}}
    @hasSection('pagination')
    <div class="pagination-container">
        @yield('pagination')
    </div>
    @endif
</div>

<script>
function handleExcelImport(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('file', input.files[0]);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        fetch('@yield('import-excel-route')', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Excel imported successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Import failed'));
            }
        })
        .catch(error => {
            alert('Error importing Excel file');
            console.error(error);
        });
    }
}
</script>

