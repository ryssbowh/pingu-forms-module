<?php

namespace Pingu\Forms\Support\Fields;

class MediaUpload extends MediaField
{
	public function extraValidationRules()
	{
		$extensions = \Media::getAvailableFileExtensions();
		return 'file_extension:'.implode(',', $extensions).'|max:'.config('media.maxFileSize');
	}

	/**
	 * @inehritDoc
	 */
	public function getDefaultView()
	{
		return 'media::fields.'.$this->getType();
	}
}