@extends('layouts.app')

@section('title', 'Lead Details - Lead Management System')

@section('content')
        <!-- Sidebar -->
        <x-sidebar active="dashboard" />

        <!-- Main Content -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-user me-2"></i>Lead Details</h2>
                <a href="{{ route('cp.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

                <!-- Lead Information -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Lead Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> {{ $lead->name }}</p>
                                        <p><strong>Email:</strong> {{ $lead->email }}</p>
                                        <p><strong>Phone:</strong> {{ $lead->phone ?? 'Not provided' }}</p>
                                        <p><strong>Source:</strong> {{ $lead->source ?? 'Not specified' }}</p>
                                        <p><strong>Subsource:</strong> {{ $lead->subsource ?? 'Not specified' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-{{ $lead->status === 'new' ? 'primary' : ($lead->status === 'assigned' ? 'warning' : ($lead->status === 'converted' ? 'success' : ($lead->status === 'contacted' ? 'info' : 'danger'))) }}" id="current-status-badge">
                                                {{ ucfirst($lead->status) }}
                                            </span>
                                        </p>
                                        <div class="mt-3">
                                            <label for="status-select" class="form-label fw-bold">Change Status:</label>
                                            <form id="status-update-form" method="POST" action="{{ route('cp.lead.update-status', $lead) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="input-group">
                                                    <select name="status" id="status-select" class="form-select">
                                                        <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                                                        <option value="assigned" {{ $lead->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                                                        <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                                        <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Converted</option>
                                                        <option value="lost" {{ $lead->status === 'lost' ? 'selected' : '' }}>Lost</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-primary" id="status-update-btn">
                                                        <i class="fas fa-save me-1"></i>Update
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <p class="mt-3"><strong>Created:</strong> {{ $lead->created_at->format('M d, Y H:i') }}</p>
                                        <p><strong>Last Updated:</strong> {{ $lead->updated_at->format('M d, Y H:i') }}</p>
                                        @if($lead->assigned_at)
                                            <p><strong>Assigned:</strong> {{ $lead->assigned_at->format('M d, Y H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($lead->message)
                                    <hr>
                                    <p><strong>Message:</strong></p>
                                    <div class="alert alert-light">
                                        {{ $lead->message }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-project-diagram me-2"></i>Project Information</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Project:</strong> {{ $lead->project->name ?? 'N/A' }}</p>
                                <p><strong>Developer:</strong> {{ $lead->project->developer->name ?? 'N/A' }}</p>
                                <p><strong>Project Status:</strong> 
                                    <span class="badge bg-{{ $lead->project->is_active ? 'success' : 'secondary' }}">
                                        {{ $lead->project->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                                @if($lead->project->description)
                                    <hr>
                                    <p><strong>Description:</strong></p>
                                    <p class="text-muted small">{{ $lead->project->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Channel Partner Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-handshake me-2"></i>Your Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3" style="width: 50px; height: 50px; font-size: 18px;">
                                                {{ strtoupper(substr($channelPartner->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $channelPartner->name }}</h6>
                                                <p class="text-muted mb-0">{{ $channelPartner->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Phone:</strong> {{ $channelPartner->phone ?? 'Not provided' }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-{{ $channelPartner->is_active ? 'success' : 'secondary' }}">
                                                {{ $channelPartner->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                @if($channelPartner->address)
                                    <hr>
                                    <p><strong>Address:</strong></p>
                                    <p class="text-muted">{{ $channelPartner->address }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lead Timeline removed for CP dashboard -->
            </div>
        </div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handle status update form submission
    $('#status-update-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#status-update-btn');
        const originalBtnText = submitBtn.html();
        const selectedStatus = $('#status-select').val();
        
        // Disable button and show loading state
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span>Updating...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'PUT',
            data: {
                status: selectedStatus,
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                // Update the status badge
                const statusBadge = $('#current-status-badge');
                const statusColors = {
                    'new': 'primary',
                    'assigned': 'warning',
                    'contacted': 'info',
                    'converted': 'success',
                    'lost': 'danger'
                };
                
                statusBadge.removeClass('bg-primary bg-warning bg-info bg-success bg-danger');
                statusBadge.addClass('bg-' + statusColors[selectedStatus]);
                statusBadge.text(response.lead.status.charAt(0).toUpperCase() + response.lead.status.slice(1));
                
                // Show success message
                showAlert('success', 'Lead status updated successfully!');
                
                // Re-enable button
                submitBtn.prop('disabled', false);
                submitBtn.html(originalBtnText);
            },
            error: function(xhr) {
                let errorMessage = 'Failed to update status. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                }
                
                showAlert('danger', errorMessage);
                
                // Re-enable button
                submitBtn.prop('disabled', false);
                submitBtn.html(originalBtnText);
            }
        });
    });
    
    // Function to show alert messages
    function showAlert(type, message) {
        // Remove existing alerts
        $('.status-alert').remove();
        
        // Create alert element
        const alert = $('<div class="alert alert-' + type + ' alert-dismissible fade show status-alert" role="alert">' +
            message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>');
        
        // Insert at the top of the card body
        $('.card-body').first().prepend(alert);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            alert.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }
});
</script>
@endsection

@section('styles')
<style>
    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border-left: 3px solid #a136aa;
    }
</style>
        </div>
@endsection
