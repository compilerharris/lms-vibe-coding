@extends('layouts.app')

@section('title', 'Real Estate Developer Details - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="developers" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-building me-2"></i>Real Estate Developer Details</h2>
                    <div>
                        <a href="{{ route('user-management.edit', $developer) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit via User Management
                        </a>
                        <a href="{{ route('developers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Developers
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Developer Information -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Real Estate Developer Information</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                                    {{ strtoupper(substr($developer->name, 0, 1)) }}
                                </div>
                                <h5 class="mb-1">{{ $developer->name }}</h5>
                                <p class="text-muted mb-3">{{ $developer->email }}</p>
                                
                                <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h6 class="text-primary">{{ $developer->projects->count() }}</h6>
                                                <small class="text-muted">Projects</small>
                                            </div>
                                        </div>
                                    <div class="col-6">
                                        <h6 class="text-success">Active</h6>
                                        <small class="text-muted">Status</small>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="text-start">
                                    <p><strong>Phone:</strong> {{ $developer->phone ?? 'N/A' }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-{{ $developer->is_active ? 'success' : 'danger' }}">
                                            {{ $developer->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                    <p><strong>Created:</strong> {{ $developer->created_at->format('M d, Y') }}</p>
                                </div>
                                
                                @if($developer->address)
                                    <div class="text-start">
                                        <p><strong>Address:</strong></p>
                                        <p class="text-muted">{{ $developer->address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Projects and Channel Partners -->
                    <div class="col-md-8">
                        <!-- Real Estate Projects -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Real Estate Projects</h6>
                                <span class="badge bg-info">{{ $developer->projects_count ?? 0 }}</span>
                            </div>
                            <div class="card-body">
                                @if($developer->projects->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Project Name</th>
                                                    <th>Status</th>
                                                    <th>Leads</th>
                                                    <th>Created</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($developer->projects as $project)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ route('projects.show', $project) }}" class="text-decoration-none">
                                                            {{ $project->name }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $project->is_active ? 'success' : 'secondary' }}">
                                                            {{ $project->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $project->leads_count ?? 0 }}</span>
                                                    </td>
                                                    <td>{{ $project->created_at->format('M d, Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3">No real estate projects assigned to this developer.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Channel Partners -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-handshake me-2"></i>Channel Partners</h6>
                                <span class="badge bg-warning">{{ $developer->channel_partners_count ?? 0 }}</span>
                            </div>
                            <div class="card-body">
                                @if($developer->channelPartners->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Partner Name</th>
                                                    <th>Email</th>
                                                    <th>Round Robin</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($developer->channelPartners as $cp)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ route('channel-partners.show', $cp) }}" class="text-decoration-none">
                                                            {{ $cp->name }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $cp->email }}</td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $cp->round_robin_count }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $cp->is_active ? 'success' : 'danger' }}">
                                                            {{ $cp->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3">No channel partners assigned to this developer.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Users -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-users me-2"></i>Users</h6>
                                <span class="badge bg-primary">{{ $developer->users_count ?? 0 }}</span>
                            </div>
                            <div class="card-body">
                                @if($developer->users->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>User Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Created</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($developer->users as $user)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ route('user-management.show', $user) }}" class="text-decoration-none">
                                                            {{ $user->name }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $user->role->name === 'admin' ? 'danger' : ($user->role->name === 'leader' ? 'warning' : ($user->role->name === 'developer' ? 'info' : 'secondary')) }}">
                                                            {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3">No users assigned to this developer.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
</style>
@endsection
