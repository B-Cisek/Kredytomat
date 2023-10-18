<?php

return [
    'bank' => [
        'store' => 'Bank has been added successfully',
        'update' => 'Bank has been updated successfully',
        'delete' => 'Bank has been deleted successfully',
        'deleteFail' => 'Cannot remove bank assigned to credit',
        'massDeleteFail' => 'No banks selected for delete',
        'massDelete' => 'Selected banks have been deleted'
    ],
    'credit' => [
        'store' => 'Credit has been added successfully',
        'update' => 'Credit has been updated successfully',
        'delete' => 'Credit has been deleted successfully',
        'massDeleteFail' => 'No credits selected for delete',
        'massDelete' => 'Selected credits have been deleted'
    ],
    'user' => [
        'store' => 'User has been added successfully',
        'update' => 'User has been updated successfully',
        'delete' => 'User has been deleted successfully',
        'adminUser' => 'Admin user account cannot be deleted',
        'cannotDeleteUser' => 'You cannot delete a user who has simulations saved',
        'massDeleteFail' => 'No users selected for delete',
        'massDelete' => 'Selected users have been deleted'
    ],
    'sendResetPasswordLink' => [
        'noEmail' => 'No email address',
        'emailSent' => 'Sent link to change password'
    ],
    'authentication' => [
        'loggedOn' => 'Logged On',
        'loggedOut' => 'Logged Out',
        'registered' => 'Registered'
    ],
    'newPassword' => 'The password has been changed',
    'profile' => [
        'update' => 'Profile has been updated',
        'wrongCurrentPassword' => 'The current password is not correct',
        'passwordUpdated' => 'Password updated',
        'accountRemoved' => 'Account deleted'
    ],
    'creditSimulation' => [
        'alreadyExist' => 'Credit simulation already exists',
        'saved' => 'Credit simulation has been saved',
        'deleted' => 'Credit simulation has been deleted successfully',
    ],
    'overpaymentSimulation' => [
        'alreadyExist' => 'Overpayment simulation already exists',
        'saved' => 'Overpayment simulation has been saved',
        'deleted' => 'Overpayment simulation has been deleted successfully',
    ]
];
