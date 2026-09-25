<?php

namespace App\Filament\Resources\PbiReactivations\Schemas;

use App\Enums\MinistryDecision;
use App\Enums\PbiReactivationReason;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PbiReactivationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Peserta BPJS')
                    ->components([
                        Grid::make(3)->components([
                            TextEntry::make('participant_name')
                                ->label('Nama Peserta BPJS')
                                ->weight('bold'),
                            TextEntry::make('participant_nik')
                                ->label('NIK Peserta')
                                ->copyable(),
                            TextEntry::make('bpjs_card_number')
                                ->label('Nomor Kartu BPJS / KIS')
                                ->copyable()
                                ->icon(Heroicon::OutlinedCreditCard),
                        ]),
                        Grid::make(3)->components([
                            TextEntry::make('serviceRequest.request_number')
                                ->label('Nomor Tiket Layanan')
                                ->copyable()
                                ->icon(Heroicon::OutlinedTicket),
                            TextEntry::make('deactivated_date')
                                ->label('Tanggal Nonaktif BPJS')
                                ->date('d F Y')
                                ->placeholder('-'),
                            TextEntry::make('reason')
                                ->label('Alasan Reaktivasi')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof PbiReactivationReason ? $state->label() : (PbiReactivationReason::tryFrom((string) $state)?->label() ?? $state))
                                ->color(fn ($state) => $state === PbiReactivationReason::Emergency ? 'danger' : 'info'),
                        ]),
                    ]),

                Section::make('Keterangan Fasilitas Kesehatan & Verifikasi')
                    ->components([
                        Grid::make(3)->components([
                            TextEntry::make('health_facility_name')
                                ->label('Faskes Perujuk / Rumah Sakit')
                                ->placeholder('(Tidak ada)'),
                            TextEntry::make('health_letter_number')
                                ->label('No. Surat Keterangan Medis')
                                ->placeholder('(Tidak ada)'),
                            TextEntry::make('decile')
                                ->label('Peringkat Desil')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state ? "Desil {$state}" : '(Belum diverifikasi)')
                                ->color('success'),
                        ]),
                        TextEntry::make('eligibility_notes')
                            ->label('Catatan Hasil Verifikasi Kelayakan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Rekomendasi Dinas & Proses Kementerian Sosial')
                    ->components([
                        Grid::make(3)->components([
                            TextEntry::make('recommendation_number')
                                ->label('Nomor Surat Rekomendasi')
                                ->placeholder('(Belum Terbit)')
                                ->copyable()
                                ->weight('bold')
                                ->icon(Heroicon::OutlinedDocumentText),
                            TextEntry::make('recommendation_issued_at')
                                ->label('Tanggal Rekomendasi')
                                ->dateTime('d F Y, H:i')
                                ->placeholder('-'),
                            TextEntry::make('signer.name')
                                ->label('Penandatangan')
                                ->placeholder('-'),
                        ]),
                        Grid::make(3)->components([
                            TextEntry::make('proposed_to_ministry_at')
                                ->label('Waktu Input ke SIKS-NG')
                                ->dateTime('d F Y, H:i')
                                ->placeholder('(Belum Diusulkan)'),
                            TextEntry::make('ministry_decision')
                                ->label('Keputusan Kemensos')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof MinistryDecision ? $state->label() : (MinistryDecision::tryFrom((string) $state)?->label() ?? $state))
                                ->color(fn ($state) => $state instanceof MinistryDecision ? $state->color() : (MinistryDecision::tryFrom((string) $state)?->color() ?? 'gray')),
                            TextEntry::make('reactivated_date')
                                ->label('Tanggal Aktif di BPJS')
                                ->date('d F Y')
                                ->placeholder('(Belum Aktif)'),
                        ]),
                    ]),
            ]);
    }
}
