<?php

namespace App\Filament\Resources\StockEntryResource\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StockEntryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Nota')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('supplier_name')
                                    ->label('Supplier')
                                    ->placeholder('Umum')
                                    ->weight('bold'),
                                TextEntry::make('entry_date')
                                    ->label('Tanggal Datang')
                                    ->date(),
                                TextEntry::make('final_amount')
                                    ->label('Total Bayar')
                                    ->money('IDR')
                                    ->color('success')
                                    ->weight('bold'),
                            ]),
                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Detail Barang Masuk')
                    ->schema([
                        RepeatableEntry::make('productBatches')
                            ->label('')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label('Produk')
                                    ->weight('bold'),
                                TextEntry::make('initial_qty')
                                    ->label('Jumlah')
                                    ->suffix(' ekor/pcs'),
                                TextEntry::make('buy_price')
                                    ->label('Harga Modal')
                                    ->money('IDR'),
                            ])
                            ->columns(3)
                            ->grid(1), // Bisa dibuat 2 kalau mau hemat tempat
                    ]),
            ]);
    }
}