@extends('layouts.app')

@section('title', 'Projects - Lead Management System')

@section('content')
        <div class="row">
            <!-- Sidebar -->
            <x-sidebar active="cs-projects" />

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3><i class="fas fa-project-diagram me-2"></i>Projects</h3>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>All Projects</h6>
                    </div>
                    <div class="card-body">
                        @if($projects->count() > 0)
                            <div class="table-responsive">
                                <table id="projectsTable" class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Developer</th>
                                            <th>Leads Count</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                            <tr>
                                                <td>{{ $loop->iteration + ($projects->currentPage() - 1) * $projects->perPage() }}</td>
                                                <td>
                                                    <strong>{{ $project->name }}</strong>
                                                </td>
                                                <td>
                                                    {{ $project->description ? Str::limit($project->description, 50) : 'No description' }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $project->developer->name ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $project->leads_count ?? 0 }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('cs.projects.leads', $project) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye me-1"></i>View Leads
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $projects->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Projects Found</h5>
                                <p class="text-muted">No active projects are available at the moment.</p>
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
    $('#projectsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [5] } // Disable sorting on Actions column
        ],
        paging: false, // Disable DataTables pagination since we're using Laravel pagination
        info: false, // Disable DataTables info since we're using Laravel pagination
        language: {
            search: "Search projects:",
            lengthMenu: "Show _MENU_ projects per page",
            info: "Showing _START_ to _END_ of _TOTAL_ projects",
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



