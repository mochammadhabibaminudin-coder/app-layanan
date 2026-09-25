<?php

namespace App\Filament\Resources\Complaints\Tables;

use App\Enums\ComplaintStatus;
use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\Complaint;
use App\Models\RehabilitationCase;
use App\Models\User;
use App\Models\WorkUnit;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('reported_at', 'desc')
            ->columns([
                TextColumn::make('complaint_number')
                    ->label('No. Laporan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('category.name')
                    ->label('Kategori Masalah')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reporter_name')
                    ->label('Pelapor')
                    ->searchable()
                    ->description(fn ($record) => $record->reporter_phone),
                TextColumn::make('village.name')
                    ->label('Lokasi Kejadian')
                    ->description(fn ($record) => $record->location_detail)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ComplaintStatus ? $state->label() : (ComplaintStatus::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => $state instanceof ComplaintStatus ? $state->color() : (ComplaintStatus::tryFrom((string) $state)?->color() ?? 'gray')),
                TextColumn::make('reported_at')
                    ->label('Waktu Laporan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('resolved_at')
                    ->label('Waktu Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('complaint_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->label('Status Pengaduan')
                    ->options(collect(ComplaintStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    Action::make('verify_complaint')
                        ->label('Verifikasi Awal Laporan')
                        ->icon(Heroicon::OutlinedCheckBadge)
                        ->color('info')
                        ->visible(fn ($record) => in_array($record->status, [ComplaintStatus::Received, ComplaintStatus::ClarificationRequested]))
                        ->schema([
                            Select::make('decision')
                                ->label('Hasil Verifikasi Awal')
                                ->options([
                                    ComplaintStatus::Verification->value => 'Terverifikasi (Lanjut Disposisi/Penanganan)',
                                    ComplaintStatus::ClarificationRequested->value => 'Minta Klarifikasi / Data Tambahan ke Pelapor',
                                ])
                                ->required(),
                            Textarea::make('verification_result')
                                ->label('Catatan Hasil Verifikasi')
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->verification_result = $data['verification_result'];
                            $record->transitionTo($data['decision'], $data['verification_result']);

                            Notification::make()
                                ->title('Verifikasi pengaduan berhasil disimpan')
                                ->success()
                                ->send();
                        }),

                    Action::make('dispatch_complaint')
                        ->label('Disposisikan Laporan')
                        ->icon(Heroicon::OutlinedArrowUturnRight)
                        ->color('purple')
                        ->visible(fn ($record) => in_array($record->status, [ComplaintStatus::Received, ComplaintStatus::Verification]))
                        ->schema([
                            Select::make('to_work_unit_id')
                                ->label('Disposisi ke Unit Kerja')
                                ->options(WorkUnit::pluck('name', 'id'))
                                ->required(),
                            Select::make('to_user_id')
                                ->label('Petugas Penerima (Opsional)')
                                ->options(User::pluck('name', 'id')),
                            Textarea::make('instructions')
                                ->label('Petunjuk / Arahan Penanganan')
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->dispositions()->create([
                                'from_user_id' => auth()->id(),
                                'to_work_unit_id' => $data['to_work_unit_id'],
                                'to_user_id' => $data['to_user_id'] ?? null,
                                'instructions' => $data['instructions'],
                                'disposed_at' => now(),
                            ]);

                            $record->officer_id = $data['to_user_id'] ?? $record->officer_id;
                            $record->transitionTo(ComplaintStatus::Dispatched, "Disposisi: {$data['instructions']}");

                            Notification::make()
                                ->title('Disposisi pengaduan berhasil dikirim')
                                ->success()
                                ->send();
                        }),

                    Action::make('start_handling')
                        ->label('Mulai Penanganan Lapangan')
                        ->icon(Heroicon::OutlinedPlay)
                        ->color('warning')
                        ->visible(fn ($record) => in_array($record->status, [ComplaintStatus::Dispatched, ComplaintStatus::Verification]))
                        ->action(function ($record): void {
                            $record->transitionTo(ComplaintStatus::InHandling, 'Petugas mulai melakukan penanganan di lapangan');

                            Notification::make()
                                ->title('Status penanganan diperbarui: Dalam Penanganan')
                                ->success()
                                ->send();
                        }),

                    Action::make('resolve_complaint')
                        ->label('Selesaikan Pengaduan')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->visible(fn ($record) => ! in_array($record->status, [ComplaintStatus::Resolved, ComplaintStatus::Duplicate, ComplaintStatus::Invalid]))
                        ->schema([
                            Textarea::make('action_taken')
                                ->label('Tindakan & Hasil Akhir Penanganan')
                                ->required()
                                ->placeholder('Jelaskan tindakan konkret yang telah dilakukan serta kondisi terakhir permasalahan sosial'),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->action_taken = $data['action_taken'];
                            $record->resolved_at = now();
                            $record->transitionTo(ComplaintStatus::Resolved, $data['action_taken']);

                            Notification::make()
                                ->title('Pengaduan Berhasil Diselesaikan')
                                ->success()
                                ->send();
                        }),

                    Action::make('create_rehab_case')
                        ->label('Teruskan Jadi Kasus Rehsos')
                        ->icon(Heroicon::OutlinedUserPlus)
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Buat Kasus Rehabilitasi Sosial Baru dari Pengaduan Ini')
                        ->schema([
                            TextInput::make('client_name')
                                ->label('Nama Klien / Korban')
                                ->required()
                                ->default(fn ($record) => $record->reporter_name),
                            Select::make('client_category_id')
                                ->label('Kategori Pemerlu Pelayanan (PPKS)')
                                ->options(ClientCategory::pluck('name', 'id'))
                                ->required(),
                            Select::make('gender')
                                ->label('Jenis Kelamin Klien')
                                ->options([
                                    'male' => 'Laki-laki',
                                    'female' => 'Perempuan',
                                ])
                                ->default('male')
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            $client = Client::create([
                                'name' => $data['client_name'],
                                'client_category_id' => $data['client_category_id'],
                                'gender' => $data['gender'],
                                'address' => $record->location_detail,
                                'village_id' => $record->village_id,
                                'phone' => $record->reporter_phone,
                            ]);

                            $rehabCase = RehabilitationCase::create([
                                'client_id' => $client->id,
                                'complaint_id' => $record->id,
                                'officer_id' => auth()->id(),
                                'handling_type' => HandlingType::Direct,
                                'status' => RehabilitationCaseStatus::Received,
                                'received_at' => now(),
                            ]);

                            $record->action_taken = "Diteruskan menjadi Kasus Rehabilitasi Sosial No. {$rehabCase->case_number}";
                            $record->transitionTo(ComplaintStatus::InHandling, "Diteruskan ke Rehsos (Kasus No. {$rehabCase->case_number})");

                            Notification::make()
                                ->title('Kasus Rehabilitasi Sosial Berhasil Dibuat')
                                ->body("No. Kasus: {$rehabCase->case_number}")
                                ->success()
                                ->send();
                        }),

                    Action::make('mark_duplicate')
                        ->label('Tandai Duplikat')
                        ->icon(Heroicon::OutlinedDocumentDuplicate)
                        ->color('gray')
                        ->visible(fn ($record) => ! in_array($record->status, [ComplaintStatus::Resolved, ComplaintStatus::Duplicate]))
                        ->schema([
                            Select::make('duplicate_of_id')
                                ->label('Laporan Induk Asal')
                                ->options(function ($record) {
                                    return Complaint::where('id', '!=', $record->id)
                                        ->whereNull('duplicate_of_id')
                                        ->pluck('complaint_number', 'id');
                                })
                                ->required(),
                            Textarea::make('reason')
                                ->label('Keterangan Duplikasi'),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->duplicate_of_id = $data['duplicate_of_id'];
                            $record->transitionTo(ComplaintStatus::Duplicate, "Duplikat dari laporan #{$data['duplicate_of_id']}. {$data['reason']}");

                            Notification::make()
                                ->title('Laporan ditandai sebagai duplikat')
                                ->warning()
                                ->send();
                        }),

                    Action::make('mark_invalid')
                        ->label('Tolak / Laporan Tidak Valid')
                        ->icon(Heroicon::OutlinedXCircle)
                        ->color('danger')
                        ->visible(fn ($record) => ! in_array($record->status, [ComplaintStatus::Resolved, ComplaintStatus::Invalid]))
                        ->schema([
                            Textarea::make('reason')
                                ->label('Alasan Laporan Dinyatakan Tidak Valid / Hoaks')
                                ->required(),
                        ])
                        ->action(function ($record, array $data): void {
                            $record->verification_result = $data['reason'];
                            $record->transitionTo(ComplaintStatus::Invalid, $data['reason']);

                            Notification::make()
                                ->title('Laporan pengaduan dinyatakan tidak valid')
                                ->danger()
                                ->send();
                        }),
                ])->label('Aksi Pengaduan'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
