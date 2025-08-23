<div style="font-family: Arial, sans-serif; padding:20px">
    <h2 style="color:#333">رمز التحقق</h2>
    <p>مرحباً {{ $user->name }} 👋</p>
    <p>الكود الخاص بك هو:</p>
    <h1 style="color:blue; letter-spacing:3px">{{ $otp }}</h1>
    <p>الكود صالح لمدة <b>3 دقائق</b></p>
</div>
