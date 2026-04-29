<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;


    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';


    protected static ?int $navigationSort = 3;


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() > 0 ? 'danger' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        TextInput::make('number')
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->native(false),

                        Select::make('payment_method')
                            ->options([
                                'cod' => 'Cash on Delivery',
                                'card' => 'Credit Card',
                            ])
                            ->disabled(),

                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columns(2),


                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->label('Product')
                                    ->disabled()
                                    ->required(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->disabled()
                                    ->required(),

                                TextInput::make('unit_price')
                                    ->prefix('$')
                                    ->disabled()
                                    ->required(),

                                TextInput::make('total_price')
                                    ->prefix('$')
                                    ->disabled()
                                    ->required(),
                            ])
                            ->columns(4)
                            ->addable(false)
                            ->deletable(false)
                    ])
                ])->columnSpan(['lg' => 2]),

                Group::make()->schema([
                    Section::make('Customer Details')->schema([
                        TextInput::make('address_info.first_name')->label('First Name')->disabled(),
                        TextInput::make('address_info.last_name')->label('Last Name')->disabled(),
                        TextInput::make('address_info.phone')->label('Phone')->disabled(),
                        TextInput::make('address_info.city')->label('City')->disabled(),
                        TextInput::make('address_info.street_address')->label('Address')->disabled(),
                        TextInput::make('address_info.zip_code')->label('Zip Code')->disabled(),
                    ]),

                    Section::make('Totals')->schema([
                        TextInput::make('total_price')
                            ->prefix('$')
                            ->disabled(),

                        TextInput::make('shipping_price')
                            ->prefix('$')
                            ->disabled(),

                        Placeholder::make('grand_total_placeholder')
                            ->label('Grand Total')
                            ->content(fn ($record) => '$' . number_format($record?->total_price + $record?->shipping_price, 2)),
                    ])
                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
