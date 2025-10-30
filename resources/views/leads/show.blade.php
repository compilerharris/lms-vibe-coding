@extends('layouts.app')

@section('title', 'Lead Details - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="leads" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-user me-2"></i>Lead Details</h2>
                    <div>
                        <a href="{{ route('leads.edit', $lead) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Lead
                        </a>
                        <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Leads
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Lead Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Name:</strong>
                                        <p class="text-muted">{{ $lead->name }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Email:</strong>
                                        <p class="text-muted">{{ $lead->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Phone:</strong>
                                        <p class="text-muted">{{ $lead->phone ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Source:</strong>
                                        <p class="text-muted">{{ $lead->source ?? 'Not specified' }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Project:</strong>
                                        <p class="text-muted">{{ $lead->project->name ?? 'Not assigned' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Channel Partner:</strong>
                                        <p class="text-muted">{{ $lead->assignedUser->name ?? 'Unassigned' }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Status:</strong>
                                        <span class="badge bg-{{ $lead->status === 'new' ? 'primary' : ($lead->status === 'assigned' ? 'warning' : ($lead->status === 'converted' ? 'success' : 'secondary')) }}">
                                            {{ ucfirst($lead->status) }}
                                        </span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Assigned At:</strong>
                                        <p class="text-muted">{{ $lead->assigned_at ? $lead->assigned_at->format('M d, Y H:i') : 'Not assigned' }}</p>
                                    </div>
                                </div>

                                @if($lead->message)
                                <div class="mb-3">
                                    <strong>Message:</strong>
                                    <p class="text-muted">{{ $lead->message }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Timeline</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6>Lead Created</h6>
                                            <small class="text-muted">{{ $lead->created_at->format('M d, Y H:i') }}</small>
                                        </div>
                                    </div>
                                    
                                    @if($lead->assigned_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <h6>Assigned to Channel Partner</h6>
                                            <small class="text-muted">{{ $lead->assigned_at->format('M d, Y H:i') }}</small>
                                        </div>
                                    </div>
                                    @endif

                                    @if($lead->updated_at != $lead->created_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6>Last Updated</h6>
                                            <small class="text-muted">{{ $lead->updated_at->format('M d, Y H:i') }}</small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('leads.edit', $lead) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Edit Lead
                                    </a>
                                    
                                    <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="d-grid">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete this lead?')">
                                            <i class="fas fa-trash me-2"></i>Delete Lead
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -15px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    
    .timeline-content h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: -10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
</style>
@endsection
