@extends('layouts.app')

@section('title', 'Edit User - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="user-management" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit User: {{ $user->name }}</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('user-management.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    <div class="form-text">Leave blank to keep current password</div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role_id" class="form-label">Role *</label>
                                    <select class="form-control @error('role_id') is-invalid @enderror" 
                                            id="role_id" name="role_id" required>
                                        <option value="">Select a role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Current User Info -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Current User Information:</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Current Role:</strong> {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}<br>
                                                <strong>Current Developer:</strong> {{ $user->developer->name ?? 'None' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Created:</strong> {{ $user->created_at->format('M d, Y H:i') }}<br>
                                                <strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('user-management.show', $user) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to User Details
                                </a>
                                <button type="submit" class="btn btn-primary" id="update-user-btn">
                                    <span class="btn-text">
                                        <i class="fas fa-save me-2"></i>Update User
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

@endsection

@section('scripts')
<script src="{{ asset('js/form-validation.js') }}"></script>
@endsection
