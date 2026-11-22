<?php

use App\Http\Controllers\Admin\AdminContentController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFeedbackController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminSponsorController;
use App\Http\Controllers\Admin\AdminSystemController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AiResearchController;
use App\Http\Controllers\ArabicController;
use App\Http\Controllers\Auth\EmailAuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DuaController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HadithAudioController;
use App\Http\Controllers\HadithController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SponsorController;
use App\Livewire\DuaIndex;
use App\Livewire\Library;
use App\Livewire\QuranIndex;
use App\Livewire\SupportIndex;
use App\Livewire\TopicIndex;
use Illuminate\Support\Facades\Route;

// SEO
Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::get('/sitemap.xml', [SeoController::class, 'index']);
Route::get('/sitemap-pages.xml', [SeoController::class, 'pages']);
Route::get('/sitemap-hadith-{page}.xml', [SeoController::class, 'hadith'])->whereNumber('page');

Route::get('/', Library::class)->name('library');
Route::get('/makusanyo/{collection:slug}', [CollectionController::class, 'show'])->name('collections.show');
Route::get('/makusanyo/{collection:slug}/{book}', [CollectionController::class, 'book'])->whereNumber('book')->name('collections.book');
Route::get('/makusanyo/{collection:slug}/{book}/{chapter}', [CollectionController::class, 'chapter'])->whereNumber(['book', 'chapter'])->name('collections.chapter');
Route::get('/mada', TopicIndex::class)->name('topics.index');
Route::get('/mada/{slug}', TopicIndex::class)->name('topics.show');
Route::get('/changia', SupportIndex::class)->name('support');
Route::view('/wadhamini', 'sponsors')->name('sponsors');
Route::get('/wadhamini/bofya/{sponsor}', [SponsorController::class, 'go'])->middleware('throttle:60,1')->name('sponsors.go');
Route::redirect('/sadaka', '/changia')->name('support.alias');

// Njia za Malipo (Pesapal Callback, IPN Webhook, Risiti ya Kidigitali, Majaribio)
Route::get('/malipo/callback', [PaymentController::class, 'callback'])->name('payments.callback');
Route::match(['get', 'post'], '/malipo/ipn', [PaymentController::class, 'ipn'])->name('payments.ipn');
Route::post('/malipo/webhook/clickpesa', [PaymentController::class, 'clickPesaWebhook'])->name('payments.webhook.clickpesa');
Route::get('/malipo/risiti/{reference}', [PaymentController::class, 'receipt'])->name('payments.receipt');
Route::get('/malipo/simulation/{reference}', [PaymentController::class, 'simulate'])->name('payments.simulate');

Route::get('/quran', QuranIndex::class)->name('quran.index');
Route::get('/quran/{number}', [QuranController::class, 'show'])->whereNumber('number')->name('quran.show');

Route::get('/duaa', DuaIndex::class)->name('duaa.index');
Route::get('/duaa/{dua}', [DuaController::class, 'show'])->name('duaa.show');

Route::view('/tasbih', 'tasbih')->name('tasbih');
Route::view('/qibla', 'qibla')->name('qibla');
Route::view('/tarehe-ya-kiislamu', 'islamic-date')->name('islamic.date');
Route::view('/ramadhani', 'ramadhan')->name('ramadhan');
Route::view('/nyakati-za-swala', 'prayer-times')->name('prayer.times');
Route::redirect('/prayer-times', '/nyakati-za-swala');

Route::get('/hadith/{hadith}', [HadithController::class, 'show'])->name('hadith.show');
Route::get('/hadith-ya-leo', [HadithController::class, 'today'])->name('hadith.today');

// ---- Research & Knowledge Graph ----
Route::prefix('utafiti')->name('research.')->group(function () {
    Route::get('/', [ResearchController::class, 'hub'])->name('hub');
    Route::get('/linganisha', [ResearchController::class, 'compare'])->name('compare');
    Route::get('/ramani/{hadith}', [ResearchController::class, 'graph'])->middleware('throttle:60,1')->name('graph');
});

Route::view('/kuhusu', 'about')->name('about');

// ---- AI Research (inahitaji AI_API_KEY) ----
Route::prefix('msaidizi')->name('ai.')->group(function () {
    Route::get('/', [AiResearchController::class, 'index'])->name('index');
    Route::post('/uliza', [AiResearchController::class, 'ask'])->middleware('throttle:10,1')->name('ask');
    Route::post('/faida/{hadith}', [AiResearchController::class, 'lessons'])->middleware('throttle:30,1')->name('lessons');
    Route::post('/chat/{hadith}', [AiResearchController::class, 'chat'])->middleware('throttle:30,1')->name('chat');
});

// ---- Arabic Analyzer (lite) ----
Route::prefix('kiarabu')->name('arabic.')->group(function () {
    Route::get('/neno', [ArabicController::class, 'word'])->middleware('throttle:90,1')->name('word');
    Route::get('/{hadith?}', [ArabicController::class, 'index'])->name('index');
});

