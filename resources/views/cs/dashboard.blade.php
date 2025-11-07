@extends('layouts.app')

@php
    $roleLabel = ucfirst(Auth::user()->role->name) . ' Dashboard';
@endphp
@section('title', $roleLabel . ' - Lead Assignment System')

@section('content')
        <!-- Sidebar -->
        <x-sidebar active="dashboard" />

        <!-- Main Content -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-headset me-2"></i>{{ $roleLabel }}</h2>
                <div class="text-muted">
                    <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                    <span class="badge bg-info ms-2">{{ ucfirst(Auth::user()->role->name) }}</span>
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
                                <i class="fas fa-calendar-day fa-2x mb-2"></i>
                                <h3>{{ $analytics['leadsToday'] }}</h3>
                                <p class="mb-0">Leads Today</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card secondary">
                            <div class="card-body text-center">
                                <i class="fas fa-project-diagram fa-2x mb-2"></i>
                                <h3>{{ $analytics['activeProjectsCount'] }}</h3>
                                <p class="mb-0">Active Projects</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card primary">
                            <div class="card-body text-center">
                                <i class="fas fa-handshake fa-2x mb-2"></i>
                                <h3>{{ $analytics['activeChannelPartnersCount'] }}</h3>
                                <p class="mb-0">Channel Partners</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card dark">
                            <div class="card-body text-center">
                                <i class="fas fa-building fa-2x mb-2"></i>
                                <h3>{{ $analytics['activeDevelopersCount'] }}</h3>
                                <p class="mb-0">Developers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card success">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-week fa-2x mb-2"></i>
                                <h3>{{ $analytics['leadsThisWeek'] }}</h3>
                                <p class="mb-0">This Week</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Leads Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Leads</h6>
                                <span class="badge bg-primary">{{ $recentLeads->count() }} Recent</span>
                            </div>
                            <div class="card-body">
                                @if($recentLeads->count() > 0)
                                    <div class="table-responsive">
                                        <table id="recentLeadsTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Project</th>
                                                    <th>Developer</th>
                                                    <th>Channel Partner</th>
                                                    <th>Status</th>
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
                                                        <span class="badge bg-info">{{ $lead->project->name ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>{{ $lead->project->developer->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($lead->assignedUser)
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-circle me-2" style="width: 25px; height: 25px; font-size: 10px;">
                                                                    {{ strtoupper(substr($lead->assignedUser->name, 0, 1)) }}
                                                                </div>
                                                                {{ $lead->assignedUser->name }}
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
                                                    <td>{{ $lead->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('cs.lead.show', $lead) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> View Details
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Leads Found</h5>
                                        <p class="text-muted">No leads have been created yet.</p>
                                    </div>
                                @endif
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

    // DataTable for recent leads
    $('#recentLeadsTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']], // Sort by # column ascending
        columnDefs: [
            { orderable: false, targets: [9] } // Disable sorting on Action column
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv me-2"></i>Export CSV',
                className: 'btn btn-sm btn-outline-success',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
                text: '<i class="fas fa-file-excel me-2"></i>Export Excel',
                className: 'btn btn-sm btn-outline-success',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
});
</script>
@endsection

@section('styles')
<style>
    /* Style export buttons consistently */
    .dt-buttons .btn {
        border-radius: 5px !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
        border: 1px solid #28a745 !important;
        color: #28a745 !important;
        background-color: transparent !important;
    }
    
    .dt-buttons .btn:hover {
        background-color: #28a745 !important;
        color: white !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .dt-buttons .btn i {
        color: inherit !important;
    }
    
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
