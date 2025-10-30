@extends('layouts.app')

@section('title', 'Edit Lead - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="leads" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Lead</h4>
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

                        <form action="{{ route('leads.update', $lead) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $lead->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $lead->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $lead->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="source" class="form-label">Source</label>
                                    <input type="text" class="form-control @error('source') is-invalid @enderror" 
                                           id="source" name="source" value="{{ old('source', $lead->source) }}" placeholder="e.g., Website, Facebook, Google">
                                    @error('source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="project_id" class="form-label">Project *</label>
                                    <select class="form-control @error('project_id') is-invalid @enderror" 
                                            id="project_id" name="project_id" required>
                                        <option value="">Select a project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id', $lead->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}@if($project->developer) ({{ $project->developer->name }})@else (No Developer)@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="assigned_user_id" class="form-label">Channel Partner</label>
                                    <select class="form-control @error('assigned_user_id') is-invalid @enderror" 
                                            id="assigned_user_id" name="assigned_user_id">
                                        <option value="">Select a channel partner</option>
                                        @foreach($channelPartners as $channelPartner)
                                            <option value="{{ $channelPartner->id }}" {{ old('assigned_user_id', $lead->assigned_user_id) == $channelPartner->id ? 'selected' : '' }}>
                                                {{ $channelPartner->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New</option>
                                        <option value="assigned" {{ old('status', $lead->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                        <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="converted" {{ old('status', $lead->status) == 'converted' ? 'selected' : '' }}>Converted</option>
                                        <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="4" placeholder="Any additional information about the lead">{{ old('message', $lead->message) }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('leads.show', $lead) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Lead Details
                                </a>
                                <button type="submit" class="btn btn-primary" id="update-lead-btn">
                                    <span class="btn-text">
                                        <i class="fas fa-save me-2"></i>Update Lead
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
