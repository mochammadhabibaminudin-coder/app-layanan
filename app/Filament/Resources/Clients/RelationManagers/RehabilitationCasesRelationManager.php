<?php

namespace App\Filament\Resources\Clients\RelationManagers;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RehabilitationCasesRelationManager extends RelationManager
{
    protected static string $relationship = 'rehabilitationCases';

    protected static ?string $title = 'Riwayat Kasus Rehabilitasi Sosial';

    protected static ?string $modelLabel = 'Kasus Rehsos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->components([
                    TextInput::make('case_number')
                        ->label('Nomor Kasus')
                        ->placeholder('(Otomatis dibuat sistem)')
                        ->disabled()
                        ->dehydrated(fn ($state) => filled($state)),
                    Select::make('handling_type')
                        ->label('Jenis Penanganan')
                        ->options(HandlingType::class)
                        ->default(HandlingType::Direct)
                        ->required(),
                    Select::make('status')
                        ->label('Status Kasus')
                        ->options(RehabilitationCaseStatus::class)
                        ->default(RehabilitationCaseStatus::Received)
                        ->required(),
                ]),
                DateTimePicker::make('received_at')
                    ->label('Waktu Diterima')
                    ->default(now())
                    ->required(),
                Textarea::make('handling_result')
                    ->label('Hasil Akhir Pelayanan / Terminasi')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('case_number')
            ->defaultSort('received_at', 'desc')
            ->columns([
                TextColumn::make('case_number')
                    ->label('No. Kasus')
                    ->weight('bold')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('handling_type')
                    ->label('Penanganan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof HandlingType ? $state->label() : (HandlingType::tryFrom((string) $state)?->label() ?? $state)),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof RehabilitationCaseStatus ? $state->label() : (RehabilitationCaseStatus::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => $state instanceof RehabilitationCaseStatus ? $state->color() : (RehabilitationCaseStatus::tryFrom((string) $state)?->color() ?? 'gray')),
                TextColumn::make('received_at')
                    ->label('Tgl Diterima')
                    ->dateTime('d M Y')
                    ->icon(Heroicon::OutlinedCalendar)
                    ->sortable(),
                TextColumn::make('closed_at')
                    ->label('Tgl Selesai')
                    ->dateTime('d M Y')
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buka Kasus Baru untuk Klien Ini'),
            ])
            ->recordActions([
                ViewAction::make(),
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
