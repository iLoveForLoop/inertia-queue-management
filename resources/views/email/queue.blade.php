<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Queue Status Update</title>
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f5f7fa; color: #333333;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%"
        style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <!-- Header -->
        <tr>
            <td
                style="padding: 30px 0; text-align: center; background-color: #ffffff; border-top: 4px solid #14b8a6; border-radius: 8px 8px 0 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="text-align: center; padding: 0 20px;">
                            <!-- Logo and Name -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="text-align: center;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            align="center">
                                            <tr>
                                                <td
                                                    style="font-size: 28px; font-weight: bold; color: #14b8a6; vertical-align: middle;">
                                                    MediQueue
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Main Content -->
        <tr>
            <td
                style="background-color: #ffffff; padding: 30px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <!-- Greeting -->
                    <tr>
                        <td style="padding-bottom: 20px; font-size: 18px; color: #333333;">
                            Hello {{ $queueItem->user->name }},
                        </td>
                    </tr>

                    <!-- Notification Box with Dynamic Background Color -->
                    <tr>
                        <td style="padding: 0 0 30px 0;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="@if ($queueItem->status == 'pending') background: linear-gradient(to right, #14b8a6, #0369a1);@elseif($queueItem->status == 'completed')background: linear-gradient(to right, #10b981, #059669);@elseif($queueItem->status == 'canceled')background: linear-gradient(to right, #ef4444, #b91c1c); @endif border-radius: 8px; color: white;">
                                <tr>
                                    <td style="padding: 30px; text-align: center;">
                                        <p style="margin: 0; font-size: 16px; margin-bottom: 15px;">Your Queue Number
                                        </p>
                                        <p style="margin: 0; font-size: 36px; font-weight: bold; margin-bottom: 15px;">
                                            {{ $queueItem->queue_number }}</p>

                                        @if ($queueItem->status == 'pending')
                                            <p
                                                style="margin: 0; font-size: 20px; font-weight: bold; background-color: #ffffff; color: #14b8a6; padding: 8px 16px; border-radius: 20px; display: inline-block;">
                                                NOW SERVING</p>
                                        @elseif($queueItem->status == 'completed')
                                            <p
                                                style="margin: 0; font-size: 20px; font-weight: bold; background-color: #ffffff; color: #10b981; padding: 8px 16px; border-radius: 20px; display: inline-block;">
                                                COMPLETED</p>
                                        @elseif($queueItem->status == 'canceled')
                                            <p
                                                style="margin: 0; font-size: 20px; font-weight: bold; background-color: #ffffff; color: #dc2626; padding: 8px 16px; border-radius: 20px; display: inline-block;">
                                                CANCELED</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Dynamic Instructions based on status -->
                    <tr>
                        <td style="padding-bottom: 30px; line-height: 1.5; color: #4b5563;">
                            @if ($queueItem->status == 'pending')
                                <p style="margin: 0 0 15px 0;">Please proceed to the pharmacy counter. If you are not
                                    present when your number is called, your queue position will be automatically
                                    cancelled.
                                </p>
                            @elseif($queueItem->status == 'completed')
                                <p style="margin: 0 0 15px 0;">Your pharmacy transaction has been successfully
                                    completed. Thank you for using our queue management system.</p>
                                <p style="margin: 0 0 15px 0;">Transaction Details:</p>
                                <ul style="margin: 0 0 15px 0; padding-left: 20px;">
                                    <li>Date: {{ date('F d, Y', strtotime($queueItem->updated_at)) }}</li>
                                    <li>Time: {{ date('h:i A', strtotime($queueItem->updated_at)) }}</li>
                                    <li>Service: Pharmacy</li>
                                </ul>
                                <p style="margin: 0 0 15px 0;">We hope you found our service efficient and helpful. If
                                    you have any feedback or questions, please don't hesitate to contact our customer
                                    service team.</p>
                            @elseif($queueItem->status == 'canceled')
                                <p style="margin: 0 0 15px 0;">Your queue has been canceled. This may be because:</p>
                                <ul style="margin: 0 0 15px 0; padding-left: 20px;">
                                    <li>You were not present when your number was called</li>
                                    <li>You requested cancellation</li>
                                    <li>The service was temporarily unavailable</li>
                                </ul>
                                <p style="margin: 0 0 15px 0;">If you still need pharmacy services, please take a new
                                    queue number at our service counter or through the MediQueue app.</p>
                            @endif
                        </td>
                    </tr>

                    <!-- Signature -->
                    <tr>
                        <td style="border-top: 1px solid #e5e7eb; padding-top: 20px; font-size: 16px; color: #4b5563;">
                            <p style="margin: 0 0 8px 0;">Thanks,</p>
                            <p style="margin: 0; font-weight: bold; color: #14b8a6;">MediQueue Team</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="padding: 20px 0; text-align: center; color: #6b7280; font-size: 14px;">
                <p style="margin: 0 0 10px 0;">Saint Luke's BGC Taguig</p>
                <p style="margin: 0 0 10px 0;">Operating Hours: Mon-Sat, 8:00 AM - 5:00 PM</p>
                <p style="margin: 0;">© 2025 MediQueue. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>
