<?php

namespace App\Actions\Users;

use App\Domain\Users\Repositories\UserRepository;

class DeleteUser
{
    public function __construct(private UserRepository $repository) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
