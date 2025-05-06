<?php
use App\Http\Controllers\PromptGeneratorController;
use Illuminate\Support\Facades\Route;

Route::post('/promp-generator', PromptGeneratorController::class)->name('promptGenerator');
