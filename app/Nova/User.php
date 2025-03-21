<?php

namespace App\Nova;

use App\Nova\UserSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use Laravel\Nova\Fields\BelongsToMany;

class User extends Resource
{
	use PasswordValidationRules;

	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\User>
	 */
	public static $model = \App\Models\User::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'name';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id',
		'name',
		'email',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel|\Laravel\Nova\ResourceTool|\Illuminate\Http\Resources\MergeValue>
	 */
	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),

			Boolean::make('Status')->trueValue('active')->falseValue('blocked')->default('active'),

			Image::make('Profile Picture')
				->disk('public')
				->thumbnail(function ($value, $model) {
					return $model instanceof \App\Models\User
						? $model->getFirstMediaUrl('profile_picture')
						: null;
				})
				->preview(function ($value, $model) {
					return $model instanceof \App\Models\User
						? $model->getFirstMediaUrl('profile_picture')
						: null;
				})
				->store(function (Request $request, $model) {
					if ($request->hasFile('profile_picture')) {
						$model->clearMediaCollection('profile_picture');
						$model->addMediaFromRequest('profile_picture')
							->toMediaCollection('profile_picture', 'public');
						return true;
					}
					return false;
				})
				->deletable(false),

			Gravatar::make()->maxWidth(50),

			Select::make('Type')->options([
				'individual' => 'Individual',
				'school' => 'Dance School'
			])->rules('required'),

			Text::make('Name')
				->sortable()
				->rules('required', 'max:255'),

			Text::make('Email')
				->sortable()
				->rules('required', 'email', 'max:254')
				->creationRules('unique:users,email')
				->updateRules('unique:users,email,{{resourceId}}'),

			Password::make('Password')
				->onlyOnForms()
				->creationRules($this->passwordRules())
				->updateRules($this->optionalPasswordRules()),

			HasOne::make('Dance School Details', 'school', UserSchool::class)
				->hideFromIndex()
				->canSee(function ($request) {
					// For detail view
					if ($request->resourceId) {
						$model = \App\Models\User::find($request->resourceId);
						return $model && $model->type === 'school';
					}

					// For create/edit forms
					if ($request->isCreateOrAttachRequest() || $request->isUpdateOrUpdateAttachedRequest()) {
						return false;
					}

					return false;
				}),

			BelongsToMany::make('Addresses', 'address', Address::class),
		];
	}

	/**
	 * Get the cards available for the request.
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

	public static function fillForUpdate(NovaRequest $request, $model): array
	{
		$fields = parent::fillForUpdate($request, $model);

		// Store data in user_schools if user type is 'school'
		if ($request->input('type') === 'school') {
			UserSchool::updateOrCreate(
				['user_id' => $model->id], // Find existing record
				[
					'website' => $request->input('website'),
					'description' => $request->input('description'),
				]
			);
		}

		return $fields;
	}

	public static function fillForCreation(NovaRequest $request, $model): array
	{
		$fields = parent::fillForCreation($request, $model);

		// Store data in user_schools if user type is 'school'
		if ($request->input('type') === 'school') {
			UserSchool::updateOrCreate(
				['user_id' => $model->id], // Find existing record
				[
					'website' => $request->input('website'),
					'description' => $request->input('description'),
				]
			);
		}

		return $fields;
	}
}
