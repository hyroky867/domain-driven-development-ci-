<?php

declare(strict_types=1);

namespace Package\Part11\Repositories;

use Package\Part11\Entities;
use Package\Part11\ValueObjects;

final class User implements UserInterface
{
    private \App\Models\User $model;

    public function __construct(\App\Models\User $model)
    {
        $this->model = $model;
    }

    public function save(Entities\User $user): void
    {
        $this->model->save([
            'user_id' => $user->id->value,
            'name' => $user->name->value,
            'mail_address' => $user->mail_address->value,
        ]);
    }

    public function findByUserId(ValueObjects\User\UserId $user_id): ?Entities\User
    {
        $result = $this->model->where('user_id', $user_id->value)
            ->first();

        if ($result instanceof \App\Entities\User) {
            return new Entities\User(
                new ValueObjects\User\Name($result->name),
                new ValueObjects\User\UserId($result->user_id),
                new ValueObjects\User\MailAddress($result->mail_address)
            );
        }
        return null;
    }

    public function findByUserName(ValueObjects\User\Name $name): ?Entities\User
    {
        $result = $this->model->where('name', $name->value)
            ->first();

        if ($result instanceof \App\Entities\User) {
            return new Entities\User(
                new ValueObjects\User\Name($result->name),
                new ValueObjects\User\UserId($result->user_id),
                new ValueObjects\User\MailAddress($result->mail_address),
            );
        }
        return null;
    }

    public function delete(Entities\User $user): void
    {
        $this->model->where('user_id', $user->id->value)
            ->delete();
    }
}
