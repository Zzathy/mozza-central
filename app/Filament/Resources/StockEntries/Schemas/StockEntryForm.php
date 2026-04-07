<?php

namespace App\Filament\Resources\StockEntries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class StockEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Nota Supplier')
                    ->description('Isi detail umum dari nota atau pengiriman barang.')
                    ->schema([
                        TextInput::make('supplier_name')
                            ->label('Nama Supplier')
                            ->placeholder('Contoh: Supplier Ikan Tulungagung')
                            ->columnSpan(1),
                        DatePicker::make('entry_date')
                            ->label('Tanggal Datang')
                            ->default(now())
                            ->required()
                            ->columnSpan(1),
                        Textarea::make('notes')
                            ->label('Catatan Tambahan')
                            ->placeholder('Misal: Ada bonus pakan, atau ikan ada yang lemas')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),
                Section::make('Daftar Barang Datang')
                    ->description('Klik "Tambah Barang" untuk memasukkan item sesuai nota.')
                    ->schema([
                        Repeater::make('productBatches')
                            ->label('Detail Barang')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $product = \App\Models\Product::find($state);
                                        if ($product) {
                                            $set('buy_price', $product->cost_price);
                                        }
                                    })
                                    ->columnSpan(2),
                                TextInput::make('initial_qty')
                                    ->label('Stok Masuk')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Set $set) => $set('remaining_qty', $state))
                                    ->columnSpan(1),
                                TextInput::make('buy_price')
                                    ->label('Harga Per Item')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->columnSpan(1),
                                TextInput::make('remaining_qty')
                                    ->hidden()
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Barang Lain')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $batches = $get('productBatches') ?? [];
                                $total = 0;

                                foreach ($batches as $batch) {
                                    $qty = (float) ($batch['initial_qty'] ?? 0);
                                    $price = (float) ($batch['buy_price'] ?? 0);
                                    $total += ($qty * $price);
                                }

                                $set('total_amount', $total);
                                
                                $discount = (float) $get('discount');
                                $set('final_amount', $total - $discount);
                            }),
                    ]),
                Section::make('Ringkasan Pembayaran')
                    ->description('Total akan terhitung otomatis berdasarkan daftar barang di bawah.')
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Total Kotor')
                            ->prefix('Rp')
                            ->numeric()
                            ->readOnly()
                            ->live(),

                        TextInput::make('discount')
                            ->label('Diskon Supplier')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $totalKotor = (float) $get('total_amount');
                                $diskon = (float) $state;
                                
                                $set('final_amount', $totalKotor - $diskon);
                            }),
                        TextInput::make('final_amount')
                            ->label('Total Bayar (Final)')
                            ->prefix('Rp')
                            ->numeric()
                            ->readOnly()
                            ->helperText('Hasil dari: Total Kotor - Potongan'),
                    ])->columns(3),
            ]);
    }
}
