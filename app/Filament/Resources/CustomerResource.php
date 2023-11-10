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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer Information')
                    ->description('Enter the customer\'s information.')
                    ->aside()
                    ->collapsible()
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->placeholder(__('Select User'))
                            ->required(),
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
                //
            ])
            ->filters([
                //
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
        ];
    }
}
