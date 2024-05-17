<?php
/**
 * En este archivo se definen las rutas y middleware para la aplicación Laravel.
 * Utiliza el controlador ProfileController para gestionar las operaciones relacionadas con el perfil de usuario.
 */

use App\Http\Controllers\ProfileController; // Importa el controlador ProfileController
use Illuminate\Support\Facades\Route; // Importa la clase Route de Laravel

// Rutas de visualización
Route::view('/', 'welcome'); // Ruta para la página de inicio
Route::view('/hompage','homepage'); // Ruta alternativa para la página de inicio

// Ruta para el dashboard, requiere autenticación y verificación del usuario
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grupo de rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    // Ruta para editar el perfil, utiliza el método 'edit' del controlador ProfileController
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Ruta para actualizar el perfil, utiliza el método 'update' del controlador ProfileController
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Ruta para eliminar el perfil, utiliza el método 'destroy' del controlador ProfileController
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Incluye las rutas de autenticación generadas por Laravel
require __DIR__.'/auth.php';
?>
