<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ApexCare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background:#eef1f4; }
        .card { border:none; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,.1); }
        .left { background:#162335; color:#fff; border-top-left-radius:12px; border-bottom-left-radius:12px; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 left d-flex align-items-center justify-content-center p-4">
                        <div class="text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <img src="{{ asset('images/logo_apexcare.png') }}" alt="ApexCare" class="me-2" style="height:36px;">
                                <span class="h2 fw-bold mb-0">ApexCare</span>
                            </div>
                            <p class="mt-3">Welcome to the future of healthcare management. Streamline your operations, all in one place.</p>
                        </div>
                    </div>
                    <div class="col-md-7 p-4 bg-white">
                        <h4 class="mb-1">Login</h4>
                        <p class="text-muted">Please enter your credentials to continue.</p>
                        <form method="post" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="d-grid mb-3">
                                <button class="btn btn-dark">Sign In</button>
                            </div>
                            <div class="text-muted small">Don't have an account? <a href="{{ route('register') }}">Register here</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>


