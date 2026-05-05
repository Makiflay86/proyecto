<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Controlador del dashboard principal.
 *
 * La lógica de datos (queries, stats, gráficos) vive en el componente
 * Livewire DashboardContent, que se auto-refresca cada 30 s con #[Poll].
 * Este controlador solo se encarga de renderizar la vista contenedora.
 */
class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
