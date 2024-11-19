<?php

namespace App\Filament\Resources\MaintenanceResource\Widgets;

use App\Models\Maintenance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MaintenanceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Mantenimientos', Maintenance::count())
                ->description('Cantidad total de mantenimientos registrados')
                ->color('primary'),

            Stat::make('Mantenimientos Pendientes', Maintenance::where('status_id', 3)->count())
                ->description('Mantenimientos en progreso')
                ->color('danger'),

            Stat::make('Mantenimientos Pendientes', Maintenance::where('status_id', 4)->count())
                ->description('Mantenimientos finalizados exitosamente')
                ->color('danger'),

            Stat::make('Ingresos Totales', '$' . number_format(Maintenance::sum('pricing'), 2))
                ->description('Ingresos generados por mantenimientos')
                ->color('secondary'),
        ];
    }
}
