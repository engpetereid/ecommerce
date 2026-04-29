<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Concerns\Translatable;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\KeyValue;

class ProductResource extends Resource
{
    use Translatable;

    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Product Data')
                    ->tabs([

                        Tabs\Tab::make('General Info')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(Product::class, 'slug', ignoreRecord: true),

                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required(),

                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),

                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull(),

                                Forms\Components\Toggle::make('is_active')->default(true),
                                Forms\Components\Toggle::make('is_featured'),
                                Forms\Components\Toggle::make('has_variants')
                                    ->label('This product has variants (Size, Color...)')
                                    ->live(),
                            ]),


                        Tabs\Tab::make('Images')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Main Image')
                                    ->image()
                                    ->directory('products'),

                                Forms\Components\FileUpload::make('images')
                                    ->label('Gallery')
                                    ->image()
                                    ->multiple()
                                    ->directory('products')
                                    ->reorderable(),
                            ]),


                        Tabs\Tab::make('Variants')
                            ->hidden(fn (Forms\Get $get) => $get('has_variants') !== true)
                            ->schema([

                                Forms\Components\Repeater::make('variants')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('sku')
                                            ->label('SKU')
                                            ->required(),

                                        Forms\Components\TextInput::make('price')
                                            ->numeric()
                                            ->label('Variant Price')
                                            ->helperText('Leave empty if same as main price'),

                                        Forms\Components\TextInput::make('stock')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),


                                        KeyValue::make('options')
                                            ->keyLabel('Option (e.g. Color)')
                                            ->valueLabel('Value (e.g. Red)')
                                            ->reorderable(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(1),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->sortable(),
                Tables\Columns\TextColumn::make('price')->money('USD'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
