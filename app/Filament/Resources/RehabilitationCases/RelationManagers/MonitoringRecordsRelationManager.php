<?php

namespace App\Filament\Resources\RehabilitationCases\RelationManagers;

use App\Models\Referral;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MonitoringRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'monitoringRecords';

    protected static ?string $title = 'Catatan Monitoring & Perkembangan';

    protected static ?string $modelLabel = 'Catatan Monitoring';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->components([
                    DatePicker::make('monitoring_date')
                        ->label('Tanggal Monitoring')
                        ->default(now())
                        ->required(),
                    Select::make('officer_id')
                        ->label('Petugas Monitoring')
                        ->options(User::pluck('name', 'id'))
                        ->default(auth()->id())
                        ->required()
                        ->searchable(),
                    Select::make('referral_id')
                        ->label('Rujukan Terkait (Opsional)')
                        ->options(function () {
                            $case = $this->getOwnerRecord();

                            return Referral::where('rehabilitation_case_id', $case?->id)
                                ->pluck('referral_number', 'id');
                        })
                        ->searchable(),
                ]),
                Textarea::make('progress')
                    ->label('Perkembangan Klien Saat Ini')
                    ->placeholder('Catat kemajuan kondisi fisik, psikososial, atau kemandirian klien')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('result_notes')
                    ->label('Catatan Hasil Monitoring / Rencana Tindak Lanjut')
                    ->placeholder('Rekomendasi tindak lanjut monitoring berikutnya')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('progress')
            ->defaultSort('monitoring_date', 'desc')
            ->columns([
                TextColumn::make('monitoring_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->icon(Heroicon::OutlinedCalendar)
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->icon(Heroicon::OutlinedUser),
                TextColumn::make('referral.referral_number')
                    ->label('No. Rujukan')
                    ->placeholder('(Monitoring Mandiri)')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('progress')
                    ->label('Perkembangan Klien')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('result_notes')
                    ->label('Catatan Tindak Lanjut')
                    ->limit(40)
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Catatan Monitoring'),
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
