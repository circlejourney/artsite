@extends("layouts.site")

@push("metatitle"){{ "Display Name" }}@endpush

@section('body')
    <div class="profile">
        <div class="profile-banner" style="background-image: url(/images/defaultbanner.png)"></div>
        
        <div class="profile-info">
            <img class="profile-avatar" src="/images/user.png">
            <div class="profile-details">
                <div class="display-name">Display Name</div>
                <div class="display-username">@username</div>
            </div>
            <div class="profile-interact">
                <a class="button" href="#">Follow</a>
            </div>
        </div>
    </div>
@endsection