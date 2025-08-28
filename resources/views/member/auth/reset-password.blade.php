<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* Same CSS as login page */
        * {margin:0; padding:0; box-sizing:border-box;}
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding:40px;
            width:100%;
            max-width:420px;
            border:1px solid rgba(255,255,255,0.2);
            animation: slideUp 0.6s ease-out;
        }
        .logo {
            width:120px;
            margin:0 auto 30px;
            display:block;
            border-radius:50%;
            box-shadow:0 4px 8px rgba(0,0,0,0.1);
        }
        .form-title {
            text-align:center;
            color:#2c3e50;
            margin-bottom:30px;
            font-size:28px;
            font-weight:600;
        }
        .input-group {margin-bottom:25px;}
        .form-input {
            width:100%;
            padding:15px 20px;
            border:2px solid #e1e8ed;
            border-radius:12px;
            font-size:16px;
            background:#f8f9fa;
            transition:all 0.3s ease;
            outline:none;
        }
        .form-input:focus {
            border-color:#667eea;
            background:#fff;
            box-shadow:0 0 0 3px rgba(102,126,234,0.1);
            transform:translateY(-2px);
        }
        .login-btn {
            width:100%;
            padding:16px;
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            color:white;
            border:none;
            border-radius:12px;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:all 0.3s ease;
            margin-bottom:20px;
        }
        .login-btn:hover {
            transform:translateY(-2px);
            box-shadow:0 10px 25px rgba(102,126,234,0.3);
        }
        .register-link {
            display:block;
            text-align:center;
            padding:14px;
            color:#667eea;
            font-size:16px;
            text-decoration:none;
            transition:all 0.3s ease;
        }
        .register-link:hover {text-decoration:underline;}
        @keyframes slideUp {
            from {opacity:0; transform:translateY(30px);}
            to {opacity:1; transform:translateY(0);}
        }
    </style>
</head>
<body>
<div class="login-container">
    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:15px; color:red;">
            {{ implode(', ', $errors->all()) }}
        </div>
    @endif
    <form method="POST" action="{{ route('member.reset.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <img src="{{ asset('assets/images/logo.jpeg') }}" alt="Logo" class="logo">
        <h2 class="form-title">Reset Password</h2>
        <div class="input-group">
            <input type="password" name="password" placeholder="New Password" class="form-input" required>
        </div>
        <div class="input-group">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-input" required>
        </div>
        <button type="submit" class="login-btn">Reset Password</button>
    </form>
    <a href="{{ route('member.login') }}" class="register-link">Back to Login</a>
</div>
</body>
</html>
