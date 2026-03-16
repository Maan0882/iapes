<?php

namespace App\Filament\Resources\InterviewManagement;

use App\Filament\Resources\InterviewManagement\InterviewBatchResource\Pages;
use App\Filament\Resources\InterviewManagement\InterviewBatchResource\RelationManagers;
use App\Models\InterviewManagement\InterviewBatch;

use Carbon\Carbon;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, TextArea, FileUpload, Select, DatePicker, TimePicker, Section, Grid};
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\{Action, BulkAction};
use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;


class InterviewBatchResource extends Resource
{
    protected static ?string $model = InterviewBatch::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Interview Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\TextInput::make('interview_batch_code')
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\TextInput::make('interview_batch_name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('interview_date')
                    ->required()
                    ->native(false)
                    ->minDate(now()->startOfDay())
                    ->displayFormat('d M, Y'),

                Forms\Components\TimePicker::make('start_time')
                    ->required(),

                Forms\Components\TimePicker::make('end_time')
                    ->required(),

                Forms\Components\TextInput::make('interview_location')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('batch_size')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('capacity_status')
                    ->options([
                        'open' => 'Open',
                        'full' => 'Full',
                    ])
                    ->default('open')
                    ->required(),

                Forms\Components\Select::make('workflow_status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('scheduled')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('3s') // ⬅ auto refresh
            ->columns([
                Tables\Columns\TextColumn::make('interview_batch_code')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('interview_batch_name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('interview_date')
                    ->date(),

                Tables\Columns\TextColumn::make('start_time'),

                Tables\Columns\TextColumn::make('batch_size'),

                Tables\Columns\BadgeColumn::make('capacity_status')
                    ->colors([
                        'success' => 'open',
                        'danger' => 'full',
                    ]),

                Tables\Columns\BadgeColumn::make('workflow_status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListInterviewBatches::route('/'),
            'create' => Pages\CreateInterviewBatch::route('/create'),
            'edit' => Pages\EditInterviewBatch::route('/{record}/edit'),
        ];
    }
}
