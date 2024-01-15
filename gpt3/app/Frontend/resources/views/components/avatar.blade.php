<div class="avatar-profile py-4 d-flex flex-column align-items-center justify-content-center">
    @if (Auth::guest())
        <img src="https://builtprefab.com/wp-content/uploads/2019/01/cropped-blank-profile-picture-973460_960_720-300x300.png" alt="Avatar" class="avatar">
        <a href="{{ route('register') }}" class="button-sign-in mt-2 ">
           Rigister Now
        </a>
        <a href="{{ route('login') }}" class="button-sign-in mt-2">
            Sign In
        </a>
    @else  
        <img src="https://upload.wikimedia.org/wikipedia/en/thumb/b/b0/Avatar-Teaser-Poster.jpg/220px-Avatar-Teaser-Poster.jpg" alt="Avatar" class="avatar">
        <div class="mt-2 font-weight-bold">Hi, {{ auth()->user()->full_name }}</div>
    @endif
</div>