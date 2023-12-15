<div class="p-2" id="delete-{{ $notification->id }}">
    <input type="hidden" name="notifications[]" value="{{ $notification->id }}">
    <button class="invisible-button" data-action="{{ route("notifications.delete-one", ["notification" => $notification->id])}}" onclick="delete_one()">
        <i class="fa fa-times"></i>
    </button>
    <span class="small text-muted mr-2">
        {{ $notification->created_at->diffForHumans() }}
    </span>
    {!! $notification->getDisplayHTML() !!}
</div>