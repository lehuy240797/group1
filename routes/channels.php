<?php

use Illuminate\Support\Facades\Broadcast;
Broadcast::channel('booking-status.{token}', function ($user = null, $token) {
    // Trả về true để cho phép tất cả user (kể cả chưa đăng nhập) subscribe kênh private này
    return true;
});
