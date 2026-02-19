<?php

namespace App\Actions\Users;

use App\Domain\Users\Repositories\UserRepository;

class ChangeUserStatusAction
{
    public function __construct(private UserRepository $repository) {}

    public function execute(int $id, bool $isBlocked)
    {
        return $this->repository->setIsBlocked($id, $isBlocked);
    }
}
