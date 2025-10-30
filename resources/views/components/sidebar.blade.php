@props(['active' => ''])

<div class="sidebar">
    <h6 class="mb-2"><i class="fas fa-tachometer-alt me-2"></i>Navigation</h6>
    <ul class="nav flex-column">
            <li class="nav-item mb-1">
                @if(Auth::user()->isDeveloper())
                    <a class="nav-link {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ route('developer.dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                @elseif(Auth::user()->isCS() || Auth::user()->isBiddable())
                    <a class="nav-link {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ route('cs.dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                @elseif(Auth::user()->isChannelPartner())
                    <a class="nav-link {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ route('cp.dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                @else
                    <a class="nav-link {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                @endif
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link {{ $active === 'leads' ? 'active' : '' }}" href="{{ route('leads.index') }}">
                    <i class="fas fa-users me-2"></i>Leads
                </a>
            </li>
            
            @if(Auth::user()->isAdmin() || Auth::user()->isLeader())
            <li class="nav-item mb-1">
                <a class="nav-link {{ $active === 'channel-partners' ? 'active' : '' }}" href="{{ route('channel-partners.index') }}">
                    <i class="fas fa-handshake me-2"></i>Channel Partners
                </a>
            </li>
            @endif
            
            @if(Auth::user()->isAdmin() || Auth::user()->isLeader())
            <li class="nav-item mb-1">
                <a class="nav-link {{ $active === 'projects' ? 'active' : '' }}" href="{{ route('projects.index') }}">
                    <i class="fas fa-project-diagram me-2"></i>Projects
                </a>
            </li>
            @endif
            @if(Auth::user()->isDeveloper())
            <li class="nav-item mb-1">
                <div class="nav-link text-muted">
                    <i class="fas fa-building me-2"></i>My Projects
                </div>
            </li>
            @php
                $userProjects = \App\Models\Project::where('developer_user_id', Auth::user()->id)
                    ->where('is_active', true)
                    ->limit(5)
                    ->get();
            @endphp
            @foreach($userProjects as $project)
            <li class="nav-item mb-1">
                <a class="nav-link {{ $active === 'project-' . $project->id ? 'active' : '' }}" href="{{ route('developer.project.show', $project) }}">
                    <i class="fas fa-circle me-2" style="font-size: 6px;"></i>{{ Str::limit($project->name, 20) }}
                </a>
            </li>
            @endforeach
            @endif
            
            @if(Auth::user()->isAdmin() || Auth::user()->isLeader())
            <li class="nav-item mb-1">
                <a class="nav-link {{ $active === 'developers' ? 'active' : '' }}" href="{{ route('developers.index') }}">
                    <i class="fas fa-building me-2"></i>Developer Management
                </a>
            </li>
            @endif
            @if(Auth::user()->isAdmin())
            <li class="nav-item mb-1">
                <a class="nav-link {{ $active === 'developer-mapping' ? 'active' : '' }}" href="{{ route('developer-mapping.index') }}">
                    <i class="fas fa-link me-2"></i>Developer Mapping
                </a>
            </li>
            @endif
            @if(Auth::user()->isAdmin())
            <li class="nav-item mb-1">
                <a class="nav-link {{ $active === 'developer-alt-names' ? 'active' : '' }}" href="{{ route('developer-alt-names.index') }}">
                    <i class="fas fa-tag me-2"></i>Developer/Project Alt Names
                </a>
            </li>
            @endif
            @if(Auth::user()->isAdmin())
            <li class="nav-item mb-1">
                <a class="nav-link {{ $active === 'apis' ? 'active' : '' }}" href="{{ route('apis.index') }}">
                    <i class="fas fa-code me-2"></i>APIs
                </a>
            </li>
            @endif
            @if(Auth::user()->isAdmin())
            <li class="nav-item mb-1">
                <a class="nav-link {{ $active === 'user-management' ? 'active' : '' }}" href="{{ route('user-management.index') }}">
                    <i class="fas fa-users me-2"></i>User Management
                </a>
            </li>
            @endif
        </ul>
</div>
