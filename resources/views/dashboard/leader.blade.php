@extends('layouts.app')

@section('title', 'Leader Dashboard - Lead Assignment System')

@section('content')
        <div class="row">
            <!-- Sidebar -->
            <x-sidebar active="dashboard" />

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="row mb-4">
                            <div class="col-12">
                                <h2><i class="fas fa-chart-line me-2"></i>Leader Dashboard</h2>
                                <p class="text-muted">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your leads.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h3>{{ $stats['total_leads'] }}</h3>
                                <p class="mb-0">Total Leads</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card success">
                            <div class="card-body text-center">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <h3>{{ $stats['new_leads'] }}</h3>
                                <p class="mb-0">New Leads</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card warning">
                            <div class="card-body text-center">
                                <i class="fas fa-user-check fa-2x mb-2"></i>
                                <h3>{{ $stats['assigned_leads'] }}</h3>
                                <p class="mb-0">Assigned Leads</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card info">
                            <div class="card-body text-center">
                                <i class="fas fa-trophy fa-2x mb-2"></i>
                                <h3>{{ $stats['converted_leads'] }}</h3>
                                <p class="mb-0">Converted Leads</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-handshake fa-2x text-primary mb-2"></i>
                                <h4>{{ $stats['total_cps'] }}</h4>
                                <p class="mb-0">Channel Partners</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-project-diagram fa-2x text-success mb-2"></i>
                                <h4>{{ $stats['active_projects'] }}</h4>
                                <p class="mb-0">Active Projects</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Leads -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Leads</h5>
                            </div>
                            <div class="card-body">
                                @if($recent_leads->count() > 0)
                                    <div class="table-responsive">
                                        <table id="leaderRecentLeadsTable" class="table table-hover">
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
                                                @foreach($recent_leads as $lead)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $lead->name }}</td>
                                                    <td>{{ $lead->email }}</td>
                                                    <td>{{ $lead->project->name ?? 'N/A' }}</td>
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
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No leads found.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@section('scripts')
<script>
$(document).ready(function() {
    $('#leaderRecentLeadsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']], // Sort by # column ascending
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
