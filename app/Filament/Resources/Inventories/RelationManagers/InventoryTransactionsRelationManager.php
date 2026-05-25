<?php

namespace App\Filament\Resources\Inventories\RelationManagers;

use App\Filament\Resources\Inventories\Schemas\InventoryTrasactionForm;
use App\Filament\Resources\Inventories\Tables\InventoryTransactionTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class InventoryTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'inventoryTransactions';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return InventoryTrasactionForm::configure($schema, $this->ownerRecord);
    }

    public function table(Table $table): Table
    {
        return InventoryTransactionTable::configure($table);
    }
}
