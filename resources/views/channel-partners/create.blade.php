@extends('layouts.app')

@section('title', 'Add Channel Partner - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="channel-partners" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Channel Partner</h4>
                        <small class="text-muted">All fields marked with * are required. Name, email, and phone must be unique.</small>
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

                        <form action="{{ route('channel-partners.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    <div class="form-text">Must be unique.</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    <div class="form-text">Must be unique.</div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    <div class="form-text">Must be unique if provided.</div>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="round_robin_count" class="form-label">Round Robin Count *</label>
                                    <input type="number" class="form-control @error('round_robin_count') is-invalid @enderror" 
                                           id="round_robin_count" name="round_robin_count" value="{{ old('round_robin_count', 2) }}" 
                                           min="1" required>
                                    <div class="form-text">Position in round-robin rotation (1, 2, 3, etc.). Lower numbers get leads first.</div>
                                    @error('round_robin_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="developer_id" class="form-label">Developer *</label>
                                <select class="form-control @error('developer_id') is-invalid @enderror" 
                                        id="developer_id" name="developer_id" required>
                                    <option value="">Select a developer</option>
                                    @foreach($developers as $developer)
                                        <option value="{{ $developer->id }}" {{ old('developer_id') == $developer->id ? 'selected' : '' }}>
                                            {{ $developer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('developer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" placeholder="Channel partner address">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('channel-partners.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Channel Partners
                                </a>
                                <button type="submit" class="btn btn-primary" id="create-cp-btn">
                                    <span class="btn-text">
                                        <i class="fas fa-save me-2"></i>Create Channel Partner
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
<script>
    // Auto-suggest next available round robin count
    document.addEventListener('DOMContentLoaded', function() {
        const roundRobinInput = document.getElementById('round_robin_count');
        if (roundRobinInput && roundRobinInput.value === '1') {
            roundRobinInput.value = '2';
        }
    });
</script>
@endsection
