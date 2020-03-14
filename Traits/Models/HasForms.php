<?php 
namespace Pingu\Forms\Traits\Models;

use Collective\Html\Eloquent\FormAccessible;
use Pingu\Forms\Contracts\FormRepositoryContract;
use Pingu\Forms\Support\BaseForms;

trait HasForms
{
    use FormAccessible;

    /**
     * Get the form instance
     * 
     * @return FormRepository
     */
    public function forms(): FormRepositoryContract
    {
        return new BaseForms($this);
    }

}