<?php

namespace App\Filament\Resources\RehabilitationCases;

use App\Filament\Resources\RehabilitationCases\Pages\CreateRehabilitationCase;
use App\Filament\Resources\RehabilitationCases\Pages\EditRehabilitationCase;
use App\Filament\Resources\RehabilitationCases\Pages\ListRehabilitationCases;
use App\Filament\Resources\RehabilitationCases\Pages\ViewRehabilitationCase;
use App\Filament\Resources\RehabilitationCases\Schemas\RehabilitationCaseForm;
use App\Filament\Resources\RehabilitationCases\Schemas\RehabilitationCaseInfolist;
use App\Filament\Resources\RehabilitationCases\Tables\RehabilitationCasesTable;
use App\Models\RehabilitationCase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RehabilitationCaseResource extends Resource
{
    protected static ?string $model = RehabilitationCase::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan Prioritas';

    protected static ?string $navigationLabel = 'Kasus Rehabilitasi Sosial';

    protected static ?string $modelLabel = 'Kasus Rehsos';

    protected static ?string $pluralModelLabel = 'Kasus Rehsos';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return RehabilitationCaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RehabilitationCaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RehabilitationCasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AssessmentsRelationManager::class,
            RelationManagers\ReferralsRelationManager::class,
            RelationManagers\MonitoringRecordsRelationManager::class,
            RelationManagers\StatusHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRehabilitationCases::route('/'),
            'create' => CreateRehabilitationCase::route('/create'),
            'view' => ViewRehabilitationCase::route('/{record}'),
            'edit' => EditRehabilitationCase::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
