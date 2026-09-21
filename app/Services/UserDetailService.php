<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDetail;

class UserDetailService
{
    /**
     * Get the user detail for a given user.
     */
    public function getUserDetail(User $user): ?UserDetail
    {
        return $user->detail;
    }

    /**
     * Create or update the user detail for a given user.
     */
    public function updateOrCreateUserDetail(User $user, array $data): UserDetail
    {
        $attributes = [];

        if (array_key_exists('nik', $data)) {
            $attributes['nik'] = $data['nik'];
        }

        $jkKey = match (true) {
            array_key_exists('jenis_kelamin', $data) => 'jenis_kelamin',
            array_key_exists('gender', $data) => 'gender',
            default => null,
        };

        if ($jkKey !== null) {
            $attributes['jenis_kelamin'] = $data[$jkKey];
        }

        $tglLahirKey = match (true) {
            array_key_exists('tgl_lahir', $data) => 'tgl_lahir',
            default => null,
        };

        if ($tglLahirKey !== null) {
            $attributes['tgl_lahir'] = $data[$tglLahirKey];
        }

        $teleponKey = match (true) {
            array_key_exists('no_telepon', $data) => 'no_telepon',
            default => null,
        };

        if ($teleponKey !== null) {
            $attributes['no_telepon'] = $data[$teleponKey];
        }

        return UserDetail::updateOrCreate(
            ['user_id' => $user->user_id],
            $attributes
        );
    }
}
