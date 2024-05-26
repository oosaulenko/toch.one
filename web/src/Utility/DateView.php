<?php

namespace App\Utility;

use DateTime;

trait DateView
{

    public function humanDate($date): string {
        $now = new DateTime('now');

        $interval = $now->diff($date);

        if ($interval->d > 0) {
            return $interval->format('%a days ago');
        } elseif ($interval->h > 0) {
            return $interval->format('%h hours ago');
        } elseif ($interval->i > 0) {
            return $interval->format('%i minutes ago');
        } else {
            return 'Just now';
        }
    }

}