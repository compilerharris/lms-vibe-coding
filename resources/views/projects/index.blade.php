@extends('layouts.app')

@section('title', 'Projects')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="projects" />

            <!-- Main Content -->
            <div class="main-content">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3><i class="fas fa-project-diagram me-2"></i>Projects</h3>
                        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add New Project
                        </a>
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
                    <div class="table-responsive">
                        <table id="projectsTable" class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Developer</th>
                                    <th>Status</th>
                                    <th>Leads Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $project->name }}</strong>
                                        </td>
                                        <td>
                                            {{ $project->description ? Str::limit($project->description, 50) : 'No description' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $project->developer->name }}</span>
                                        </td>
                                        <td>
                                            @if($project->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $project->leads_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
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

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#projectsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']], // Sort by # column ascending by default
        columnDefs: [
            { orderable: false, targets: [6] } // Disable sorting only on Actions column
        ],
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
