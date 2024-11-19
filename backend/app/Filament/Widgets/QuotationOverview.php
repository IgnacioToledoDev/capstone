<?php

namespace App\Filament\Widgets;

use App\Models\Quotation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuotationOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Cotizaciones', Quotation::count())
                ->description('Cantidad total de cotizaciones registradas')
                ->color('primary'),

            Stat::make('Cotizaciones Aprobadas', Quotation::where('approved_by_client', true)->count())
                ->description('Cotizaciones aprobadas por los clientes')
                ->color('success'),

            Stat::make('Cotizaciones Pendientes', Quotation::where('approved_by_client', false)->count())
                ->description('Cotizaciones pendientes de aprobación')
                ->color('warning'),
        ];
    }
}
