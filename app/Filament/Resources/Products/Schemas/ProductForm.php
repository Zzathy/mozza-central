<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->preload()
                            ->searchable(),
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->label('Slug (Otomatis)')
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                    ])->columns(2),
                Section::make('Penetapan harga')
                    ->description('Sistem akan memberikan saran sesuai skema margin, silakan isi harga jual final secara manual.')
                    ->schema([
                        TextInput::make('cost_price')
                            ->label('Harga Modal (Beli)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->live(onBlur: true),
                        Select::make('multiplier_type')
                            ->label('Skema Margin')
                            ->options([
                                '3' => 'Ikan (3x)',
                                '2' => 'Mesin (2x)',
                                'custom' => 'Manual',
                            ])
                            ->default('2')
                            ->live()
                            ->dehydrated(false),
                        TextInput::make('custom_multiplier')
                            ->label('Margin Manual')
                            ->numeric()
                            ->default(1.5)
                            ->visible(fn (Get $get) => $get('multiplier_type') === 'custom')
                            ->live(onBlur: true)
                            ->dehydrated(false),
                        TextInput::make('price')
                            ->label('Harga Jual Final')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->helperText(function (Get $get) {
                                $cost = (float) $get('cost_price');
                                $type = $get('multiplier_type');
                                
                                $mult = ($type === 'custom') ? (float)$get('custom_multiplier') : (float)$type;
                                
                                if ($cost > 0 && $mult > 0) {
                                    $suggested = $cost * $mult;
                                    return "💡 Saran Harga ({$mult}x): Rp " . number_format($suggested, 0, ',', '.');
                                }
                                return 'Input modal untuk melihat saran harga.';
                            }),
                        TextInput::make('price_note')
                            ->label('Catatan Harga Paket')
                            ->placeholder('Misal: 10rb dapat 3')
                            ->helperText('Info ini bakal muncul di kasir biar kamu gak lupa ngasih harga paket.')
                    ])->columns(2),
                Section::make('Status & Stok')
                    ->schema([
                        TextInput::make('min_stock')
                            ->label('Stok Minimal')
                            ->numeric()
                            ->default(1),
                        Toggle::make('is_active')
                            ->label('Produk Aktif')
                            ->default(true)
                    ])->columns(2),
            ]);
    }
}
