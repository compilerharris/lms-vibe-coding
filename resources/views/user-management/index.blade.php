@extends('layouts.app')

@section('title', 'User Management - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="user-management" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3><i class="fas fa-users me-2"></i>User Management</h3>
                    <a href="{{ route('user-management.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add New User
                    </a>
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
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>All Users</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="usersTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Alt Name</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                {{ $user->name }}
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role->name === 'admin' ? 'danger' : ($user->role->name === 'leader' ? 'warning' : ($user->role->name === 'developer' ? 'info' : 'secondary')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->role->name === 'developer' && $user->alt_name)
                                                <span class="badge bg-primary clickable-alt-name" 
                                                      data-alt-name="{{ $user->alt_name }}" 
                                                      title="Click to copy">
                                                    {{ $user->alt_name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('user-management.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('user-management.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($user->id !== Auth::id())
                                                <form action="{{ route('user-management.destroy', $user) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger delete-btn" 
                                                            data-user-name="{{ $user->name }}">
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

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']], // Sort by # column ascending by default
        columnDefs: [
            { orderable: false, targets: [6] } // Disable sorting only on Actions column
        ],
        language: {
            search: "Search users:",
            lengthMenu: "Show _MENU_ users per page",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // Handle delete button loading state
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const form = button.closest('.delete-form');
        const userName = button.data('user-name');
        const originalContent = button.html();
        
        // Show confirmation
        if (!confirm(`Are you sure you want to delete user "${userName}"?`)) {
            return false;
        }
        
        // Show loading state
        button.prop('disabled', true)
               .html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        
        // Submit form via AJAX
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Reload page to show updated list
                location.reload();
            },
            error: function(xhr) {
                // Reset button on error
                button.prop('disabled', false).html(originalContent);
                
                // Show error message
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert('Error: ' + xhr.responseJSON.message);
                } else {
                    alert('An error occurred while deleting the user.');
                }
            }
        });
        
        // Fallback: reset button after 10 seconds
        setTimeout(function() {
            button.prop('disabled', false).html(originalContent);
        }, 10000);
    });

    // Handle click-to-copy for alt names
    $('.clickable-alt-name').on('click', function() {
        const altName = $(this).data('alt-name');
        navigator.clipboard.writeText(altName).then(function() {
            // Show success feedback
            const badge = $('.clickable-alt-name[data-alt-name="' + altName + '"]');
            const originalText = badge.text();
            badge.text('Copied!').addClass('bg-success').removeClass('bg-primary');
            
            setTimeout(function() {
                badge.text(originalText).addClass('bg-primary').removeClass('bg-success');
            }, 1500);
        }).catch(function() {
            alert('Failed to copy to clipboard');
        });
    });
});

// Debug function for testing deletion (keeping for future use)
function testDelete(userId, userName) {
    if (confirm(`Debug: Delete user ${userName} (ID: ${userId})?`)) {
        $.ajax({
            url: `/test-delete-user/${userId}`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert('User deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(xhr) {
                alert('AJAX Error: ' + xhr.responseText);
            }
        });
    }
}
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
    
    .clickable-alt-name {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .clickable-alt-name:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
</style>
@endsection
