<?php

namespace App\Services\Admin;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseService {

    protected Database $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(public_path('notification/cartub-7a7b5-firebase-adminsdk-fbsvc-59277d53fc.json'))
            ->withDatabaseUri(config('constants.FIREBASE_DATABASE_URL'));

        $this->database = $firebase->createDatabase();
    }

    public function storeBooking(array $bookingData): void
    {
        $this->database
            ->getReference('bookings/' . $bookingData['id']) // 'bookings/{booking_id}'
            ->set($bookingData);
    }
    
}
