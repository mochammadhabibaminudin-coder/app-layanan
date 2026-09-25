<?php

namespace App\Filament\Resources\DtsenCertificates\RelationManagers;

use App\Enums\ApprovalDecision;
use App\Enums\ApprovalStep;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApprovalsRelationManager extends RelationManager
{
    protected static string $relationship = 'approvals';

    protected static ?string $title = 'Riwayat Paraf & Persetujuan Berjenjang';

    protected static ?string $modelLabel = 'Persetujuan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('step')
                    ->label('Jenjang Persetujuan')
                    ->options(ApprovalStep::class)
                    ->required(),
                Select::make('approver_id')
                    ->label('Pejabat Penandatangan')
                    ->options(User::pluck('name', 'id'))
                    ->default(auth()->id())
                    ->required()
                    ->searchable(),
                Select::make('decision')
                    ->label('Keputusan')
                    ->options(ApprovalDecision::class)
                    ->default(ApprovalDecision::Pending)
                    ->required(),
                DateTimePicker::make('decided_at')
                    ->label('Waktu Keputusan')
                    ->default(now()),
                Textarea::make('notes')
                    ->label('Catatan Pejabat')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('step')
            ->defaultSort('step', 'asc')
            ->columns([
                TextColumn::make('step')
                    ->label('Tahap / Jenjang')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ApprovalStep ? $state->label() : (ApprovalStep::tryFrom((int) $state)?->label() ?? $state))
                    ->color('info'),
                TextColumn::make('approver.name')
                    ->label('Pejabat')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->weight('bold'),
                TextColumn::make('decision')
                    ->label('Keputusan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ApprovalDecision ? $state->label() : (ApprovalDecision::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => $state instanceof ApprovalDecision ? $state->color() : (ApprovalDecision::tryFrom((string) $state)?->color() ?? 'gray')),
                TextColumn::make('decided_at')
                    ->label('Waktu Putusan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Menunggu putusan'),
                TextColumn::make('notes')
                    ->label('Catatan Pejabat')
                    ->placeholder('-')
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Antrean Persetujuan'),
            ])
            ->recordActions([
                Action::make('decide')
                    ->label('Beri Putusan')
                    ->icon(Heroicon::OutlinedCheckBadge)
                    ->color('success')
                    ->schema([
                        Select::make('decision')
                            ->label('Keputusan Pejabat')
                            ->options([
                                ApprovalDecision::Approved->value => 'Setujui (Disetujui)',
                                ApprovalDecision::Returned->value => 'Kembalikan (Perlu Perbaikan)',
                            ])
                            ->required(),
                        Textarea::make('notes')
                            ->label('Catatan / Arahan')
                            ->placeholder('Catatan atau petunjuk perbaikan jika dikembalikan'),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update([
                            'decision' => $data['decision'],
                            'notes' => $data['notes'] ?? null,
                            'decided_at' => now(),
                            'approver_id' => auth()->id() ?? $record->approver_id,
                        ]);

                        Notification::make()
                            ->title('Keputusan persetujuan berhasil disimpan')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
