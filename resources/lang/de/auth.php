
<?php

return [
    'failed'   => 'Diese Kombination aus Zugangsdaten wurde nicht in unserer Datenbank gefunden.',
    'password' => 'Das eingegebene Passwort ist nicht korrekt.',
    'throttle' => 'Zu viele Loginversuche. Versuchen Sie es bitte in :seconds Sekunden nochmal.',
    'login_success' => 'Erfolgreich eingeloggt.',
    'login_failed' => 'Anmelden fehlgeschlagen.',
    'register_success' => 'Account erfolgreich erstellt.',
    'unauthenticated' => 'Benutzer ist nicht angemeldet.',
    'already_authed' => 'Benutzer ist bereits angemeldet.',
    'get_details' => 'Erfolgreich Benutzerdetails geladen.',
    'logged_out' => 'Erfolgreich vom Konto abgemeldet.',
    'invalid_email' => 'Diese Email-Adresse gehört zu keinem Account.',
    'invalid_reset_token' => 'Ungültiger Zugangsschlüssel.',
    'invalid_password' => 'Ungültiges Passwort.',
    'mail' => [
        'reset_password' => [
            'title' => 'Passwort zurücksetzen',
            'call_click' => 'Passwort vergessen? Kein Problem! Drücke einfach auf den Knopf unten.',
            'button' => 'Zurücksetzen'
        ],
        'verify_mail' => [
            'title' => 'Email-Adresse bestätigen',
            'call_click' => 'Um deine Email-Adresse zu bestätigen, drücke einfach auf den Knopf unten.',
            'button' => 'Bestätigen',
            'not_you' => 'Kein Account erstellt? Dann ignoriere einfach diese Meldung. :)'
        ],
        'login_success' => [
            'title' => 'Erfolgreich angemeldet',
            'subtitle' => 'Jemand hat sich so eben in deinen Account angemeldet.',
            'not_you' => 'Warst das du? Kein Problem, dann ignoriere diese Meldung einfach. Nicht du? Dann ändere sofort dein Passwort und eventuell sogar deine Email Adresse, alternativ melde dich bei einem Administrator deines Vertrauens und erhalte weitere Hilfe.',
            'button' => 'Zur Anmeldung'
        ],
        'login_failed' => [
            'title' => 'Anmeldung fehlgeschlagen',
            'subtitle' => 'Jemand wollte sich so eben in deinen Account anmelden.',
            'not_you' => 'Warst das du? Kein Proble, dann ignoriere diese Meldung einfach. Nicht du? Dann halte Augen und Ohren offen, ob jemand in deinen Account möchte oder es nur einmalig war. Präventiv kannst du dein Passwort verstärken, falls du dich weitergehend schützen möchtest.',
            'button' => 'Zur Anmeldung'
        ],
        'email_changed' => [
            'title' => 'Email-Adresse geändert',
            'subtitle' => 'Deine Email-Adresse wurde so eben geändert.',
            'not_you' => 'Warst das du? Kein Proble, dann ignoriere diese Meldung einfach. Nicht du? Dann melde dich so schnell wie möglich bei einem Administrator und dies rückgängig zu machen!',
            'button' => 'Zur Anmeldung',
        ],
        'password_changed' => [
            'title' => 'Passwort geändert',
            'subtitle' => 'Dein Passwort wurde so eben geändert.',
            'not_you' => 'Warst das du? Kein Proble, dann ignoriere diese Meldung einfach. Nicht du? Dann melde dich so schnell wie möglich bei einem Administrator und dies rückgängig zu machen!',
            'button' => 'Zur Anmeldung',
        ],
    ]
];
