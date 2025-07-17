    <?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\API\BookingController;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    | These routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. They use the 'api' prefix by default.
    */

    // Protected API routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {

        // Get the authenticated user's info
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Booking API CRUD routes
        Route::get('/bookings', [BookingController::class, 'apiIndex']);          // List all bookings
        Route::post('/bookings', [BookingController::class, 'store']);            // Create new booking
        Route::get('/bookings/{booking}', [BookingController::class, 'show']);    // Show single booking
        Route::put('/bookings/{booking}', [BookingController::class, 'update']);  // Update booking
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']); // Delete booking
    });
