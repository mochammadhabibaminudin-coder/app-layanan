<?php

namespace App\Filament\Widgets;

use App\Enums\ServiceRequestStatus;
use App\Models\ServiceRequest;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class EmergencyPbiWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Perhatian Khusus: Pengajuan Darurat & Menunggu Persetujuan')
            ->description('Daftar pengajuan PBI-JK darurat medis atau permohonan yang membutuhkan tindakan segera.')
            ->query(
                ServiceRequest::query()
                    ->with(['serviceType', 'village.district', 'officer'])
                    ->where(function ($query) {
                        $query->where('is_priority', true)
                            ->whereNotIn('status', [ServiceRequestStatus::Completed, ServiceRequestStatus::Rejected])
                            ->orWhere('status', ServiceRequestStatus::AwaitingApproval);
                    })
                    ->latest('submitted_at')
            )
            ->columns([
                TextColumn::make('request_number')
                    ->label('Nomor Tiket')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('serviceType.name')
                    ->label('Jenis Layanan')
                    ->badge()
                    ->color(fn (string $state): string => str_contains($state, 'PBI') ? 'danger' : 'info'),

                TextColumn::make('applicant_name')
                    ->label('Pemohon')
                    ->description(fn (ServiceRequest $record): string => 'NIK: '.$record->applicant_nik),

                TextColumn::make('village.name')
                    ->label('Wilayah')
                    ->description(fn (ServiceRequest $record): string => $record->village?->district?->name ?? '-'),

                IconColumn::make('is_priority')
                    ->label('Darurat')
                    ->boolean()
                    ->trueIcon('heroicon-s-exclamation-triangle')
                    ->trueColor('danger')
                    ->falseIcon('heroicon-o-minus')
                    ->falseColor('gray'),

                TextColumn::make('status')
                    ->label('Status Terkini')
                    ->badge(),

                TextColumn::make('submitted_at')
                    ->label('Diajukan')
                    ->since()
                    ->sortable(),
            ]);
    }
}
