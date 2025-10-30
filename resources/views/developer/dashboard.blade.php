@extends('layouts.app')

@section('title', 'Developer Dashboard - Lead Assignment System')

@section('content')
        <!-- Sidebar -->
        <x-sidebar active="dashboard" />

        <!-- Main Content -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-tachometer-alt me-2"></i>Developer Dashboard</h2>
                <div class="text-muted">
                    <i class="fas fa-building me-1"></i>{{ Auth::user()->developer->name ?? 'Developer' }}
                </div>
            </div>

                <!-- Analytics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h3>{{ $analytics['totalLeads'] }}</h3>
                                <p class="mb-0">Total Leads</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card success">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h3>{{ $analytics['convertedLeads'] }}</h3>
                                <p class="mb-0">Converted Leads</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card warning">
                            <div class="card-body text-center">
                                <i class="fas fa-percentage fa-2x mb-2"></i>
                                <h3>{{ $analytics['conversionRate'] }}%</h3>
                                <p class="mb-0">Conversion Rate</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card info">
                            <div class="card-body text-center">
                                <i class="fas fa-project-diagram fa-2x mb-2"></i>
                                <h3>{{ $projects->count() }}</h3>
                                <p class="mb-0">Active Projects</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Projects Overview -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-building me-2"></i>My Real Estate Projects</h6>
                            </div>
                            <div class="card-body">
                                @if($projects->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Project Name</th>
                                                    <th>Leads</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($projects as $project)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <strong>{{ $project->name }}</strong>
                                                        @if($project->description)
                                                            <br><small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $project->leads_count ?? 0 }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $project->is_active ? 'success' : 'secondary' }}">
                                                            {{ $project->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('developer.project.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> View Details
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No projects assigned to you yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Channel Partners -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-handshake me-2"></i>Channel Partners</h6>
                            </div>
                            <div class="card-body">
                                @if($channelPartners->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Partner Name</th>
                                                    <th>Leads</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($channelPartners as $cp)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-circle me-2" style="width: 25px; height: 25px; font-size: 10px;">
                                                                {{ strtoupper(substr($cp->name, 0, 1)) }}
                                                            </div>
                                                            {{ $cp->name }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $cp->leads_count ?? 0 }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No channel partners assigned.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Leads -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Leads</h6>
                            </div>
                            <div class="card-body">
                                @if($recentLeads->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Project</th>
                                                    <th>Channel Partner</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentLeads as $lead)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $lead->name }}</td>
                                                    <td>{{ $lead->email }}</td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $lead->project->name }}</span>
                                                    </td>
                                                    <td>{{ $lead->assignedUser->name ?? 'Unassigned' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $lead->status === 'new' ? 'primary' : ($lead->status === 'assigned' ? 'warning' : ($lead->status === 'converted' ? 'success' : 'secondary')) }}">
                                                            {{ ucfirst($lead->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $lead->created_at->format('M d, Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No leads found for your projects.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Charts -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Leads by Status</h6>
                            </div>
                            <div class="card-body">
                                @if(!empty($analytics['leadsByStatus']))
                                    <canvas id="statusChart" width="400" height="200"></canvas>
                                @else
                                    <p class="text-muted text-center">No data available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Leads by Project</h6>
                            </div>
                            <div class="card-body">
                                @if(!empty($analytics['leadsByProject']))
                                    <canvas id="projectChart" width="400" height="200"></canvas>
                                @else
                                    <p class="text-muted text-center">No data available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Status Chart
    @if(!empty($analytics['leadsByStatus']))
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($analytics['leadsByStatus'])) !!},
            datasets: [{
                data: {!! json_encode(array_values($analytics['leadsByStatus'])) !!},
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    @endif

    // Project Chart
    @if(!empty($analytics['leadsByProject']))
    const projectCtx = document.getElementById('projectChart').getContext('2d');
    new Chart(projectCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($analytics['leadsByProject'])) !!},
            datasets: [{
                label: 'Leads',
                data: {!! json_encode(array_values($analytics['leadsByProject'])) !!},
                backgroundColor: '#a136aa'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif
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
    
    .stat-card {
        transition: transform 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection
