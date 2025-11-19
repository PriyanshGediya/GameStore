<?php
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\GameController;    
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\Customer\MembershipController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;    
use App\Models\Game;
// Middleware Aliases (Assuming these are correctly defined in Kernel.php)
use App\Http\Middleware\AuthCheck;
use App\Http\Middleware\RoleCheck; // Admin
use App\Http\Middleware\RoleCustomerCheck; // Customer
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This refactored version uses Route::prefix() and Route::name() for clarity
| and consistency, especially within the authenticated groups.
|
*/

// --- Public Routes ---

Route::get('/email/verify', function () {
    return view('auth.verify-email'); 
})->middleware('auth')->name('verification.notice');

// Verification handler

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    // Find the user by ID
    $user = User::findOrFail($id);

    // Validate the signed URL hash
    if (!hash_equals((string) $hash, sha1($user->email))) {
        abort(403, 'Invalid verification link');
    }

    // Mark email as verified if not already
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    // Redirect to login page with success message
    return redirect()->route('login_page')->with('success', 'Email verified! You can now log in.');
})->middleware('signed')->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/', function () {
    return view('/landingpage');
})->name('landing_page');

// Authentication & Registration
Route::get('/register', fn () => view('auth.register'))->name('register_page');
Route::post('/register', [AuthenticationController::class, 'register_logic'])->name('register_logic');

Route::get('/login', fn () => view('auth.login'))->name('login_page');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');


// Pass forget for guest user)
Route::get('/forgot-password', [ProfilesController::class, 'forgotPasswordPage'])->name('forgot_password.page');
Route::post('/forgot-password/send-otp', [ProfilesController::class, 'sendPasswordOtp'])->name('forgot_password.send_otp');
Route::get('/forgot-password/verify', [ProfilesController::class, 'verifyPasswordOtpPage'])->name('forgot_password.verify_page');
Route::post('/forgot-password/reset', [ProfilesController::class, 'resetPassword'])->name('forgot_password.reset');
Route::post('/forgot-password/verify', [ProfilesController::class, 'verifyForgotPasswordOtp'])
    ->name('forgot_password.verify');


// Guest Home & Search/Detail (Read-only access)
Route::get('/home', [GameController::class, 'homeIndex'])->name('home_guest');
Route::get('/guest/search', [GameController::class, 'searchGame'])->name('guest.search');

// web.php
Route::get('/detail/{gameDetail}', [GameController::class, 'detail'])->name('detail_guest');
Route::post('/subscribe', [MembershipController::class, 'subscribe'])->name('subscribe');

// WARNING: Publicly accessible profile view.
Route::get('/profile/{id}', [ProfilesController::class, 'myProfile'])->name('my_profile');


// --- Authenticated User Home Routes (Role-specific entry points) ---

Route::get('/home/customer', [GameController::class, 'homeIndex'])
    ->name('home_customer')
    ->middleware([AuthCheck::class, RoleCustomerCheck::class]);

Route::get('/home/admin', [GameController::class, 'adminHome'])
    ->name('home_admin')
    ->middleware(['auth', 'role:admin']);




// --- Customer Routes (AuthCheck & RoleCustomerCheck) ---

Route::middleware([AuthCheck::class, RoleCustomerCheck::class])->name('customer.')->group(function () {


    Route::get('/library', [App\Http\Controllers\GameController::class, 'library'])
    ->name('customer.library')
    ->middleware('auth');


    // --- Profile Management ---
    Route::get('/profile', [ProfilesController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfilesController::class, 'update'])->name('profile.update');
    Route::post('/profile/picture', [ProfilesController::class, 'updatePicture'])->name('profile.picture.update');

    // --- Security (Email & Password) ---
    Route::post('/profile/email/request', [ProfilesController::class, 'requestEmailChange'])->name('profile.email.request');
    Route::post('/profile/email/verify', [ProfilesController::class, 'verifyEmailOtp'])->name('profile.email.verify');
    Route::post('/profile/password/otp/request', [ProfilesController::class, 'requestPasswordOtp'])->name('profile.password.otp.request');
    Route::post('/profile/password/otp/verify', [ProfilesController::class, 'verifyPasswordOtp'])->name('profile.password.otp.verify');
    Route::post('/profile/password/update', [ProfilesController::class, 'updatePasswordAfterOtp'])->name('profile.password.update');


    /* --- Your Other Customer Routes --- */
    Route::get('/detail/{id}', [GameController::class, 'detail'])->name('detail');
    Route::get('/search', [GameController::class, 'searchGame'])->name('search');
    Route::post('/install/{id}', [GameController::class, 'installGame'])->name('install_game');

    Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [TransactionsController::class, 'cart_view'])->name('view');
    Route::post('/add', [TransactionsController::class, 'add_to_cart'])->name('add');
    Route::delete('/delete/{id}', [TransactionsController::class, 'delete_game_in_cart'])->name('game.delete');
    Route::post('/checkout', [TransactionsController::class, 'purchase'])->name('checkout');
    Route::delete('/', [TransactionsController::class, 'delete_cart'])->name('delete_all');
    
    // MOVE THE CALLBACK ROUTE HERE AND SIMPLIFY ITS NAME
    Route::post('/callback', [TransactionsController::class, 'callback'])->name('callback');

    // Customer Genre Filter
    Route::get('/filter/genre/{id}', [GameController::class, 'filterByGenre'])
     ->name('filter.genre');


});
    
    // FIX: The redundant checkout route has been removed. The one inside the group above is correct.

    // To this:
