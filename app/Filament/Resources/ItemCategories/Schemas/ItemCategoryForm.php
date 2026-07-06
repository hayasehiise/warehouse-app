<?php

namespace App\Filament\Resources\ItemCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Category Name')
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->nullable(),
            ]);
    }
}
