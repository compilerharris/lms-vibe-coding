@extends('layouts.app')

@section('title', 'Leads - Lead Assignment System')

@section('content')
        <div class="row">
            <!-- Sidebar -->
            <x-sidebar active="leads" />

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-users me-2"></i>Leads</h2>
                    @if(Auth::user()->isAdmin() || Auth::user()->isLeader())
                    <a href="{{ route('leads.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Lead
                    </a>
                    @endif
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="leadsTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Project</th>
                                        <th>
                                            @if(Auth::user()->isChannelPartner())
                                                Developer
                                            @else
                                                Channel Partner
                                            @endif
                                        </th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leads as $index => $lead)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $lead->name }}</td>
                                        <td>{{ $lead->email }}</td>
                                        <td>{{ $lead->phone ?? 'N/A' }}</td>
                                        <td>{{ $lead->project->name ?? 'N/A' }}</td>
                                        <td>
                                            @if(Auth::user()->isChannelPartner())
                                                {{ optional($lead->project->developer)->name ?? 'N/A' }}
                                            @else
                                                {{ $lead->assignedUser->name ?? 'Unassigned' }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $lead->status === 'new' ? 'primary' : ($lead->status === 'assigned' ? 'warning' : ($lead->status === 'converted' ? 'success' : 'secondary')) }}">
                                                {{ ucfirst($lead->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $lead->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('leads.show', $lead) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(Auth::user()->isAdmin() || Auth::user()->isLeader())
                                                <a href="{{ route('leads.edit', $lead) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('leads.destroy', $lead) }}" method="POST" style="display: inline;" data-skip-loader id="delete-form-{{ $lead->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-lead-btn" 
                                                            data-lead-name="{{ $lead->name }}" data-form-id="delete-form-{{ $lead->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
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
            $('#leadsTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[0, 'asc']], // Sort by # column ascending by default
                columnDefs: [
                    { orderable: false, targets: [8] } // Disable sorting only on Actions column
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

    // Handle delete button clicks
    $('.delete-lead-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const leadName = $(this).data('lead-name');
        const formId = $(this).data('form-id');
        const form = document.getElementById(formId);
        const button = $(this);
        
        if (confirm(`Are you sure you want to delete the lead "${leadName}"?`)) {
            // Show loading state
            button.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...');
            button.prop('disabled', true);
            
            // Submit the form programmatically
            form.submit();
        }
    });
});
</script>
@endsection
