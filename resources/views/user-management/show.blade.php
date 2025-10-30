@extends('layouts.app')

@section('title', 'User Details - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="user-management" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-user me-2"></i>User Details</h2>
                    <div>
                        <a href="{{ route('user-management.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit User
                        </a>
                        <a href="{{ route('user-management.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>User Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Name:</strong>
                                        <p class="text-muted">{{ $user->name }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Email:</strong>
                                        <p class="text-muted">{{ $user->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Role:</strong>
                                        <p class="text-muted">
                                            <span class="badge bg-{{ $user->role->name === 'admin' ? 'danger' : ($user->role->name === 'leader' ? 'warning' : ($user->role->name === 'developer' ? 'info' : 'secondary')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Developer:</strong>
                                        <p class="text-muted">{{ $user->developer->name ?? 'Not assigned' }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Created:</strong>
                                        <p class="text-muted">{{ $user->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Last Updated:</strong>
                                        <p class="text-muted">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Role Permissions -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Role Permissions</h5>
                            </div>
                            <div class="card-body">
                                @if($user->role->name === 'admin')
                                    <div class="alert alert-danger">
                                        <h6><i class="fas fa-crown me-2"></i>Administrator</h6>
                                        <ul class="mb-0">
                                            <li>Full read/write/execute access to all features</li>
                                            <li>User management capabilities</li>
                                            <li>System configuration access</li>
                                            <li>All dashboard and reporting features</li>
                                        </ul>
                                    </div>
                                @elseif($user->role->name === 'leader')
                                    <div class="alert alert-warning">
                                        <h6><i class="fas fa-chart-line me-2"></i>Leader</h6>
                                        <ul class="mb-0">
                                            <li>Read access to all data</li>
                                            <li>Project-wise read access</li>
                                            <li>Dashboard and reporting access</li>
                                            <li>Lead and channel partner viewing</li>
                                        </ul>
                                    </div>
                                @elseif($user->role->name === 'developer')
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-code me-2"></i>Developer</h6>
                                        <ul class="mb-0">
                                            <li>Read access to all projects</li>
                                            <li>Manage assigned projects and channel partners</li>
                                            <li>View all leads for their projects</li>
                                            <li>Dashboard access with project-specific data</li>
                                        </ul>
                                    </div>
                                @elseif($user->role->name === 'channel_partner')
                                    <div class="alert alert-secondary">
                                        <h6><i class="fas fa-handshake me-2"></i>Channel Partner</h6>
                                        <ul class="mb-0">
                                            <li>Access to assigned leads only</li>
                                            <li>View leads for their developer's projects</li>
                                            <li>Limited dashboard access</li>
                                            <li>Lead management capabilities</li>
                                        </ul>
                                    </div>
                                @else
                                    <div class="alert alert-light">
                                        <h6><i class="fas fa-eye me-2"></i>{{ ucfirst(str_replace('_', ' ', $user->role->name)) }}</h6>
                                        <ul class="mb-0">
                                            <li>Read-only access to all data</li>
                                            <li>Dashboard viewing capabilities</li>
                                            <li>Lead and project information access</li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>User Avatar</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="avatar-large mb-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <h5>{{ $user->name }}</h5>
                                <p class="text-muted">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('user-management.edit', $user) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Edit User
                                    </a>
                                    
                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('user-management.destroy', $user) }}" method="POST" class="d-grid">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash me-2"></i>Delete User
                                        </button>
                                    </form>
                                    @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-ban me-2"></i>Cannot Delete Self
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection

@section('styles')
<style>
    .avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 32px;
        margin: 0 auto;
    }
</style>
@endsection
