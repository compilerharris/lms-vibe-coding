@extends('layouts.app')

@section('title', 'Channel Partner Dashboard - Lead Assignment System')

@section('content')
        <!-- Sidebar -->
        <x-sidebar active="dashboard" />

        <!-- Main Content -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-handshake me-2"></i>Channel Partner Dashboard</h2>
                <div class="text-muted">
                    <i class="fas fa-user me-1"></i>{{ $channelPartner->name }}
                    <span class="badge bg-info ms-2">Channel Partner</span>
                </div>
            </div>

                @if($developer)
                <!-- Developer Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Mapped Developer Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3" style="width: 50px; height: 50px; font-size: 18px;">
                                                {{ strtoupper(substr($developer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $developer->name }}</h6>
                                                <p class="text-muted mb-0">{{ $developer->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Phone:</strong> {{ $developer->phone ?? 'Not provided' }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-{{ $developer->is_active ? 'success' : 'secondary' }}">
                                                {{ $developer->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                @if($developer->address)
                                    <hr>
                                    <p><strong>Address:</strong></p>
                                    <p class="text-muted">{{ $developer->address }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Analytics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h3>{{ $analytics['totalLeads'] }}</h3>
                                <p class="mb-0">Total Assigned Leads</p>
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
                                <i class="fas fa-calendar-day fa-2x mb-2"></i>
                                <h3>{{ $analytics['leadsToday'] }}</h3>
                                <p class="mb-0">Leads Today</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card secondary">
                            <div class="card-body text-center">
                                <i class="fas fa-project-diagram fa-2x mb-2"></i>
                                <h3>{{ $analytics['activeProjectsCount'] }}</h3>
                                <p class="mb-0">Active Projects</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card primary">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-week fa-2x mb-2"></i>
                                <h3>{{ $analytics['leadsThisWeek'] }}</h3>
                                <p class="mb-0">This Week</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card dark">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <h3>{{ $analytics['leadsThisMonth'] }}</h3>
                                <p class="mb-0">This Month</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($assignedLeads->count() > 0)
                <!-- Assigned Leads Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-clock me-2"></i>My Assigned Leads</h6>
                                <span class="badge bg-primary">{{ $assignedLeads->count() }} Total</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="assignedLeadsTable" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Project</th>
                                                <th>Developer</th>
                                                <th>Status</th>
                                                <th>Assigned Date</th>
                                                <th>Created</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentLeads as $lead)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $lead->name }}</td>
                                                <td>{{ $lead->email }}</td>
                                                <td>{{ $lead->phone ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $lead->project->name }}</span>
                                                </td>
                                                <td>{{ $lead->project->developer->name }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $lead->status === 'new' ? 'primary' : ($lead->status === 'assigned' ? 'warning' : ($lead->status === 'converted' ? 'success' : 'secondary')) }}">
                                                        {{ ucfirst($lead->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $lead->assigned_at ? $lead->assigned_at->format('M d, Y') : 'N/A' }}</td>
                                                <td>{{ $lead->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('cp.lead.show', $lead) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View Details
                                                    </a>
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

                <!-- Analytics Charts -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Leads by Status</h6>
                            </div>
                            <div class="card-body">
                                @if(!empty($leadsByStatus))
                                    <canvas id="statusChart" width="400" height="200"></canvas>
                                @else
                                    <p class="text-muted text-center">No data available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Leads by Project</h6>
                            </div>
                            <div class="card-body">
                                @if(!empty($leadsByProject))
                                    <canvas id="projectChart" width="400" height="200"></canvas>
                                @else
                                    <p class="text-muted text-center">No data available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Trend</h6>
                            </div>
                            <div class="card-body">
                                @if(!empty($monthlyLeads))
                                    <canvas id="monthlyChart" width="400" height="200"></canvas>
                                @else
                                    <p class="text-muted text-center">No data available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- No Leads Message -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Assigned Leads</h5>
                                <p class="text-muted">
                                    @if($developer)
                                        You don't have any leads assigned from {{ $developer->name }} yet.
                                    @else
                                        You haven't been mapped to any developer yet.
                                    @endif
                                </p>
                                <p class="text-muted">Leads will appear here once they are assigned to you.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Status Chart
    @if(!empty($leadsByStatus))
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($leadsByStatus)) !!},
            datasets: [{
                data: {!! json_encode(array_values($leadsByStatus)) !!},
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
    @if(!empty($leadsByProject))
    const projectCtx = document.getElementById('projectChart').getContext('2d');
    new Chart(projectCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($leadsByProject)) !!},
            datasets: [{
                label: 'Leads',
                data: {!! json_encode(array_values($leadsByProject)) !!},
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

    // Monthly Chart
    @if(!empty($monthlyLeads))
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($monthlyLeads)) !!},
            datasets: [{
                label: 'Leads',
                data: {!! json_encode(array_values($monthlyLeads)) !!},
                borderColor: '#a136aa',
                backgroundColor: 'rgba(161, 54, 170, 0.1)',
                fill: true
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

    // DataTable for assigned leads
    @if($assignedLeads->count() > 0)
    $('#assignedLeadsTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[6, 'desc']], // Sort by Assigned Date descending
        columnDefs: [
            { orderable: false, targets: [8] } // Disable sorting on Action column
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

    .stat-card.secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }

    .stat-card.secondary .card-body {
        color: white;
    }

    .stat-card.dark {
        background: linear-gradient(135deg, #343a40 0%, #212529 100%);
        color: white;
    }

    .stat-card.dark .card-body {
        color: white;
    }
</style>
        </div>
@endsection
