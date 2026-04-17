<?php

use App\Http\Controllers\API\App\v1\AppOrderController;
use App\Http\Controllers\API\App\v1\CartController;
use App\Http\Controllers\API\App\v1\MenuController;
use App\Http\Controllers\API\App\v1\SeatController;
use App\Http\Controllers\API\App\v1\TableController;
use App\Http\Controllers\API\App\V1\BillController;
use App\Http\Controllers\API\App\V1\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('app')
    ->middleware(['jwt.verify'])
    ->group(function () {

        //   Cart
        // Route::prefix('cart')->group(function () {
        //     Route::get('/{tableId}',         [CartController::class, 'show']);
        //     Route::post('/{tableId}/add',    [CartController::class, 'add']);
        //     Route::patch('/{tableId}/items/{cartItemId}', [CartController::class, 'update']);
        //     Route::delete('/{tableId}/items/{cartItemId}', [CartController::class, 'remove']);
        //     Route::delete('/{tableId}/clear', [CartController::class, 'clear']);
        // });

        // //  Notification
        // Route::prefix('notifications')->group(function () {
        //     Route::get('/',              [NotificationController::class, 'index']);
        //     Route::patch('/{id}/read',   [NotificationController::class, 'markRead']);
        //     Route::post('/read-all',     [NotificationController::class, 'markAllRead']);
        // });
    });
