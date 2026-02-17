<?php

namespace App\Actions\Users;

use App\Domain\Users\Repositories\UserRepository;
use App\DTO\User\UserData;
use App\Models\User;

class EditUser
{
    public function __construct(private UserRepository $repository) {}

    public function execute(int $id, UserData $data): User
    {
        return $this->repository->edit($id, $data);
    }
}
