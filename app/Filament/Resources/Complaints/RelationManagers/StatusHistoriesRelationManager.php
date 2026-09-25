<?php

namespace App\Filament\Resources\Complaints\RelationManagers;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatusHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'statusHistories';

    protected static ?string $title = 'Riwayat Status Pengaduan';

    protected static ?string $modelLabel = 'Riwayat Status';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('from_status')
                    ->label('Status Sebelumnya')
                    ->disabled(),
                TextInput::make('to_status')
                    ->label('Status Baru')
                    ->required(),
                Select::make('user_id')
                    ->label('Petugas')
                    ->relationship('user', 'name')
                    ->disabled(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('to_status')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i:s')
                    ->icon(Heroicon::OutlinedClock)
                    ->sortable(),
                TextColumn::make('from_status')
                    ->label('Dari')
                    ->badge()
                    ->color('gray')
                    ->placeholder('(Baru Diterima)'),
                TextColumn::make('to_status')
                    ->label('Menjadi')
                    ->badge()
                    ->color('primary')
                    ->weight('bold'),
                TextColumn::make('notes')
                    ->label('Catatan Perkembangan')
                    ->placeholder('-')
                    ->wrap(),
                TextColumn::make('user.name')
                    ->label('Oleh Petugas')
                    ->icon(Heroicon::OutlinedUser)
                    ->placeholder('Sistem / Pelapor'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
