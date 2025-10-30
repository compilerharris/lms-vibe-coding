@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="projects" />

            <!-- Main Content -->
            <div class="main-content">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3><i class="fas fa-edit me-2"></i>Edit Project</h3>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Projects
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-project-diagram me-2"></i>Project Details</h5>
                    <small class="text-muted">All fields marked with * are required.</small>
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

                    <form action="{{ route('projects.update', $project) }}" method="POST" data-skip-loader>
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Project Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $project->name) }}" required>
                                <div class="form-text">Must be unique.</div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="developer_user_id" class="form-label">Developer *</label>
                                <select class="form-select @error('developer_user_id') is-invalid @enderror" 
                                        id="developer_user_id" name="developer_user_id" required>
                                    <option value="">Select Developer</option>
                                    @foreach($developers as $developer)
                                        <option value="{{ $developer->id }}" 
                                                {{ old('developer_user_id', $project->developer_user_id) == $developer->id ? 'selected' : '' }}>
                                            {{ $developer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('developer_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Enter project description...">{{ old('description', $project->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Project
                                </button>
                                <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
