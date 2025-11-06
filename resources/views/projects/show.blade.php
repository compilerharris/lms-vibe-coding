@extends('layouts.app')

@section('title', 'Project Details')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="projects" />

            <!-- Main Content -->
            <div class="main-content">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3><i class="fas fa-project-diagram me-2"></i>Project Details</h3>
                        <div>
                            <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Back to Projects
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Project Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Project Name:</label>
                                    <p class="form-control-plaintext">{{ $project->name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Developer:</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-info">{{ $project->developer->name }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Description:</label>
                                    <p class="form-control-plaintext">
                                        {{ $project->description ?: 'No description provided' }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status:</label>
                                    <p class="form-control-plaintext">
                                        @if($project->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Created:</label>
                                    <p class="form-control-plaintext">{{ $project->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="stat-card">
                                        <h4 class="text-primary">{{ $project->leads->count() }}</h4>
                                        <small class="text-muted">Total Leads</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card">
                                        <h4 class="text-success">{{ $project->leads->where('status', 'converted')->count() }}</h4>
                                        <small class="text-muted">Converted</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card">
                                        <h4 class="text-warning">{{ $project->leads->where('status', 'assigned')->count() }}</h4>
                                        <small class="text-muted">Assigned</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card">
                                        <h4 class="text-info">{{ $project->leads->where('status', 'new')->count() }}</h4>
                                        <small class="text-muted">New</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($project->leads->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-users me-2"></i>Project Leads</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="projectLeadsShowTable" class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Channel Partner</th>
                                                <th>Status</th>
                                                <th>Assigned Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($project->leads as $lead)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $lead->name }}</td>
                                                    <td>{{ $lead->email }}</td>
                                                    <td>{{ $lead->phone ?: 'N/A' }}</td>
                                                    <td>
                                                        @if($lead->assignedUser)
                                                            <span class="badge bg-info">{{ $lead->assignedUser->name }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">Not Assigned</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @switch($lead->status)
                                                            @case('new')
                                                                <span class="badge bg-info">New</span>
                                                                @break
                                                            @case('assigned')
                                                                <span class="badge bg-warning">Assigned</span>
                                                                @break
                                                            @case('contacted')
                                                                <span class="badge bg-primary">Contacted</span>
                                                                @break
                                                            @case('converted')
                                                                <span class="badge bg-success">Converted</span>
                                                                @break
                                                            @case('lost')
                                                                <span class="badge bg-danger">Lost</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        {{ $lead->assigned_at ? $lead->assigned_at->format('M d, Y') : 'N/A' }}
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
            @endif
        </div>
    </div>


@section('scripts')
<script>
$(document).ready(function() {
    $('#projectLeadsShowTable').DataTable({
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
