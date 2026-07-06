<?php

namespace App\Filament\Resources\Inventories\RelationManagers;

use App\Filament\Resources\Inventories\Tables\DistributionTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class DistributionItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'distributionItems';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return DistributionTable::configure($table);
    }
}
