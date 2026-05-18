<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Section;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'System Settings';
    protected static ?int $navigationSort = 100;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Time'),
                TextColumn::make('user.name')
                    ->label('User / Intern')
                    ->getStateUsing(function (ActivityLog $record) {
                        if ($record->user) return $record->user->name;
                        
                        $subject = $record->subject;
                        if ($subject instanceof \App\Models\InternManagement\Intern) return $subject->name;
                        if ($subject instanceof \App\Models\InterviewManagement\Application) return $subject->name;
                        
                        return 'System';
                    })
                    ->description(function (ActivityLog $record) {
                        if ($record->user) return $record->user->email;
                        
                        $subject = $record->subject;
                        if ($subject instanceof \App\Models\InternManagement\Intern) return "Intern: {$subject->username}";
                        if ($subject instanceof \App\Models\InterviewManagement\Application && $subject->intern) {
                            return "Intern: {$subject->intern->username}";
                        }
                        
                        return null;
                    })
                    ->searchable(['name', 'email']),
                TextColumn::make('action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('subject_type')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->label('Model'),
                TextColumn::make('record_identifier')
                    ->label('Activity')
                    ->getStateUsing(function (ActivityLog $record) {
                        $subject = $record->subject;
                        
                        // If the record was deleted, we use the name we captured at the time of deletion
                        if (!$subject) {
                            return $record->properties['record_name'] ?? "ID: {$record->subject_id}";
                        }
                        
                        $nameCols = ['name', 'title', 'username', 'event_title', 'interview_batch_name', 'intern_batch_name'];
                        $codeCols = ['application_code', 'interview_batch_code', 'intern_code'];
                        
                        $name = null;
                        foreach ($nameCols as $col) { if (isset($subject->{$col})) { $name = (string) $subject->{$col}; break; } }
                        
                        $code = null;
                        foreach ($codeCols as $col) { if (isset($subject->{$col})) { $code = (string) $subject->{$col}; break; } }
                        
                        if ($name && $code) $identifier = "{$name} ({$code})";
                        elseif ($name) $identifier = $name;
                        elseif ($code) $identifier = $code;
                        else $identifier = "ID: {$record->subject_id}";

                        return $identifier;
                    })
                    ->description(fn (ActivityLog $record) => $record->description)
                    ->searchable(['description']),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Activity Details')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->label('Timestamp'),
                        TextEntry::make('causer_name')
                            ->label('User / Intern')
                            ->getStateUsing(function (ActivityLog $record) {
                                if ($record->user) return (string) $record->user->name;
                                $subject = $record->subject;
                                if ($subject instanceof \App\Models\InternManagement\Intern) return (string) $subject->name;
                                if ($subject instanceof \App\Models\InterviewManagement\Application) return (string) $subject->name;
                                return 'System';
                            }),
                        TextEntry::make('causer_identifier')
                            ->label('Email / Username')
                            ->getStateUsing(function (ActivityLog $record) {
                                if ($record->user) return (string) $record->user->email;
                                $subject = $record->subject;
                                if ($subject instanceof \App\Models\InternManagement\Intern) return (string) $subject->username;
                                if ($subject instanceof \App\Models\InterviewManagement\Application) return (string) $subject->intern?->username;
                                return null;
                            }),
                        TextEntry::make('action')
                            ->badge(),
                        TextEntry::make('record_identifier')
                            ->label('Activity')
                            ->getStateUsing(function (ActivityLog $record) {
                                $subject = $record->subject;
                                
                                if (!$subject) {
                                    $identifier = $record->properties['record_name'] ?? "ID: {$record->subject_id}";
                                    return "{$identifier} ({$record->description})";
                                }
                                
                                $nameCols = ['name', 'title', 'username', 'event_title', 'interview_batch_name', 'intern_batch_name'];
                                $codeCols = ['application_code', 'interview_batch_code', 'intern_code'];
                                
                                $name = null;
                                foreach ($nameCols as $col) { if (isset($subject->{$col})) { $name = (string) $subject->{$col}; break; } }
                                
                                $code = null;
                                foreach ($codeCols as $col) { if (isset($subject->{$col})) { $code = (string) $subject->{$col}; break; } }
                                
                                if ($name && $code) $identifier = "{$name} ({$code})";
                                elseif ($name) $identifier = $name;
                                elseif ($code) $identifier = $code;
                                else $identifier = "ID: {$record->subject_id}";

                                return "{$identifier} ({$record->description})";
                            }),
                        TextEntry::make('subject_type')
                            ->label('Model Type')
                            ->formatStateUsing(fn ($state) => class_basename($state)),
                        TextEntry::make('subject_id')
                            ->label('Model ID'),
                    ])->columns(3),
                Section::make('Activity Summary')
                    ->schema([
                        TextEntry::make('properties')
                            ->label('')
                            ->getStateUsing(function (ActivityLog $record) {
                                $properties = $record->properties;
                                if (!$properties || !is_array($properties)) return null;

                                $output = [];
                                $attributes = $properties['attributes'] ?? [];
                                $old = $properties['old'] ?? [];

                                if ($record->action === 'updated') {
                                    foreach ($attributes as $key => $value) {
                                        if (in_array($key, ['updated_at', 'created_at', 'id'])) continue;
                                        $oldValue = $old[$key] ?? 'none';
                                        $newValue = $value ?? 'none';
                                        if (is_bool($oldValue)) $oldValue = $oldValue ? 'True' : 'False';
                                        if (is_bool($newValue)) $newValue = $newValue ? 'True' : 'False';
                                        $fieldName = ucfirst(str_replace('_', ' ', $key));
                                        $output[] = "Changed **{$fieldName}** from `{$oldValue}` to `{$newValue}`";
                                    }
                                } elseif ($record->action === 'created') {
                                    foreach ($attributes as $key => $value) {
                                        if (empty($value) || in_array($key, ['updated_at', 'created_at', 'id'])) continue;
                                        $fieldName = ucfirst(str_replace('_', ' ', $key));
                                        $output[] = "**{$fieldName}**: {$value}";
                                    }
                                }

                                return count($output) > 0 ? implode("\n", $output) : "Action performed successfully.";
                            })
                            ->markdown()
                            ->extraAttributes([
                                'class' => 'p-4 bg-primary-50 border-l-4 border-primary-500 rounded-r-lg text-gray-800 leading-relaxed shadow-sm italic',
                            ]),
                    ])
                    ->visible(fn (ActivityLog $record) => !empty($record->properties)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
