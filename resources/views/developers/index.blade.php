@extends('layouts.app')

@section('title', 'Real Estate Developer Management - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="developers" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-building me-2"></i>Real Estate Developer Management</h2>
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>Developers are managed via User Management
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
                            <table id="developersTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mapped CPs</th>
                                        <th>Projects</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($developers as $developer)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($developer->name, 0, 1)) }}
                                                </div>
                                                <strong>{{ $developer->name }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $developer->email }}</td>
                                        <td>
                                            @php($cpCount = $developer->mappedChannelPartners->count() ?? 0)
                                            <span class="badge bg-primary">{{ $cpCount }}</span>
                                            @if($cpCount > 0)
                                                <div class="mt-1 small text-muted">
                                                    @foreach($developer->mappedChannelPartners->take(3) as $cp)
                                                        <span class="badge bg-success me-1 mb-1">{{ $cp->name }}</span>
                                                    @endforeach
                                                    @if($cpCount > 3)
                                                        <span class="badge bg-secondary">+{{ $cpCount - 3 }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $developer->projects_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('developers.show', $developer) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('user-management.edit', $developer) }}" class="btn btn-sm btn-outline-warning" title="Edit via User Management">
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
    $('#developersTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']], // Sort by # column ascending by default
        columnDefs: [
            { orderable: false, targets: [6] } // Disable sorting only on Actions column (now at index 6)
        ],
        language: {
            search: "Search real estate developers:",
            lengthMenu: "Show _MENU_ developers per page",
            info: "Showing _START_ to _END_ of _TOTAL_ developers",
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
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endsection
