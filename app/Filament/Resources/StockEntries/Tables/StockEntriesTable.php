<?php

namespace App\Filament\Resources\StockEntries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('entry_date')
                    ->label('Tanggal Datang')
                    ->date()
                    ->sortable(),

                TextColumn::make('supplier_name')
                    ->label('Supplier')
                    ->placeholder('Tanpa Nama')
                    ->searchable(),

                // Kita tampilin jumlah item yang datang di nota ini
                TextColumn::make('product_batches_count')
                    ->label('Jumlah Jenis Item')
                    ->counts('productBatches')
                    ->alignCenter(),

                TextColumn::make('total_amount')
                    ->label('Total Kotor')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('discount')
                    ->label('Potongan')
                    ->money('IDR')
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('final_amount')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
            ])
            ->defaultSort('entry_date', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
