<div class="dummy-checkbox">
    <input class="real-checkbox" type="checkbox" form="notification-manage" name="notification_checked[]" value="{{ $notification->id }}">
    <div class="dummy-checkbox-box" onclick="$(this.previousElementSibling).prop('checked', !$(this.previousElementSibling).prop('checked'))"></div>
</div>