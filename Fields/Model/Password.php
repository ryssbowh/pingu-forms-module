<?php
namespace Pingu\Forms\Fields\Model;

use Hash;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Contracts\ModelFieldContract;
use Pingu\Forms\Fields\Base\Password as Base;
use Pingu\Forms\Traits\ModelField;

class Password extends Base implements ModelFieldContract
{
	use ModelField;

	/**
	 * @inheritDoc
	 */
	public static function setModelValue(BaseModel $model, string $field, $value)
	{
		$model->$field = Hash::make($value);
	}
}