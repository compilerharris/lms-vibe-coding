@extends('layouts.app')

@section('title', 'Channel Partner Details - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="channel-partners" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-handshake me-2"></i>Channel Partner Details</h2>
                    <div>
                        <a href="{{ route('user-management.edit', $channelPartner) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit via User Management
                        </a>
                        <a href="{{ route('channel-partners.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Channel Partners
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Channel Partner Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Name:</strong>
                                        <p class="text-muted">{{ $channelPartner->name }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Email:</strong>
                                        <p class="text-muted">{{ $channelPartner->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Phone:</strong>
                                        <p class="text-muted">{{ $channelPartner->phone ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Assigned Leads:</strong>
                                        <span class="badge bg-info">{{ $channelPartner->assignedLeads->count() }}</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Status:</strong>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>

                                @if($channelPartner->address)
                                <div class="mb-3">
                                    <strong>Address:</strong>
                                    <p class="text-muted">{{ $channelPartner->address }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Assigned Leads -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Assigned Leads ({{ $channelPartner->assignedLeads->count() }})</h5>
                            </div>
                            <div class="card-body">
                                @if($channelPartner->assignedLeads->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Project</th>
                                                    <th>Status</th>
                                                    <th>Assigned</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($channelPartner->assignedLeads as $lead)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $lead->name }}</td>
                                                    <td>{{ $lead->email }}</td>
                                                    <td>{{ $lead->project->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $lead->status === 'new' ? 'primary' : ($lead->status === 'assigned' ? 'warning' : ($lead->status === 'converted' ? 'success' : 'secondary')) }}">
                                                            {{ ucfirst($lead->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $lead->assigned_at ? $lead->assigned_at->format('M d, Y') : 'N/A' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No leads assigned to this channel partner.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Timeline</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6>Channel Partner Created</h6>
                                            <small class="text-muted">{{ $channelPartner->created_at->format('M d, Y H:i') }}</small>
                                        </div>
                                    </div>
                                    
                                    @if($channelPartner->updated_at != $channelPartner->created_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6>Last Updated</h6>
                                            <small class="text-muted">{{ $channelPartner->updated_at->format('M d, Y H:i') }}</small>
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
                                    <a href="{{ route('channel-partners.edit', $channelPartner) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Edit Channel Partner
                                    </a>
                                    
                                    <form action="{{ route('channel-partners.destroy', $channelPartner) }}" method="POST" class="d-grid">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete this channel partner?')">
                                            <i class="fas fa-trash me-2"></i>Delete Channel Partner
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
