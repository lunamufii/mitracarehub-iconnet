<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MitraCareHubResource\Pages;
use App\Filament\Resources\MitraCareHubResource\RelationManagers;
use App\Models\MitraCareHub;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MitraCareHubResource extends Resource
{
    protected static ?string $model = MitraCareHub::class;

    protected static ?string $navigationGroup = 'Laporan Keluhan';

    protected static ?string $navigationLabel = 'Daftar Keluhan';


    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Radio::make('status')
                    ->boolean()
                    ->inline()
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('report_number')->label(__('Nomor Laporan'))->sortable()->searchable(),
               TextColumn::make('name')->label(__('Nama Pelapor'))->sortable()->searchable(),
               TextColumn::make('mitra')->label(__('Nama Mitra IKR'))->sortable()->searchable(),
               TextColumn::make('description')->label(__('Deskripsi Laporan Keluhan'))->words(6),
               ImageColumn::make('file'),
               IconColumn::make('status')
               ->boolean()->sortable()->searchable(),
               TextColumn::make('created_at')
                ->dateTime('d M Y'),
               TextColumn::make('updated_at')
                ->dateTime('d M Y')
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options([
                            '1' => 'Selesai',
                            '0' => 'Belum Selesai',
                    ]),
                    Filter::make('created_at')
                        ->form([
                            DatePicker::make('created_from')->label(__('Date')),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['created_from'],
                                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '=', $date),
                                );
                        })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                //...
                ])
                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                    ]),
                ]);
    }
    public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            Section::make('Data Keluhan')
            ->schema([
                TextEntry::make('report_number')->label(__('Nomor Laporan')),
                TextEntry::make('name')->label(__('Nama Pelapor')),
                TextEntry::make('mitra')->label(__('Nama Mitra IKR')),
                TextEntry::make('description')->label(__('Deskripsi Laporan Keluhan')),
                ImageEntry::make('file'),
                IconEntry::make('status')->boolean(),
                TextEntry::make('created_at'),
                TextEntry::make('updated_at'),
            ])->columns(2)
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
            'index' => Pages\ListMitraCareHubs::route('/'),
            // 'create' => Pages\CreateMitraCareHub::route('/create'),
            // 'edit' => Pages\EditMitraCareHub::route('/{record}/edit'),
        ];
    }
}
