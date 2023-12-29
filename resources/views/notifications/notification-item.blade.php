<div class="notification-item" id="delete-{{ $notification->id }}">
    <div class="notification-left @if(isset($read) && !$read) unread @endif">
        @include("notifications.form-checkbox")

        @isset($notification->sender)
            <img src="{{ $notification->sender->getAvatarURL() }}">
        @endisset

        <span>
            {!! $notification->getDisplayHTML() !!}
        </span>

        <span class="small text-muted ml-2">
            {{ $notification->created_at->diffForHumans() }}
        </span>
    </div>

    <input type="hidden" name="notifications[]" value="{{ $notification->id }}">
    
    <div class="notification-respond">
        <button class="invisible-button" data-action="{{ route("notifications.delete-one", ["notification" => $notification->id])}}" onclick="delete_one()">
            <i class="fa fa-fw fa-trash"></i>
        </button>
    </div>

</div>