// ---- Learning Centre ----
Route::prefix('jifunze')->name('learning.')->group(function () {
    Route::get('/', [LearningController::class, 'index'])->name('index');
    Route::get('/kozi/{book}', [LearningController::class, 'course'])->name('course');
    Route::get('/somo/{hadith}', [LearningController::class, 'lesson'])->name('lesson');
    Route::get('/quiz/{book?}', [LearningController::class, 'quiz'])->name('quiz');
    Route::get('/quiz-data/{book?}', [LearningController::class, 'quizData'])->middleware('throttle:60,1')->name('quiz.data');
    Route::get('/kuhifadhi', [LearningController::class, 'memorize'])->name('memorize');

    Route::middleware(['auth', 'throttle:120,1'])->group(function () {
        Route::get('/kuhifadhi/data', [LearningController::class, 'memorizeData'])->name('memorize.data');
        Route::post('/somo/{hadith}/kamilisha', [LearningController::class, 'toggleComplete'])->name('complete');
        Route::post('/kuhifadhi/{hadith}/ongeza', [LearningController::class, 'addCard'])->name('card.add');
        Route::post('/kuhifadhi/{hadith}/review', [LearningController::class, 'reviewCard'])->name('card.review');
        Route::post('/shughuli', [LearningController::class, 'activity'])->name('activity');
    });
});

// ---- Vipendwa (Bookmarks) ----
Route::get('/vipendwa', [BookmarkController::class, 'index'])->name('bookmarks');
Route::get('/vipendwa/ids', [BookmarkController::class, 'ids'])->name('bookmarks.ids');
Route::get('/vipendwa/data', [BookmarkController::class, 'data'])->name('bookmarks.data');
Route::post('/vipendwa/toggle/{hadith}', [BookmarkController::class, 'toggle'])->middleware('auth')->name('bookmarks.toggle');
Route::post('/vipendwa/sync', [BookmarkController::class, 'sync'])->middleware('auth')->name('bookmarks.sync');

// Social login — fomu iko kwenye drawer ya pembeni (layout), /login huifungua tu.
Route::redirect('/login', '/?login=1')->name('login');

Route::middleware(['guest', 'throttle:20,1'])->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->whereIn('provider', SocialAuthController::PROVIDERS)->name('auth.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->whereIn('provider', SocialAuthController::PROVIDERS)->name('auth.callback');

    // Email + nenosiri
    Route::post('/auth/register', [EmailAuthController::class, 'register'])->name('auth.register');
    Route::post('/auth/login', [EmailAuthController::class, 'login'])->name('auth.login');

    // Umesahau nenosiri?
    Route::post('/auth/forgot', [PasswordResetController::class, 'sendLink'])->name('password.email');
    Route::get('/auth/reset/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/auth/reset', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [SocialAuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/wasifu', [ProfileController::class, 'show'])->middleware('auth')->name('profile');

// Sauti ya hadith (Mfumo mseto: Studio + Neural)
Route::get('/audio/hadith/{hadith}/info', [HadithAudioController::class, 'info'])
    ->name('hadith.audio.info');
Route::get('/audio/hadith/{hadith}/{lang}', HadithAudioController::class)
    ->middleware('throttle:30,1')
    ->name('hadith.audio');

// =========================================================================
// Maoni na Mapendekezo ya Watumiaji (User Feedback)
// =========================================================================
Route::post('/api/feedback', [FeedbackController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('api.feedback.store');

// =========================================================================
// Arifa za Kila Siku (PWA Push Notifications) & Hadithi ya Siku API
// =========================================================================
Route::post('/api/push-subscriptions', [PushNotificationController::class, 'subscribe'])->name('api.push.subscribe');
Route::delete('/api/push-subscriptions', [PushNotificationController::class, 'unsubscribe'])->name('api.push.unsubscribe');
Route::get('/api/hadith-ya-leo', [PushNotificationController::class, 'todayHadith'])->name('api.hadith.today');

// =========================================================================
// Jopo Kuu la Utawala (Admin Panel) - Inalindwa na 'auth' na 'admin'
// =========================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/malipo/export', [AdminPaymentController::class, 'export'])->name('payments.export');

    // Usimamizi wa Watumiaji
    Route::post('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::post('/users/{user}/toggle-supporter', [AdminUserController::class, 'toggleSupporter'])->name('users.toggle-supporter');

    // Usimamizi wa Maoni & Mapendekezo
    Route::post('/feedback/{feedback}/status', [AdminFeedbackController::class, 'updateStatus'])->name('feedback.status');
    Route::delete('/feedback/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('feedback.delete');

    // Usimamizi wa Maudhui
    Route::post('/wadhamini', [AdminSponsorController::class, 'store'])->name('sponsors.store');
    Route::post('/wadhamini/{sponsor}/toggle', [AdminSponsorController::class, 'toggle'])->name('sponsors.toggle');
    Route::delete('/wadhamini/{sponsor}', [AdminSponsorController::class, 'destroy'])->name('sponsors.delete');
    Route::post('/hadith/{hadith}/toggle-publish', [AdminContentController::class, 'toggleHadithPublish'])->name('hadith.toggle-publish');
    Route::post('/dua/{dua}/toggle-publish', [AdminContentController::class, 'toggleDuaPublish'])->name('dua.toggle-publish');

    // Mfumo & Cache
    Route::post('/system/clear-cache', [AdminSystemController::class, 'clearCache'])->name('system.clear-cache');
});
