<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

/**
 * Class UserPresenter.
 *
 * @package namespace App\Presenters;
 */
abstract class UserPresenter extends Presenter
{

    use UserPresenterTrait;

    public function isAdminUser(): bool {
        return $this->role->name === 'admin';
    }
}
