<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class UserSchool extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\UserSchool>
     */
    public static $model = \App\Models\UserSchool::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Select::make('User', 'user_id')
                ->options(\App\Models\User::all()->pluck('name', 'id'))
                ->rules('required')
                ->displayUsingLabels()
                ->default(function ($request) {
                    // If coming from a parent resource (User), prefill with parent ID
                    if ($request->viaResource === 'users' && $request->viaResourceId) {
                        return $request->viaResourceId;
                    }
                    return null;
                })
                ->readonly(function ($request) {
                    // Make it readonly if coming from a parent resource
                    return $request->viaResource === 'users' && $request->viaResourceId;
                }),
            Text::make('School Name', 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Website', 'website')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description', 'description'),
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
