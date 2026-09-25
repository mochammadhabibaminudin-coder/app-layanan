<?php

namespace App\Filament\Resources\Complaints\RelationManagers;

use App\Models\User;
use App\Models\WorkUnit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DispositionsRelationManager extends RelationManager
{
    protected static string $relationship = 'dispositions';

    protected static ?string $title = 'Riwayat Disposisi Penanganan';

    protected static ?string $modelLabel = 'Disposisi';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('from_user_id')
                    ->label('Disposisi Dari')
                    ->options(User::pluck('name', 'id'))
                    ->default(auth()->id())
                    ->required()
                    ->searchable(),
                Select::make('to_work_unit_id')
                    ->label('Tujuan Unit Kerja')
                    ->options(WorkUnit::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('to_user_id')
                    ->label('Petugas Penerima (Opsional)')
                    ->options(User::pluck('name', 'id'))
                    ->searchable(),
                DateTimePicker::make('disposed_at')
                    ->label('Waktu Disposisi')
                    ->default(now())
                    ->required(),
                Textarea::make('instructions')
                    ->label('Petunjuk / Arahan Penanganan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('instructions')
            ->defaultSort('disposed_at', 'desc')
            ->columns([
                TextColumn::make('disposed_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->icon(Heroicon::OutlinedClock)
                    ->sortable(),
                TextColumn::make('fromUser.name')
                    ->label('Dari')
                    ->icon(Heroicon::OutlinedUser),
                TextColumn::make('toWorkUnit.name')
                    ->label('Unit Kerja Tujuan')
                    ->badge()
                    ->color('info'),
                TextColumn::make('toUser.name')
                    ->label('Penerima')
                    ->placeholder('-'),
                TextColumn::make('instructions')
                    ->label('Arahan / Instruksi')
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Disposisi Baru'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
