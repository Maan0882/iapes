<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use Filament\Resources\Resource;


class ReportResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Reports & Analytics';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 20;
    protected static ?string $slug = 'reports';

    // No model needed – this is a custom page resource
    protected static ?string $model = null;

    public static function getPages(): array
    {
        return [
            'index' => Pages\ReportIndex::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

}
