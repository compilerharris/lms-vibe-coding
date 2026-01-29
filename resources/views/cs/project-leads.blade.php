@extends('layouts.app')

@section('title', 'Project Leads - ' . $project->name . ' - Lead Assignment System')

@section('content')
        <div class="row">
            <!-- Sidebar -->
            <x-sidebar active="cs-projects" />

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><i class="fas fa-users me-2"></i>Leads for {{ $project->name }}</h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-building me-1"></i>Developer: 
                            <span class="badge bg-info">{{ $project->developer->name ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <a href="{{ route('cs.projects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Projects
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>Project Leads</h6>
                        <span class="badge bg-primary">{{ $leads->total() }} Total Leads</span>
                    </div>
                    <div class="card-body">
                        @if($leads->count() > 0)
                            <div class="table-responsive">
                                <table id="projectLeadsTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Channel Partner</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leads as $lead)
                                        <tr>
                                            <td>{{ $loop->iteration + ($leads->currentPage() - 1) * $leads->perPage() }}</td>
                                            <td>{{ $lead->name }}</td>
                                            <td>{{ $lead->email }}</td>
                                            <td>{{ $lead->phone ?? 'N/A' }}</td>
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
                                            <td style="vertical-align: middle;">
                                                <a href="{{ route('cs.lead.show', $lead) }}" class="btn btn-sm btn-outline-primary" style="height: 28px; padding: 0.2rem 0.5rem; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $leads->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Leads Found</h5>
                                <p class="text-muted">No leads have been created for this project yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#projectLeadsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [7] } // Disable sorting on Actions column
        ],
        paging: false, // Disable DataTables pagination since we're using Laravel pagination
        info: false, // Disable DataTables info since we're using Laravel pagination
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
    
    #projectLeadsTable tbody td:last-child {
        vertical-align: middle !important;
        padding: 0.5rem !important;
    }
    
    #projectLeadsTable .btn {
        height: 28px;
        min-height: 28px;
        max-height: 28px;
        padding: 0.2rem 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
</style>
@endsection



