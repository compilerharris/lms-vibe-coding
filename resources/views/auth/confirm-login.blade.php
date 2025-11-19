<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Already Logged In - Lead Assignment System</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/loader.css') }}" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
            color: white;
            padding: 1rem;
            text-align: center;
        }
        .login-body {
            padding: 1.5rem;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
            border: none;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
            color: white;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, #000000 0%, #a136aa 100%);
            color: white;
        }
        .btn-secondary-custom {
            background: #6c757d;
            border: none;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
            color: white;
        }
        .btn-secondary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            background: #5a6268;
            color: white;
        }
        .alert-warning-custom {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .session-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .session-info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #dee2e6;
        }
        .session-info-item:last-child {
            border-bottom: none;
        }
        .session-info-label {
            font-weight: 600;
            color: #495057;
        }
        .session-info-value {
            color: #6c757d;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <img src="{{ asset('images/logo.svg') }}" alt="Lead Assignment System" class="mb-3" style="height: 60px; width: auto; background: white; padding: 12px; border-radius: 12px;">
                    </div>
                    <div class="login-body">
                        <div class="alert-warning-custom">
                            <h5 class="mb-2"><strong>⚠️ Already Logged In</strong></h5>
                            <p class="mb-0">You are already logged in from another device. If you continue, your existing session will be terminated.</p>
                        </div>

                        @if(!empty($sessionInfo))
                        <div class="session-info">
                            <h6 class="mb-3"><strong>Active Session Details:</strong></h6>
                            @if(isset($sessionInfo['ip_address']))
                            <div class="session-info-item">
                                <span class="session-info-label">IP Address:</span>
                                <span class="session-info-value">{{ $sessionInfo['ip_address'] }}</span>
                            </div>
                            @endif
                            @if(isset($sessionInfo['user_agent']))
                            <div class="session-info-item">
                                <span class="session-info-label">Device/Browser:</span>
                                <span class="session-info-value">{{ Str::limit($sessionInfo['user_agent'], 50) }}</span>
                            </div>
                            @endif
                            @if(isset($sessionInfo['last_activity']))
                            <div class="session-info-item">
                                <span class="session-info-label">Last Activity:</span>
                                <span class="session-info-value">{{ \Carbon\Carbon::createFromTimestamp($sessionInfo['last_activity'])->diffForHumans() }}</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <div class="mb-3">
                            <p class="text-muted">What would you like to do?</p>
                        </div>

                        <form method="POST" action="{{ route('login.force') }}" class="mb-2" data-skip-loader>
                            @csrf
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary-custom" id="force-login-btn">
                                    <span class="btn-text">Login Here (Logout Other Device)</span>
                                </button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('login.cancel') }}" data-skip-loader>
                            @csrf
                            <div class="d-grid">
                                <button type="submit" class="btn btn-secondary-custom">
                                    Cancel (Use Existing Session)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forceLoginForm = document.querySelector('form[action="{{ route('login.force') }}"]');
            const forceLoginBtn = document.getElementById('force-login-btn');
            
            if (forceLoginForm && forceLoginBtn) {
                forceLoginForm.addEventListener('submit', function(e) {
                    const btn = forceLoginBtn;
                    btn.disabled = true;
                    btn.querySelector('.btn-text').innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Logging In...
                    `;
                });
            }
        });
    </script>
</body>
</html>

