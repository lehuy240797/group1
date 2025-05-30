    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\AuthenticatedSessionController;
    use App\Http\Controllers\Admin\AvailTourManController;
    use App\Http\Controllers\Admin\CusTourManController;
    use App\Http\Controllers\Admin\CustomerController;
    use App\Http\Controllers\Admin\StaffController;
    use App\Http\Controllers\TaskController;
    use App\Http\Controllers\TourDetailsController;
    use App\Http\Controllers\AvailableTourBookingsController;
    use App\Http\Controllers\PaymentController;
    use App\Http\Controllers\MyBookingController;
    use App\Http\Controllers\CustomTourBookingsController;
    use App\Http\Middleware\Role;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\ChatbotController;
    use App\Http\Controllers\Admin\RevenueController;
    use App\Http\Controllers\FeedbackController;
    use App\Http\Controllers\Admin\AdminFeedbackController;


    // Trang chủ
    Route::get('/', [HomeController::class, 'home'])->name('home');


    // Đăng xuất
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Available Tours (public)
    Route::get('/available-tours', [AvailTourManController::class, 'showAvailableTours'])->name('available-tours');
    Route::get('/tour-details/{availableTour}', [TourDetailsController::class, 'show'])->name('tour.details');
    Route::get('/booking/{availableTour}', [AvailableTourBookingsController::class, 'create'])->name('booking.create');
    Route::post('/booking/{availableTour}/payment', [AvailableTourBookingsController::class, 'processPayment'])->name('booking.payment.available');

    // Custom Tours (public)
    Route::get('/custom-tours', function () {
        return view('custom-tours');
    })->name('custom-tours');
    Route::post('/custom-tours', [CusTourManController::class, 'store'])->name('custom-tours.store');
    Route::post('/custom-tour/{tour}/payment', [CustomTourBookingsController::class, 'processCustomPayment'])->name('custom.payment');

    // Form thanh toán chung
    Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');

    // Xử lý chọn phương thức thanh toán
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');

    // Thanh toán bằng thẻ (hiển thị form + xác nhận)
    Route::post('/payment/card', [PaymentController::class, 'confirmCardPayment'])->name('payment.card.confirm');
    Route::post('/payment/confirm/card', [PaymentController::class, 'confirmCardPayment'])->name('payment.confirm.card');

    // QR Code
    Route::get('/payment/qr/{token}', [PaymentController::class, 'handleQRRedirect'])->name('payment.qr.redirect');
    Route::post('/payment/qr/confirm', [PaymentController::class, 'confirmQR'])->name('payment.qr.confirm');

    // Kiểm tra trạng thái thanh toán QR (Ajax polling)
    Route::get('/payment/status/{token}', [PaymentController::class, 'checkPaymentStatus']);
    // Hiển thị trang thành công theo token (dành cho QR redirect chính xác booking)
    Route::get('/payment/success/{token}', [PaymentController::class, 'showSuccessWithToken'])->name('payment.success.token');


    // Xử lý thanh toán thành công
    Route::get('/payment/success', function () {
        return view('payment.success');
    })->name('payment.success');




    // My Bookings (tra cứu booking)
    Route::get('/my-bookings', [MyBookingController::class, 'showSearchForm'])->name('my-bookings');
    Route::post('/my-bookings', [MyBookingController::class, 'search'])->name('my-bookings.submit');

    // Trang tĩnh
    Route::get('/about', function () {
        return view('about');
    })->name('about');

    Route::get('/contact', [FeedbackController::class, 'showFeedbackForm'])->name('feedback.form');
    Route::post('/contact', [FeedbackController::class, 'submitFeedback'])->name('feedback.submit');


    // Route cho chatbot    
    Route::get('/chatbot', [ChatbotController::class, 'index']);
    Route::post('/chat', [App\Http\Controllers\ChatbotController::class, 'chat']);

    // Route quản lý dành cho admin
    Route::middleware(['auth', Role::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/tours/overview', function () {
            return view('management.tours.index');
        })->name('tours.overview');

        // Quản lý Available Tours
        Route::resource('avail-tours', AvailTourManController::class)->names('avail_tour_man');
        Route::put('avail-tours/{avail_tour}/update-status', [AvailTourManController::class, 'updateStatus'])->name('avail_tour_man.updateStatus');


        // Quản lý Custom Tours
        Route::resource('custom-tours', CusTourManController::class)->names('cust_tour_man');
        Route::post('/custom-tours/update-status/{id}', [CusTourManController::class, 'updateStatus'])->name('cust_tour_man.updateStatus');
        Route::post('/custom-tours/{id}/assign-tour-guide', [CusTourManController::class, 'assignTourGuide'])->name('cust_tour_man.assignTourGuide');
        Route::post('/custom-tours/{id}/assign-driver', [CusTourManController::class, 'assignDriver'])->name('cust_tour_man.assignDriver');


        // Quản lý nhân viên
        Route::resource('staff', StaffController::class);

        // Quản lý khách hàng
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

        // Quản lý doanh thu
        Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');

        //Quản lý feedback
        Route::get('/feedbacks', [AdminFeedbackController::class, 'index'])->name('feedbacks.index');
        Route::get('/feedbacks/{id}', [AdminFeedbackController::class, 'showReplyForm'])->name('feedbacks.reply');
        Route::post('/feedbacks/{id}', [AdminFeedbackController::class, 'submitReply'])->name('feedbacks.storeReply');

        // Các trang quản lý khác
        Route::get('/bookings', function () {
            return view('admin.bookings');
        })->name('bookings');

        Route::get('/users', function () {
            return view('admin.users');
        })->name('users');
    });

    // Route cho tourguide và driver
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/my-tasks', [TaskController::class, 'index'])->name('my-tasks');
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
        Route::get('/profile', function () {
            return view('profile');
        })->name('profile');
    });

    // Route auth mặc định của Laravel
    require __DIR__ . '/auth.php';
