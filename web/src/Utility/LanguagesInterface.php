<?php

namespace App\Utility;

interface LanguagesInterface
{
    public function getLanguages(bool $revert = false): array;

    public function getSupportLangs(bool $revert = false): array;
}