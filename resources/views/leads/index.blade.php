@extends('layouts.app')

@section('title', 'Leads - Lead Assignment System')

@section('content')
        <div class="row">
            <!-- Sidebar -->
            <x-sidebar active="leads" />

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-users me-2"></i>Leads</h2>
                    <div>
                        @if(Auth::user()->isAdmin() || Auth::user()->isLeader())
                        <a href="{{ route('leads.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Lead
                        </a>
                        @elseif(Auth::user()->isCS() || Auth::user()->isBiddable())
                        <span class="badge bg-info">
                            <i class="fas fa-lock me-1"></i>Read-Only Access
                        </span>
                        @endif
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Export Buttons -->
                <div class="mb-3 d-flex gap-2" id="customExportButtons">
                    <button type="button" id="exportCsvBtn" class="btn btn-sm btn-outline-success" data-skip-loader onclick="return exportToCSV(event);">
                        <i class="fas fa-file-csv me-2"></i>Export CSV
                    </button>
                    <button type="button" id="exportExcelBtn" class="btn btn-sm btn-outline-success" data-skip-loader onclick="return exportToExcel(event);">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="leadsTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Project</th>
                                        <th>
                                            @if(Auth::user()->isChannelPartner())
                                                Developer
                                            @else
                                                Channel Partner
                                            @endif
                                        </th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leads as $index => $lead)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $lead->name }}</td>
                                        <td>{{ $lead->email }}</td>
                                        <td>{{ $lead->phone ?? 'N/A' }}</td>
                                        <td>{{ $lead->project->name ?? 'N/A' }}</td>
                                        <td>
                                            @if(Auth::user()->isChannelPartner())
                                                {{ optional($lead->project->developer)->name ?? 'N/A' }}
                                            @else
                                                {{ $lead->assignedUser->name ?? 'Unassigned' }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-{{ $lead->status === 'new' ? 'primary' : ($lead->status === 'assigned' ? 'warning' : ($lead->status === 'converted' ? 'success' : ($lead->status === 'contacted' ? 'info' : 'danger'))) }} status-badge-{{ $lead->id }}">
                                                    {{ ucfirst($lead->status) }}
                                                </span>
                                                @if(!Auth::user()->isCS() && !Auth::user()->isBiddable())
                                                <select class="form-select form-select-sm status-select" data-lead-id="{{ $lead->id }}" style="width: auto; min-width: 110px; font-size: 0.875rem;">
                                                    <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                                                    <option value="assigned" {{ $lead->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                                                    <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                                    <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Converted</option>
                                                    <option value="lost" {{ $lead->status === 'lost' ? 'selected' : '' }}>Lost</option>
                                                </select>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $lead->created_at->format('M d, Y') }}</td>
                                        <td style="vertical-align: middle;">
                                            <div class="btn-group" role="group" style="display: inline-flex;">
                                                <a href="{{ route('leads.show', $lead) }}" class="btn btn-sm btn-outline-primary" style="height: 28px; padding: 0.2rem 0.5rem; display: inline-flex; align-items: center; justify-content: center;" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(Auth::user()->isAdmin() || Auth::user()->isLeader())
                                                <a href="{{ route('leads.edit', $lead) }}" class="btn btn-sm btn-outline-warning" style="height: 28px; padding: 0.2rem 0.5rem; display: inline-flex; align-items: center; justify-content: center;" title="Edit Lead">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('leads.destroy', $lead) }}" method="POST" style="display: inline;" data-skip-loader id="delete-form-{{ $lead->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-lead-btn" 
                                                            style="height: 28px; padding: 0.2rem 0.5rem; display: inline-flex; align-items: center; justify-content: center;"
                                                            data-lead-name="{{ $lead->name }}" data-form-id="delete-form-{{ $lead->id }}" title="Delete Lead">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @elseif(Auth::user()->isChannelPartner() && $lead->assigned_user_id === Auth::id())
                                                <a href="{{ route('cp.lead.show', $lead) }}" class="btn btn-sm btn-outline-warning" style="height: 28px; padding: 0.2rem 0.5rem; display: inline-flex; align-items: center; justify-content: center;" title="Edit Lead">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection

@section('scripts')
<script>
// Export functions - using plain JavaScript (no jQuery dependency)
window.exportToCSV = function(event) {
    try {
        // Prevent any form submission or navigation that might trigger loading overlay
        if (event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
        }
        
        if (!window.leadsDataTable) {
            alert('Table not initialized. Please wait a moment and try again.');
            return false;
        }
        
        var csv = [];
        var headers = [];
        
        // Get headers using plain JavaScript
        var thead = document.querySelector('#leadsTable thead tr');
        if (thead) {
            var ths = thead.querySelectorAll('th');
            for (var i = 0; i < ths.length && i < 8; i++) {
                headers.push(ths[i].textContent.trim());
            }
        }
        // Add logo reference as header comment (CSV doesn't support images)
        var logoPath = '{{ asset("images/logo.png") }}';
        csv.push('# Lead Assignment System');
        csv.push('# Logo: ' + logoPath);
        csv.push('# Generated: ' + new Date().toLocaleString());
        csv.push('');
        csv.push(headers.join(','));
        
        // Get all rows using DataTables API
        window.leadsDataTable.rows({search: 'applied'}).every(function() {
            var row = [];
            var rowNode = this.node();
            var tds = rowNode.querySelectorAll('td');
            for (var i = 0; i < tds.length && i < 8; i++) {
                var text = tds[i].textContent.trim();
                text = text.replace(/[\r\n]/g, ' ').replace(/\s+/g, ' ').trim();
                if (text.indexOf(',') >= 0 || text.indexOf('"') >= 0) {
                    text = '"' + text.replace(/"/g, '""') + '"';
                }
                row.push(text);
            }
            csv.push(row.join(','));
        });
        
        // Create and download
        var csvContent = '\uFEFF' + csv.join('\n');
        var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        var url = window.URL.createObjectURL(blob);
        var link = document.createElement('a');
        link.href = url;
        link.download = 'leads_export_' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
        
        return false;
        
    } catch(err) {
        alert('Error exporting CSV: ' + err.message);
        return false;
    }
};

window.exportToExcel = function(event) {
    try {
        // Prevent any form submission or navigation
        if (event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
        }
        
        if (!window.leadsDataTable) {
            alert('Table not initialized. Please wait a moment and try again.');
            return false;
        }
        
        // Wait a bit to ensure buttons are initialized
        setTimeout(function() {
            // Method 1: Use DataTables API - most reliable
            try {
                if (window.leadsDataTable && window.leadsDataTable.buttons) {
                    var btn = window.leadsDataTable.buttons('.buttons-excel');
                    if (btn && btn.length > 0) {
                        btn.trigger();
                        return false;
                    }
                }
            } catch(e) {
                // Method failed, try next one
            }
            
            // Method 2: Use button index directly
            try {
                if (window.leadsDataTable && window.leadsDataTable.buttons) {
                    // Excel button is at index 1 (CSV is at 0)
                    var excelBtn = window.leadsDataTable.button(1);
                    if (excelBtn) {
                        excelBtn.trigger();
                        return false;
                    }
                }
            } catch(e) {
                // Method failed, try next one
            }
            
            // Method 3: Find hidden button and click
            try {
                var excelBtns = document.querySelectorAll('.buttons-excel');
                if (excelBtns.length > 0) {
                    var clickEvent = new MouseEvent('click', {
                        bubbles: true,
                        cancelable: true,
                        view: window
                    });
                    excelBtns[0].dispatchEvent(clickEvent);
                    return false;
                }
            } catch(e) {
                // Method failed, try next one
            }
            
            // Method 4: Use the button container
            try {
                if (window.leadsDataTable && window.leadsDataTable.buttons) {
                    var btnContainer = window.leadsDataTable.buttons.container();
                    if (btnContainer) {
                        var excelBtn = btnContainer.querySelector('.buttons-excel');
                        if (excelBtn) {
                            excelBtn.click();
                            return false;
                        }
                    }
                }
            } catch(e) {
                // Method failed
            }
            
            alert('Excel export is not available. Please ensure DataTables Buttons extension is loaded. You can use CSV export instead.');
        }, 300);
        
        return false;
        
    } catch(err) {
        alert('Error exporting Excel: ' + err.message + '\nPlease try CSV export instead.');
        return false;
    }
};

// Wait for jQuery before initializing DataTable
(function() {
    function initDataTable() {
        // Check if jQuery is loaded
        if (typeof jQuery === 'undefined' && typeof window.$ === 'undefined') {
            setTimeout(initDataTable, 50);
            return;
        }
        
        // jQuery is loaded, use it
        var $ = window.jQuery || window.$;
        
        $(document).ready(function() {
            // Check if table exists
            if ($('#leadsTable').length === 0) {
                return;
            }
            
            try {
                // First, check if DataTables is loaded
                if (typeof $.fn.dataTable === 'undefined') {
                    alert('DataTables library is not loaded. Please refresh the page.');
                    return;
                }
                
                var leadsDataTable = $('#leadsTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [[0, 'asc']],
                    columnDefs: [
                        { orderable: false, targets: [6, 8] } // Disable sorting on Status and Actions columns
                    ],
                    dom: 'Bfrtip', // B = buttons, f = filter, r = processing, t = table, i = information, p = pagination
                    buttons: [
                        {
                            extend: 'csv',
                            text: 'CSV',
                            className: 'buttons-csv d-none',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7],
                                format: {
                                    body: function (data, row, column, node) {
                                        if (typeof data === 'string' && data.includes('<')) {
                                            var tempDiv = document.createElement('div');
                                            tempDiv.innerHTML = data;
                                            var text = tempDiv.textContent || tempDiv.innerText || '';
                                            return text.trim();
                                        }
                                        return data;
                                    }
                                }
                            },
                            filename: function() {
                                return 'leads_' + new Date().toISOString().split('T')[0];
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            className: 'buttons-excel d-none',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7],
                                format: {
                                    body: function (data, row, column, node) {
                                        if (typeof data === 'string' && data.includes('<')) {
                                            var tempDiv = document.createElement('div');
                                            tempDiv.innerHTML = data;
                                            var text = tempDiv.textContent || tempDiv.innerText || '';
                                            return text.trim();
                                        }
                                        return data;
                                    }
                                }
                            },
                            filename: function() {
                                return 'leads_' + new Date().toISOString().split('T')[0];
                            },
                            extension: '.xlsx',
                            customize: function (xlsx) {
                                var logoPathPng = '{{ asset("images/logo.png") }}';
                                var logoPathSvg = '{{ asset("images/logo.svg") }}';
                                
                                fetch(logoPathPng).catch(function() {
                                    return fetch(logoPathSvg);
                                }).then(function(response) {
                                    if (response && response.ok) {
                                        return response.arrayBuffer();
                                    }
                                    return null;
                                }).then(function(arrayBuffer) {
                                    if (arrayBuffer && xlsx.zip) {
                                        var imagePath = 'xl/media/logo.png';
                                        xlsx.zip.file(imagePath, arrayBuffer);
                                    }
                                }).catch(function(error) {
                                    // Logo loading failed, continue without it
                                });
                            },
                            messageTop: 'Lead Assignment System\nGenerated: {{ date("Y-m-d") }}',
                            messageBottom: '{{ date("F j, Y") }} at {{ date("g:i A") }}'
                        }
                    ],
                    language: {
                        search: "Search leads:",
                        lengthMenu: "Show _MENU_ leads per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ leads",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        }
                    }
                });
                
                // Store table reference globally for export functions
                window.leadsDataTable = leadsDataTable;
                
                // Hide default DataTables buttons (we have custom export buttons)
                setTimeout(function() {
                    $('.dt-buttons').hide();
                }, 200);

                // Handle status change in table
                $(document).on('change', '.status-select', function() {
                    const select = $(this);
                    const leadId = select.data('lead-id');
                    const newStatus = select.val();
                    const statusBadge = $('.status-badge-' + leadId);
                    
                    // Store original value for rollback
                    const originalStatus = select.data('original-status') || select.find('option:selected').val();
                    
                    // Disable select during update
                    select.prop('disabled', true);
                    
                    // Update badge immediately for better UX
                    const statusColors = {
                        'new': 'primary',
                        'assigned': 'warning',
                        'contacted': 'info',
                        'converted': 'success',
                        'lost': 'danger'
                    };
                    
                    statusBadge.removeClass('bg-primary bg-warning bg-info bg-success bg-danger');
                    statusBadge.addClass('bg-' + statusColors[newStatus]);
                    statusBadge.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                    
                    // Make AJAX request
                    $.ajax({
                        url: '/leads/' + leadId + '/status',
                        method: 'PUT',
                        data: {
                            status: newStatus,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Success - keep the new status
                            select.data('original-status', newStatus);
                            showTableAlert('success', 'Lead status updated successfully!');
                            
                            // Re-enable select
                            select.prop('disabled', false);
                        },
                        error: function(xhr) {
                            // Rollback to original status
                            select.val(originalStatus);
                            statusBadge.removeClass('bg-primary bg-warning bg-info bg-success bg-danger');
                            statusBadge.addClass('bg-' + statusColors[originalStatus]);
                            statusBadge.text(originalStatus.charAt(0).toUpperCase() + originalStatus.slice(1));
                            
                            let errorMessage = 'Failed to update status. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            
                            showTableAlert('danger', errorMessage);
                            
                            // Re-enable select
                            select.prop('disabled', false);
                        }
                    });
                });
                
                // Function to show alert messages in table
                function showTableAlert(type, message) {
                    // Remove existing alerts
                    $('.table-status-alert').remove();
                    
                    // Create alert element
                    const alert = $('<div class="alert alert-' + type + ' alert-dismissible fade show table-status-alert" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
                        message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>');
                    
                    // Insert into body
                    $('body').append(alert);
                    
                    // Auto-dismiss after 5 seconds
                    setTimeout(function() {
                        alert.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 5000);
                }

                // Handle delete button clicks
                $('.delete-lead-btn').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    var leadName = $(this).data('lead-name');
                    var formId = $(this).data('form-id');
                    var form = document.getElementById(formId);
                    var button = $(this);
                    
                    if (confirm('Are you sure you want to delete the lead "' + leadName + '"?')) {
                        button.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...');
                        button.prop('disabled', true);
                        form.submit();
                    }
                });
            } catch(error) {
                alert('Error initializing table. Please refresh the page.');
            }
        });
    }
    
    // Start initialization
    initDataTable();
})();
</script>

@section('styles')
<style>
    /* Style export buttons */
    #exportCsvBtn, #exportExcelBtn {
        border-radius: 5px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    #exportCsvBtn:hover, #exportExcelBtn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Hide default DataTables button container */
    .dt-buttons {
        display: none !important;
    }
    
    /* Fix action buttons height and alignment */
    #leadsTable tbody td:last-child {
        vertical-align: middle !important;
        padding: 0.5rem !important;
    }
    
    #leadsTable .btn-group .btn {
        height: 28px;
        min-height: 28px;
        max-height: 28px;
        padding: 0.2rem 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
    
    #leadsTable .btn-group {
        display: inline-flex;
        vertical-align: middle;
    }

    /* Status select styling */
    .status-select {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        transition: all 0.2s ease;
    }

    .status-select:hover:not(:disabled) {
        border-color: #a136aa;
        box-shadow: 0 0 0 0.2rem rgba(161, 54, 170, 0.1);
    }

    .status-select:focus {
        border-color: #a136aa;
        box-shadow: 0 0 0 0.2rem rgba(161, 54, 170, 0.25);
        outline: 0;
    }

    .status-select:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endsection
