<?php

namespace App\Filament\Resources\Distributions\RelationManagers;

use App\Filament\Resources\Distributions\Schemas\DistributionItemForm;
use App\Filament\Resources\Distributions\Tables\DistributionItemTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class DistributionItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'distributionItems';

    public function isReadOnly(): bool
    {
        return $this->ownerRecord->approve_status !== 'pending' || $this->ownerRecord->trashed();
    }

    public function form(Schema $schema): Schema
    {
        return DistributionItemForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return DistributionItemTable::configure($table, $this);
    }
}
