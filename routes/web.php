<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Mail\SendRequest;
use Illuminate\Support\Facades\Route;


Route::get('/', App\Livewire\Guest\Home::class)->name('home');
Route::get('/home', App\Livewire\Guest\Home::class)->name('home.redirect');

Route::get('/feedback-list', App\Livewire\Guest\FeedbackList::class)->name('guest.feedback-list');
Route::get('/feedback/{token}', App\Livewire\Guest\FeedbackForm::class)->name('feedback.form');

Route::get('/product/feedback/{product}', App\Livewire\Guest\ProductFeedback::class)->name('guest.product.feedback');


Route::get('dashboard', App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'role:admin|dev|staff'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('setups', App\Livewire\Main\SetupIndex::class)->name('setups.index');

    Route::get('users', App\Livewire\Main\UserIndex::class)->name('users.index')->middleware('permission:view user');
    Route::get('/users/create', App\Livewire\Forms\UserForm::class)->name('users.create')->middleware('permission:create user');
    Route::get('/users/update/{user}', App\Livewire\Forms\UserForm::class)->name('users.edit')->middleware('permission:update user');

    Route::get('/users/manage-user-permissions/{user}', App\Livewire\Main\ManageUserPermission::class)
        ->name('users.manage-user-permission')
        ->middleware('permission:assign permission');

    Route::get('feedbacks', App\Livewire\Main\FeedbackIndex::class)->name('feedbacks.index');
    Route::get('feedbacks/categories', App\Livewire\Main\FeedbackCategoryIndex::class)->name('feedback-categories.index');
    Route::get('feedbacks/request', App\Livewire\Main\FeedbackRequest::class)->name('feedbacks.request');
    Route::get('feedbacks/response/{token}', App\Livewire\Main\FeedbackResponse::class)->name('feedbacks.response.detail');

    Route::get('feedbacks/response', App\Livewire\Main\FeedbackResponse::class)->name('feedbacks.response');

    Route::get('products', App\Livewire\Main\ProductIndex::class)->name('products.index')->middleware('permission:view product');
    Route::get('products/categories', App\Livewire\Main\ProductCategoryIndex::class)->name('product-categories.index')->middleware('permission:view product category');
    Route::get('/products/create', App\Livewire\Forms\ProductForm::class)->name('products.create')->middleware('permission:create product');
    Route::get('/products/edit/{product}', App\Livewire\Forms\ProductForm::class)->name('products.edit')->middleware('permission:update product');

    Route::get('templates', App\Livewire\Main\TemplateIndex::class)->name('templates.index');
    Route::get('/templates/create', App\Livewire\Forms\TemplateFrom::class)->name('templates.create')->middleware('permission:create template');
    Route::get('/templates/edit/{template}', App\Livewire\Forms\TemplateFrom::class)->name('templates.edit')->middleware('permission:update template');


    Route::get('/roles', App\Livewire\Main\RoleIndex::class)->name('roles.index')->middleware('permission:view role');
    Route::get('/permissions', App\Livewire\Main\PermissionIndex::class)->name('permissions.index')->middleware('permission:view permission');
    Route::get('/roles/manage-permissions/{role}', App\Livewire\Forms\ManagePermissions::class)->name('roles.manage_permissions')->middleware('permission:assign permission');

    Route::get('reports', App\Livewire\Main\ReportsIndex::class)->name('reports.index');
    Route::get('activity-logs', App\Livewire\Main\ActivityLogIndex::class)->name('activity-logs.index');
    Route::get('sent-logs', App\Livewire\Main\SentLogIndex::class)->name('sent-logs.index');
    Route::get('guest-users', App\Livewire\Main\GuestUserIndex::class)->name('guest-user.index');
});


use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    try {
        Mail::raw('This is a test email from Laravel.', function ($message) {
            $message->to('fuseiniabdulhafiz29@gmail.com') // replace with your personal email
                ->subject('Laravel Mail Test');
        });

        return 'Mail sent successfully!';
    } catch (\Exception $e) {
        return 'Error sending mail: ' . $e->getMessage();
    }
});


Route::get('/mail-preview', function () {
    $feedback = \App\Models\Feedback::latest()->first();
    $message = 'Please take a moment to tell us what you think about our products.';
    return new \App\Mail\FeedbackRequestMail($feedback, $message, 'adam');
});

Route::get('/test-mail', function () {
    Mail::to('fuseiniabdulhafiz29@gmail.com.com')->send(new SendRequest(
        clientName: 'Test User',
        messageContent: 'Please give feedback on Product A, Product B.',
        feedbackLink: 'https://example.com/feedback/1234'
    ));

    return 'Mail sent!';
});

require __DIR__ . '/auth.php';
