<?php

namespace App\Helper;

use App\Models\User;

class UserHelper
{

    public function validateRut($rut): bool
    {
        $rut = str_replace('.', '', $rut); // remove char .
        $rut = str_replace('-', '', $rut); // remove char -
        $dv = substr($rut, -1);

        $rut = substr($rut, 0, -1); // remove verified digit

        $acc = 1;
        $count = 0;
        while ($rut != 0) {
            $acc = ($acc + (intval($rut) % 10) * (9 - $count++ % 6)) % 11;
            $rut = (int)($rut / 10);
        }

        $dvCalculate = $acc ? chr($acc + 47) : 'K';
        $cleanDv = str_replace('/', '', $dvCalculate);

        if (strtolower($dv) != strtolower($cleanDv)) {
            return false;
        }

        return true;
    }

    public function getFullName($userId): string {
        $user = User::whereId($userId)->first();

        return $user->name . ' ' . $user->lastname;
    }

    public function getMechanicUsers(): array
    {
        $users = User::all();

        foreach ($users as $user) {
            $userRole = $user->getRoleNames();
            if ($userRole[0] === User::MECHANIC) {
                $fullName = $this->getFullName($user->id);
                $mechanics[$user->id] = $fullName;
            }
        }

        return $mechanics ?? [];
    }

    public function getCustomerUsers(): array
    {
        $clients = [];
        $users = User::all();
        foreach ($users as $user) {
            $userRole = $user->getRoleNames();
            if ($userRole[0] === User::CLIENT) {
                $fullName = $this->getFullName($user->id);
                $clients[$user->id] = $fullName;
            }
        }

        return $clients ?? [];
    }
}
