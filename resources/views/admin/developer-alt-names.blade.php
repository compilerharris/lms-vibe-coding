@extends('layouts.app')

@section('title', 'Developer/Project Alt Names - Lead Assignment System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="developer-alt-names" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-tag me-2"></i>Developer/Project Alt Names</h2>
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>Alternative names for API security
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Developer Alternative Names Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-building me-2"></i>Developer Alternative Names</h6>
                    </div>
                    <div class="card-body">
                        @if($developers->count() > 0)
                            <div class="table-responsive">
                                <table id="developerAltNamesTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Developer Name</th>
                                            <th>Developer Alt Name</th>
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
                                                    <div>
                                                        <h6 class="mb-0">{{ $developer->name }}</h6>
                                                        <small class="text-muted">{{ $developer->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary fs-6 clickable-alt-name" 
                                                      data-alt-name="{{ $developer->alt_name }}" 
                                                      style="cursor: pointer;" 
                                                      title="Click to copy">
                                                    {{ $developer->alt_name }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Developers Found</h5>
                                <p class="text-muted">There are no developers in the system yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Project Alternative Names Table -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-project-diagram me-2"></i>Project Alternative Names</h6>
                    </div>
                    <div class="card-body">
                        @if($projects->count() > 0)
                            <div class="table-responsive">
                                <table id="projectAltNamesTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Project Name</th>
                                            <th>Developer</th>
                                            <th>Project Alt Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="project-icon me-2">
                                                        <i class="fas fa-project-diagram"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $project->name }}</h6>
                                                        <small class="text-muted">{{ Str::limit($project->description, 50) ?: 'No description' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $project->developer->name }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success fs-6 clickable-alt-name" 
                                                      data-alt-name="{{ $project->alt_name }}" 
                                                      style="cursor: pointer;" 
                                                      title="Click to copy">
                                                    {{ $project->alt_name }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Projects Found</h5>
                                <p class="text-muted">There are no projects in the system yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize Developer Alt Names DataTable
    $('#developerAltNamesTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']], // Sort by # column ascending by default
        columnDefs: [
            { orderable: false, targets: [] } // All columns are sortable
        ],
        language: {
            search: "Search developers:",
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

    // Initialize Project Alt Names DataTable
    $('#projectAltNamesTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']], // Sort by # column ascending by default
        columnDefs: [
            { orderable: false, targets: [] } // All columns are sortable
        ],
        language: {
            search: "Search projects:",
            lengthMenu: "Show _MENU_ projects per page",
            info: "Showing _START_ to _END_ of _TOTAL_ projects",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // Click to copy functionality for alt names (works for both tables)
    $('.clickable-alt-name').on('click', function() {
        const altName = $(this).data('alt-name');
        const originalText = $(this).text();
        const originalClass = $(this).attr('class');
        
        // Copy to clipboard
        navigator.clipboard.writeText(altName).then(function() {
            // Show success feedback
            const badge = $(this);
            badge.text('Copied!');
            badge.removeClass('bg-primary bg-success').addClass('bg-success');
            
            // Reset after 2 seconds
            setTimeout(function() {
                badge.text(originalText);
                badge.attr('class', originalClass);
            }, 2000);
        }.bind(this)).catch(function(err) {
            console.error('Failed to copy: ', err);
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = altName;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                // Show success feedback
                const badge = $(this);
                badge.text('Copied!');
                badge.removeClass('bg-primary bg-success').addClass('bg-success');
                
                // Reset after 2 seconds
                setTimeout(function() {
                    badge.text(originalText);
                    badge.attr('class', originalClass);
                }, 2000);
            } catch (err) {
                console.error('Fallback copy failed: ', err);
                alert('Failed to copy to clipboard');
            }
            document.body.removeChild(textArea);
        }.bind(this));
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
    
    .project-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    
    .clickable-alt-name {
        transition: all 0.3s ease;
        user-select: none;
    }
    
    .clickable-alt-name:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    
    .clickable-alt-name:active {
        transform: scale(0.95);
    }
</style>
@endsection
