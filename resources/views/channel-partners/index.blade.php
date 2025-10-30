@extends('layouts.app')

@section('title', 'Channel Partners - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="channel-partners" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-handshake me-2"></i>Channel Partners</h2>
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>Channel Partners are managed via User Management
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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="channelPartnersTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Assigned Leads</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($channelPartners as $cp)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $cp->name }}</td>
                                        <td>{{ $cp->email }}</td>
                                        <td>{{ $cp->phone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $cp->assigned_leads_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('channel-partners.show', $cp) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('user-management.edit', $cp) }}" class="btn btn-sm btn-outline-warning" title="Edit via User Management">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#channelPartnersTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']], // Sort by # column ascending by default
        columnDefs: [
            { orderable: false, targets: [6] } // Disable sorting only on Actions column (now at index 6)
        ],
        language: {
            search: "Search channel partners:",
            lengthMenu: "Show _MENU_ channel partners per page",
            info: "Showing _START_ to _END_ of _TOTAL_ channel partners",
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