Route::post('/cart/callback', [TransactionsController::class, 'callback'])->name('cart.callback');

    Route::get('/history', [TransactionsController::class, 'history_index'])->name('history');
    Route::post('/review', [ReviewController::class, 'store'])->name('review.submit');
    Route::get('/membership/checkout/{plan_id}', [MembershipController::class, 'checkout'])->name('membership.checkout');
    // --- FIND AND DELETE THESE TWO ROUTES ---

// --- ADD THIS NEW ROUTE ---

// This route handles the secure verification AFTER a successful Razorpay payment
Route::post('/membership/verify-payment', [MembershipController::class, 'verifyPayment'])->name('membership.verify');

    
});


// --- Admin Routes (AuthCheck & RoleCheck) ---

Route::middleware([AuthCheck::class, RoleCheck::class])->prefix('admin')->name('admin.')->group(function () {
// AFTER: Corrected Route
Route::get('/games/{id}/manage', function ($id) {
    $game = Game::findOrFail($id); // single game
    $genres = \App\Models\Genre::all(); // pass genres for the dropdown
    return view('ManageGame.ManageGame_update', compact('game', 'genres'));
})->name('games.manage');



    //Membership 
      Route::get('/membership', [AdminsController::class, 'membership_index'])->name('membership.index');
    Route::get('/membership/create', [AdminsController::class, 'membership_create'])->name('membership.create');
    Route::post('/membership/store', [AdminsController::class, 'membership_store'])->name('membership.store');
    Route::get('/membership/{id}/edit', [AdminsController::class, 'membership_edit'])->name('membership.edit');
    Route::put('/membership/{id}/update', [AdminsController::class, 'membership_update'])->name('membership.update');
    Route::delete('/membership/{id}/delete', [AdminsController::class, 'membership_delete'])->name('membership.delete');


    // Admin Profile & Search
    Route::get('/profile', [ProfilesController::class, 'updateProfileView'])->name('profile.view');
    Route::post('/profile/update', [ProfilesController::class, 'updateProfileLogic'])->name('profile.update');
    Route::get('/detail/{id}', [GameController::class, 'detail'])->name('detail');
    Route::get('/search', [GameController::class, 'searchGame'])->name('search');
    Route::get('/search/games', [AdminsController::class, 'searchManageGames'])->name('search.games');

    // Game Management
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/', [AdminsController::class, 'game_index'])->name('index');
        Route::delete('/{id}', [AdminsController::class, 'deleteGame'])->name('delete');
        Route::get('/{id}/edit', [AdminsController::class, 'updateGameView'])->name('edit');
        Route::patch('/{id}', [AdminsController::class, 'updateGameLogic'])->name('update');
        Route::get('/create', [AdminsController::class, 'createGame'])->name('create');
        Route::post('/', [AdminsController::class, 'addGameLogic'])->name('store');
    });

    // Genre Management
    Route::prefix('genres')->name('genres.')->group(function () {
        Route::get('/', [AdminsController::class, 'genre_index'])->name('index');
         Route::get('/create', [GenreController::class, 'create'])->name('create');
        Route::get('/{id}', [GenreController::class, 'genre_detail'])->name('show');
        Route::patch('/{id}', [AdminsController::class, 'updateGenreName'])->name('update');
        Route::post('/', [GenreController::class, 'store'])->name('store');
    });

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [AdminsController::class, 'manageUsers'])->name('index');
    Route::get('/create', [AdminsController::class, 'createUserView'])->name('create'); // <-- Add this
    Route::post('/', [AdminsController::class, 'storeUser'])->name('store'); // <-- To save the new user
    Route::get('/{id}/edit', [AdminsController::class, 'editUserView'])->name('edit');
    Route::patch('/{id}', [AdminsController::class, 'updateUser'])->name('update');
    Route::delete('/{id}', [AdminsController::class, 'deleteUser'])->name('delete');
});


    // Review Management
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.delete');

    // Membership Plan Management
    Route::get('/membership', [AdminsController::class, 'membership_index'])->name('membership.index');
});
