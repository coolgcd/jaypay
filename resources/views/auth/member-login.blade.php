<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            width: 120px;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 30px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 16px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: #a0a7ac;
            font-weight: 400;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .register-link {
            display: block;
            text-align: center;
            padding: 14px;
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .register-link:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
            color: #a0a7ac;
            font-size: 14px;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e1e8ed;
            z-index: 1;
        }

        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 15px;
            position: relative;
            z-index: 2;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 25px;
                margin: 10px;
            }

            .form-title {
                font-size: 24px;
            }

            .form-input {
                padding: 12px 16px;
            }

            .login-btn {
                padding: 14px;
            }
        }

        /* Animation */
        .login-container {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="POST" action="{{ route('member.login') }}">
            @csrf
            <img src="{{ asset('assets/images/logo.jpeg') }}" alt="Logo" class="logo">
            <h2 class="form-title">Member Login</h2>
            
            <div class="input-group">
                <input
                    type="text"
                    name="show_mem_id"
                    placeholder="Member ID"
                    class="form-input"
                    required
                >
            </div>
            
            <div class="input-group">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="form-input"
                    required
                >
            </div>
            
            <button type="submit" class="login-btn">
                Login
            </button>
        </form>
        
        <div class="divider">
            <span>Don't have an account?</span>
        </div>
        
        <a href="{{ route('member.register') }}" class="register-link">
            Create Account
        </a>
        <a href="{{ route('member.forgot.form') }}" class="register-link">
    Forgot Password?
</a>

    
    <a href="{{ url('/') }}" class="register-link">
        Return Home
    </a>

    </div>
</body>
</html>