<?php 
namespace Pingu\Forms\Traits\Models;

use Collective\Html\Eloquent\FormAccessible;
use Pingu\Forms\Support\BaseForms;
use Pingu\Forms\Support\FormRepository;

trait HasForms {

    use FormAccessible;

    /**
     * Get the form instance
     * 
     * @return FormRepository
     */
    public function forms(): FormRepository
    {
        return new BaseForms($this);
    }

}