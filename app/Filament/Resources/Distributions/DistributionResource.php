<?php

namespace App\Filament\Resources\Distributions;

use App\Filament\Resources\Distributions\Pages\CreateDistribution;
use App\Filament\Resources\Distributions\Pages\ListDistributions;
use App\Filament\Resources\Distributions\Pages\ViewDistribution;
use App\Filament\Resources\Distributions\RelationManagers\DistributionItemsRelationManager;
use App\Filament\Resources\Distributions\Schemas\DistributionForm;
use App\Filament\Resources\Distributions\Schemas\DistributionInfolist;
use App\Filament\Resources\Distributions\Tables\DistributionsTable;
use App\Models\Distribution;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistributionResource extends Resource
{
    protected static ?string $model = Distribution::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'distribution_code';

    protected static ?string $navigationLabel = 'Pengeluaran Barang';

    protected static ?string $modelLabel = 'Pengeluaran Barang';

    protected static ?string $pluralModelLabel = 'Pengeluaran Barang';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return DistributionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DistributionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DistributionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DistributionItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDistributions::route('/'),
            'create' => CreateDistribution::route('/create'),
            'view' => ViewDistribution::route('/{record}'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
