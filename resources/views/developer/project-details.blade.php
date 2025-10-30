@extends('layouts.app')

@section('title', 'Project Details - Lead Assignment System')

@section('content')
        <!-- Sidebar -->
        <x-sidebar active="project-{{ $project->id }}" />

        <!-- Main Content -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-building me-2"></i>{{ $project->name }}</h2>
                <a href="{{ route('developer.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

                <!-- Project Information -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Project Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Project Name:</strong> {{ $project->name }}</p>
                                        <p><strong>Developer:</strong> {{ $project->developer->name }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-{{ $project->is_active ? 'success' : 'secondary' }}">
                                                {{ $project->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Created:</strong> {{ $project->created_at->format('M d, Y') }}</p>
                                        <p><strong>Last Updated:</strong> {{ $project->updated_at->format('M d, Y') }}</p>
                                        <p><strong>Total Leads:</strong> 
                                            <span class="badge bg-primary">{{ $projectAnalytics['totalLeads'] }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                @if($project->description)
                                    <hr>
                                    <p><strong>Description:</strong></p>
                                    <p class="text-muted">{{ $project->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Project Analytics</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="text-primary">{{ $projectAnalytics['totalLeads'] }}</h4>
                                        <small class="text-muted">Total Leads</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-success">{{ $projectAnalytics['conversionRate'] }}%</h4>
                                        <small class="text-muted">Conversion Rate</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-start">
                                    @foreach($projectAnalytics['leadsByStatus'] as $status => $count)
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="badge bg-{{ $status === 'new' ? 'primary' : ($status === 'assigned' ? 'warning' : ($status === 'converted' ? 'success' : 'secondary')) }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                            <strong>{{ $count }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leads Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-users me-2"></i>Project Leads</h6>
                        <span class="badge bg-primary">{{ $project->leads->count() }} Leads</span>
                    </div>
                    <div class="card-body">
                        @if($project->leads->count() > 0)
                            <div class="table-responsive">
                                <table id="projectLeadsTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Channel Partner</th>
                                            <th>Status</th>
                                            <th>Assigned Date</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($project->leads as $lead)
                                        <tr>
                                            <td>{{ $lead->name }}</td>
                                            <td>{{ $lead->email }}</td>
                                            <td>{{ $lead->phone ?? 'N/A' }}</td>
                                            <td>
                                                @if($lead->channelPartner)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-2" style="width: 25px; height: 25px; font-size: 10px;">
                                                            {{ strtoupper(substr($lead->channelPartner->name, 0, 1)) }}
                                                        </div>
                                                        {{ $lead->channelPartner->name }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $lead->status === 'new' ? 'primary' : ($lead->status === 'assigned' ? 'warning' : ($lead->status === 'converted' ? 'success' : 'secondary')) }}">
                                                    {{ ucfirst($lead->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $lead->assigned_at ? $lead->assigned_at->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ $lead->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Leads Found</h5>
                                <p class="text-muted">No leads have been assigned to this project yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Channel Partners Performance -->
                @if(!empty($projectAnalytics['leadsByChannelPartner']))
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-handshake me-2"></i>Channel Partner Performance</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Channel Partner</th>
                                        <th>Total Leads</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projectAnalytics['leadsByChannelPartner'] as $cpName => $leadCount)
                                    <tr>
                                        <td>{{ $cpName }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $leadCount }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $percentage = $projectAnalytics['totalLeads'] > 0 ? round(($leadCount / $projectAnalytics['totalLeads']) * 100, 1) : 0;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ $percentage }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#projectLeadsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[6, 'desc']], // Sort by Created date descending
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
</style>
        </div>
@endsection
