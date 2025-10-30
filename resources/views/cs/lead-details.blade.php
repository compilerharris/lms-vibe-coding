@extends('layouts.app')

@section('title', 'Lead Details - Lead Assignment System')

@section('content')
        <!-- Sidebar -->
        <x-sidebar active="dashboard" />

        <!-- Main Content -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-user me-2"></i>Lead Details</h2>
                <a href="{{ route('cs.dashboard') }}" class="btn btn-outline-secondary">
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
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-{{ $lead->status === 'new' ? 'primary' : ($lead->status === 'assigned' ? 'warning' : ($lead->status === 'converted' ? 'success' : 'secondary')) }}">
                                                {{ ucfirst($lead->status) }}
                                            </span>
                                        </p>
                                        <p><strong>Created:</strong> {{ $lead->created_at->format('M d, Y H:i') }}</p>
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
                @if($lead->channelPartner)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-handshake me-2"></i>Channel Partner Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3" style="width: 50px; height: 50px; font-size: 18px;">
                                                {{ strtoupper(substr($lead->channelPartner->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $lead->channelPartner->name }}</h6>
                                                <p class="text-muted mb-0">{{ $lead->channelPartner->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Phone:</strong> {{ $lead->channelPartner->phone ?? 'Not provided' }}</p>
                                        <p><strong>Round Robin Position:</strong> 
                                            <span class="badge bg-warning">{{ $lead->channelPartner->round_robin_count }}</span>
                                        </p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-{{ $lead->channelPartner->is_active ? 'success' : 'secondary' }}">
                                                {{ $lead->channelPartner->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                @if($lead->channelPartner->address)
                                    <hr>
                                    <p><strong>Address:</strong></p>
                                    <p class="text-muted">{{ $lead->channelPartner->address }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Channel Partner Assigned</h5>
                                <p class="text-muted">This lead has not been assigned to any channel partner yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Lead Timeline -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Lead Timeline</h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Lead Created</h6>
                                            <p class="text-muted mb-0">{{ $lead->created_at->format('M d, Y H:i') }}</p>
                                            <small class="text-muted">Lead was created in the system</small>
                                        </div>
                                    </div>
                                    
                                    @if($lead->assigned_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Lead Assigned</h6>
                                            <p class="text-muted mb-0">{{ $lead->assigned_at->format('M d, Y H:i') }}</p>
                                            <small class="text-muted">
                                                @if($lead->channelPartner)
                                                    Assigned to {{ $lead->channelPartner->name }}
                                                @else
                                                    Assigned to channel partner
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($lead->status === 'converted')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Lead Converted</h6>
                                            <p class="text-muted mb-0">{{ $lead->updated_at->format('M d, Y H:i') }}</p>
                                            <small class="text-muted">Lead was successfully converted</small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
