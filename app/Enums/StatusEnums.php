<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';
    case Refunded = 'refunded';
}

enum PaymentMethod: string
{
    case Card = 'card';
    case GooglePay = 'google_pay';
    case ApplePay = 'apple_pay';
}

enum NotificationType: string
{
    case Booking = 'booking';
    case Payment = 'payment';
    case System = 'system';
}

enum EarningStatus: string
{
    case Pending = 'pending';
    case Processed = 'processed';
    case Paid = 'paid';
}

enum DiscountType: string
{
    case Percentage = 'percentage';
    case Fixed = 'fixed';
}
