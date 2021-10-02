<?php

return [
    'base' => [
        'get_all_success' => 'Erfolgreich alle Daten geladen.',
        'get_success' => 'Erfolgreich Modell Daten geladen.',
        'store_success' => 'Erfolgreich Modell erstellt.',
        'update_success' => 'Erfolgreich Modell aktualisiert.',
        'update_skipped' => 'Aktualisierung wurde übersprungen. Angegebene Daten entsprechen den Orginaldaten.',
        'soft_delete_success' => 'Modell wurde in den Papierkorb verschoben.',
        'force_delete_success' => 'Modell wurde entgültig entfernt.',
        'recover_success' => 'Modell wurde wiederhergestellt.',
        'get_not_found' => 'Ungültiges Modell angefragt.',
        'store_unknown_error' => 'Ein unbekannter Fehler ist bei dem erstellen aufgetreten.'
    ],

    'relation' => [
        'invalid_parent' => 'Ungültiges übergeordnetes Element.',
        'invalid_child' => 'Ungültiges untergeordnetes Element.',
        'not_belongs' => 'Über- und Untergeordnetes Element gehören nicht zueinander.',
        'get_all_success' => 'Beziehungsdaten wurden erfolgreich geladen.',
        'checked_success' => 'Beziehungsstatus der Elemente wurden geprüft.',
        'attached_success' => 'Elemente wurden zusammengefügt.',
        'detached_success' => 'Elemente wurden voneinander gelöst.',
        'duplicate_combi' => 'Die Kombination aus Über- und Untergeordnetes Element ist ungültig oder existiert bereits.',
    ],
];
