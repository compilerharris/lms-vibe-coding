<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lead Assignment System</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
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
            padding: 1rem;
        }
        .btn-login {
            background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
            border: none;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .form-control {
            border-radius: 20px;
            border: 1px solid #e9ecef;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        .form-control:focus {
            border-color: #a136aa;
            box-shadow: 0 0 0 0.2rem rgba(161, 54, 170, 0.25);
        }
        
        /* Enhanced theme elements */
        .login-card {
            border: 2px solid rgba(161, 54, 170, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #000000 0%, #a136aa 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(161, 54, 170, 0.4);
        }
        
        .form-control:hover {
            border-color: #a136aa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card">
                    <div class="login-header">
                        <img src="{{ asset('images/logo.svg') }}" alt="Lead Assignment System" class="mb-3" style="height: 60px; width: auto; background: white; padding: 12px; border-radius: 12px;">
                    </div>
                    <div class="login-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" data-skip-loader>
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-login" id="login-btn">
                                    <span class="btn-text">Sign In</span>
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
        // Simple login button loader without interference
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form[data-skip-loader]');
            const loginBtn = document.getElementById('login-btn');
            
            if (loginForm && loginBtn) {
                loginForm.addEventListener('submit', function(e) {
                    // Only show button loader, no page loaders
                    const btn = loginBtn;
                    
                    btn.disabled = true;
                    btn.querySelector('.btn-text').innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Signing In...
                    `;
                });
            }
        });
    </script>
</body>
</html>
