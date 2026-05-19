<?php

namespace App\Filament\Resources\Distributions\RelationManagers;

use App\Models\Item;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class DistributionItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'distributionItems';

    public function isReadOnly(): bool
    {
        return $this->ownerRecord->approve_status === 'approved' || $this->ownerRecord->trashed();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_id')
                    ->label('Barang')
                    ->relationship('item', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('qty')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->rule(function (Get $get) {
                        return function (
                            string $attribute,
                            $value,
                            $fail
                        ) use ($get) {
                            $item = Item::find($get('item_id'));
                            if (! $item) {
                                return;
                            }

                            if ($item->itemStock->quantity < $value) {
                                $fail('Jumlah stok tidak cukup!');
                            }
                        };
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('public_id')
            ->columns([
                TextColumn::make('item.name')
                    ->label('Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('qty')
                    ->label('Jumlah')
                    ->numeric(),
                TextColumn::make('item.itemStock.unit')
                    ->label('Satuan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('lucide-plus')
                    ->label('Barang')
                    ->before(function (array $data) {
                        $exist = $this->ownerRecord->distributionItems()->where('item_id', $data['item_id'])->exists();
                        if ($exist) {
                            Notification::make()
                                ->title('Barang sudah ada di list!')
                                ->danger()
                                ->send();
                            throw ValidationException::withMessages([
                                'item_id' => 'Barang sudah ada di list!',
                            ]);
                        }
                    }),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make()->hidden(fn ($record) => $record->approve_status === 'approved'),
                // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
