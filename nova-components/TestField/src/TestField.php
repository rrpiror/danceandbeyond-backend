<?php

namespace Dab\TestField;

use App\Models\FulfillmentOption;
use Laravel\Nova\Fields\Field;

class TestField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'test-field';

	public function __construct($name, $attribute = null, $resolveCallback = null)
	{
		parent::__construct($name, $attribute, $resolveCallback);

		$this->withMeta([
			'options' => FulfillmentOption::all()->pluck('name', 'id')
		]);
	}
}
