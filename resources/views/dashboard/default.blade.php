@extends('layouts.app')

@section('title', 'Dashboard - Lead Assignment System')

@section('content')
        <div class="row">
            <!-- Sidebar -->
            <x-sidebar active="dashboard" />

            <!-- Main Content -->
            <div class="col-md-10">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="row mb-4">
                            <div class="col-12">
                                <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
                                <p class="text-muted">Welcome back, {{ Auth::user()->name }}!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-user fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Welcome to Lead Assignment System</h4>
                                <p class="text-muted">Your dashboard is being prepared. Please contact your administrator for access permissions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
