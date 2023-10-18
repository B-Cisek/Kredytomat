<?php

return [
    'bank' => [
        'store' => 'Bank został dodany pomyślnie',
        'update' => 'Bank został pomyślnie zaktualizowany',
        'delete' => 'Bank został pomyślnie usunięty',
        'deleteFail' => 'Nie można usunąć banku przypisanego do kredytu',
        'massDeleteFail' => 'Nie wybrano banków do usunięcia',
        'massDelete' => 'Wybrane banki zostały usunięte'
    ],
    'credit' => [
        'store' => 'Kredyt został pomyślnie dodany',
        'update' => 'Kredyt został pomyślnie zaktualizowany',
        'delete' => 'Kredyt został pomyślnie usunięty',
        'massDeleteFail' => 'Nie wybrano kredytów do usunięcia',
        'massDelete' => 'Wybrane kredyty zostały usunięte'
    ],
    'user' => [
        'store' => 'Użytkownik został pomyślnie dodany',
        'update' => 'Użytkownik został pomyślnie zaktualizowany',
        'delete' => 'Użytkownik został pomyślnie usunięty',
        'adminUser' => 'Konto użytkownika admin nie może zostać usunięte',
        'cannotDeleteUser' => 'Nie można usunąć użytkownika który ma zapisane symulacje',
        'massDeleteFail' => 'Nie wybrano użytkowników do usunięcia',
        'massDelete' => 'Usunięto zaznaczonch użytkowników'
    ],
    'sendResetPasswordLink' => [
        'noEmail' => 'Brak adresu email',
        'emailSent' => 'Wysłano link do zmiany hasła'
    ],
    'authentication' => [
        'loggedOn' => 'Zalogowano',
        'loggedOut' => 'Wylogowano',
        'registered' => 'Zarejestrowano'
    ],
    'newPassword' => 'Hasło zostało zmienione',
    'profile' => [
        'update' => 'Profil został zaktualizowany',
        'wrongCurrentPassword' => 'Aktulane hasło jest niepoprawne',
        'passwordUpdated' => 'Hasło zmienione',
        'accountRemoved' => 'Konto usunięte'
    ],
    'creditSimulation' => [
        'alreadyExist' => 'Symulacja kredytu już istnieje',
        'saved' => 'Zapisano symulacje kredytu',
        'deleted' => 'Symulacja kredytu został pomyślnie usunięta',
    ],
    'overpaymentSimulation' => [
        'alreadyExist' => 'Symulacja nadpłaty już istnieje',
        'saved' => 'Symulacja nadpłaty została zapisana',
        'deleted' => 'Symulacja nadpłaty została usunięta',
    ]
];
