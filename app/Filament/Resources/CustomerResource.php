<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer Information')
                    ->description('Enter the customer\'s information.')
                    ->aside()
                    ->collapsible()
                    ->schema([
                        TextInput::make('user.email')
                            ->autofocus()
                            ->required()
                            ->email()
                            ->hiddenOn(['edit'])
                            ->placeholder(__('Email'))
                            ->maxLength(255),
                        TextInput::make('first_name')
                            ->autofocus()
                            ->required()
                            ->placeholder(__('First Name'))
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->autofocus()
                            ->required()
                            ->placeholder(__('Last Name'))
                            ->maxLength(255),
                        Select::make('user.email')
                            ->relationship('user', 'email')
                            ->disabled()
                            ->autofocus()
                            ->label(__('Email'))
                            ->required()
                            ->hiddenOn(['create']),
                        TextInput::make('phone_number')
                            ->numeric()
                            ->autofocus()
                            ->required()
                            ->placeholder(__('Phone Number'))
                            ->minLength(10)
                            ->maxLength(12),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->sortable()
                    ->searchable(['first_name', 'last_name'])
                    ->state(function (Model $record): string {
                        return $record->first_name . ' ' . $record->last_name;
                    }),
                TextColumn::make('user.email')
                    ->label(__('Email'))
                    ->sortable()
                    ->searchable()
                // ->url(fn (Customer $customer) => "mailto:{$customer->email}"),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            // 'address' => Pages\ViewCustomerAddress::route('/{record}/address/{address_id}'),
        ];
    }
}
