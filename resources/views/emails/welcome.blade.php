<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>مرحباً بك</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            background: #4CAF50;
            color: #fff;
            padding: 15px;
            border-radius: 10px 10px 0 0;
        }

        .content {
            padding: 20px;
            color: #333;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #4CAF50;
            color: #fff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>مرحباً بك في منصتنا </h2>
        </div>
        <div class="content">
            <p>مرحباً <strong>{{ $user->name }}</strong>،</p>
            <p>
                شكراً لانضمامك إلينا! نحن سعداء جداً بوجودك معنا.
            </p>
            <p>
                يمكنك الآن تسجيل الدخول والبدء في استكشاف المميزات الرائعة التي نوفرها لك.
            </p>
            <p style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">ابدأ الآن</a>
            </p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} جميع الحقوق محفوظة - OnCall</p>
        </div>
    </div>
</body>

</html>
