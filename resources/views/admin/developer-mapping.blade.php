@extends('layouts.app')

@section('title', 'Developer-Channel Partner Mapping - Lead Assignment System')

@section('content')
        <div class="row">
            <!-- Sidebar -->
            <x-sidebar active="developer-mapping" />

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-link me-2"></i>Developer-Channel Partner Mapping</h2>
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>User-based system with automatic round-robin assignment
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Mapping Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-plus me-2"></i>Create/Update Mapping new new</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('developer-mapping.store') }}" method="POST" id="mappingForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="developer_user_id" class="form-label">Select Developer</label>
                                        <select class="form-select" id="developer_user_id" name="developer_user_id" required>
                                            <option value="">Choose a developer...</option>
                                            @foreach($developers as $developer)
                                                <option value="{{ $developer->id }}">{{ $developer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="channel_partner_user_ids" class="form-label">Select Channel Partners</label>
                                        <select class="form-select" id="channel_partner_user_ids" name="channel_partner_user_ids[]" multiple required>
                                            @foreach($channelPartners as $channelPartner)
                                                <option value="{{ $channelPartner->id }}">{{ $channelPartner->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Search and select multiple channel partners</div>
                                        <div class="form-text text-warning">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Note:</strong> Each channel partner can only be mapped to one developer. Already mapped channel partners will not appear in the dropdown.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Mapping
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Current Mappings Overview -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-link me-2"></i>Current Developer-Channel Partner Mappings</h6>
                    </div>
                    <div class="card-body">
                        @if($developers->count() > 0)
                            <div class="row">
                                @foreach($developers as $developer)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-left-primary">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-circle me-3" style="width: 40px; height: 40px; font-size: 16px;">
                                                        {{ strtoupper(substr($developer->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $developer->name }}</h6>
                                                    <p class="text-muted small mb-2">{{ $developer->email }}</p>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-primary me-2">{{ $developer->mappedChannelPartners->count() }} Mapped CPs</span>
                                                        <span class="badge bg-info">{{ $developer->projects->count() }} Projects</span>
                                                        <button type="button" class="btn btn-sm btn-outline-primary ms-auto edit-mapping-btn" data-developer-id="{{ $developer->id }}">
                                                            <i class="fas fa-edit me-1"></i>Edit
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($developer->mappedChannelPartners->count() > 0)
                                                <div class="mt-2">
                                                    <small class="text-muted">Mapped to:</small>
                                                    <div class="mt-1">
                                                        @foreach($developer->mappedChannelPartners as $cp)
                                                            <span class="badge bg-success me-1 mb-1">{{ $cp->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mt-2">
                                                    <small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>No channel partners mapped</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Developer Users Found</h5>
                                <p class="text-muted">Please create developer users first to set up mappings.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lead Assignment Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Lead Assignment Process</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>How Lead Assignment Works:</h6>
                                <ol>
                                    <li><strong>Lead Creation:</strong> When a new lead is created, it's assigned to a project</li>
                                    <li><strong>Developer Mapping:</strong> The system checks which channel partners are mapped to the project's developer</li>
                                    <li><strong>Automatic Assignment:</strong> The lead is assigned to a mapped channel partner using round-robin</li>
                                    <li><strong>Fair Distribution:</strong> Each mapped channel partner gets leads in rotation</li>
                                </ol>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-sync-alt fa-3x text-primary mb-3"></i>
                                    <h6>Round-Robin Assignment</h6>
                                    <p class="text-muted small">Automatic and fair distribution</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        min-height: 38px;
        padding: 2px;
    }
    
    .select2-container--default .select2-selection--single {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        height: 38px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 34px;
        padding-left: 12px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #a136aa;
        border: 1px solid #a136aa;
        color: white;
        padding: 4px 8px;
        margin: 2px;
        border-radius: 5px;
        font-size: 13px;
        line-height: 1.2;
        display: inline-block;
        position: relative;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 6px;
        font-weight: bold;
        font-size: 14px;
        line-height: 1;
        padding: 0;
        border: none;
        background: none;
        cursor: pointer;
        display: inline-block;
        width: 16px;
        height: 16px;
        text-align: center;
        vertical-align: middle;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ffcccc;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
        padding-left: 0;
        padding-right: 0;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        padding: 2px 5px;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
        color: #6c757d;
        margin-top: 5px;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__clear {
        color: #6c757d;
        font-size: 18px;
        line-height: 1;
        padding: 0;
        margin: 0;
        height: 34px;
        width: 34px;
        text-align: center;
        vertical-align: middle;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__clear:hover {
        color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
        border-radius: 50%;
    }
    
    .select2-dropdown {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .select2-container--default .select2-results__option {
        padding: 8px 12px;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #a136aa;
        color: white;
    }
    
    .select2-container--default .select2-search--inline .select2-search__field {
        border: none;
        outline: none;
        background: transparent;
        font-size: 14px;
        margin: 0;
        padding: 0;
        min-width: 120px;
    }
    
    .select2-container--default .select2-search--inline .select2-search__field:focus {
        border: none;
        outline: none;
        box-shadow: none;
    }
    
    /* Fix horizontal scroll and layout issues */
    .main-content {
        overflow-x: hidden;
        max-width: 100%;
    }
    
    .card {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    
    .border-left-primary {
        border-left: 4px solid #a136aa !important;
    }
    
    .avatar-circle {
        background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .col-md-6.col-lg-4 {
            margin-bottom: 1rem;
        }
        
        .card-body {
            padding: 1rem !important;
        }
        
        .d-flex.align-items-center {
            flex-direction: column;
            text-align: center;
        }
        
        .flex-shrink-0 {
            margin-bottom: 0.5rem;
        }
    }
    
    /* Prevent text overflow */
    .badge {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Ensure proper spacing */
    .row {
        margin-left: 0;
        margin-right: 0;
    }
    
    .col-md-6, .col-lg-4 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
</style>
@endsection

@section('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for developer dropdown
    $('#developer_user_id').select2({
        placeholder: 'Choose a developer...',
        allowClear: true,
        width: '100%'
    });
    
    // Initialize Select2 for channel partners multi-select
    $('#channel_partner_user_ids').select2({
        placeholder: 'Search and select channel partners...',
        allowClear: true,
        width: '100%',
        closeOnSelect: false
    });
    
    // Load existing mappings when developer is selected
    $('#developer_user_id').on('change', function() {
        const developerId = $(this).val();
        
        if (developerId) {
            // Load available channel partners (excluding already mapped ones)
            $.ajax({
                url: '{{ route("developer-mapping.available-cps") }}',
                method: 'GET',
                data: { developer_user_id: developerId },
                success: function(availableCPs) {
                    // Clear current options
                    $('#channel_partner_user_ids').empty();
                    
                    // Add available channel partners
                    availableCPs.forEach(function(cp) {
                        $('#channel_partner_user_ids').append(
                            '<option value="' + cp.id + '">' + cp.name + '</option>'
                        );
                    });
                    
                    // Trigger Select2 update
                    $('#channel_partner_user_ids').trigger('change');
                },
                error: function() {
                    console.log('Error loading available channel partners');
                }
            });
            
            // Load existing mappings for this developer
            $.ajax({
                url: '{{ route("developer-mapping.mapped-cps") }}',
                method: 'GET',
                data: { developer_user_id: developerId },
                success: function(mappedIds) {
                    // Set mapped channel partners as selected
                    if (mappedIds.length > 0) {
                        $('#channel_partner_user_ids').val(mappedIds).trigger('change');
                    }
                },
                error: function() {
                    console.log('Error loading mapped channel partners');
                }
            });
        } else {
            // Clear channel partner selections when no developer is selected
            $('#channel_partner_user_ids').empty().trigger('change');
        }
    });
    
    // Initialize any tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Edit mapping button - prefill form with selected developer and its mapped CPs
    $('.edit-mapping-btn').on('click', function() {
        const developerId = $(this).data('developer-id');
        if (!developerId) return;

        // Set developer select and trigger change to load available + mapped CPs
        $('#developer_user_id').val(developerId).trigger('change');

        // Smooth scroll to form for better UX
        $('html, body').animate({
            scrollTop: $('#mappingForm').closest('.card').offset().top - 80
        }, 400);
    });
});
</script>
@endsection