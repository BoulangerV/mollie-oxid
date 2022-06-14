<?php

$sLangName = "Deutsch";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
    'charset'                                           => 'UTF-8',

    /* SETTINGS */
    'SHOP_MODULE_GROUP_MOLLIE_GENERAL'                  => 'Grundkonfiguration',
    'SHOP_MODULE_sMollieMode'                           => 'Modus',
    'SHOP_MODULE_sMollieMode_live'                      => 'Live',
    'SHOP_MODULE_sMollieMode_test'                      => 'Test',
    'SHOP_MODULE_sMollieTestToken'                      => 'Test API Key',
    'SHOP_MODULE_sMollieLiveToken'                      => 'Live API Key',
    'SHOP_MODULE_blMollieShowIcons'                     => 'Icons Anzeigen',
    'SHOP_MODULE_blMollieLogTransactionInfo'            => 'Ergebnisse von Transaktions-Verarbeitung loggen',
    'SHOP_MODULE_blMollieRemoveDeactivatedMethods'      => 'Deaktivierte Zahlarten entfernen',
    'SHOP_MODULE_GROUP_MOLLIE_STATUS_MAPPING'           => 'Status Mapping',
    'SHOP_MODULE_sMollieStatusPending'                  => 'Status Ausstehend',
    'SHOP_MODULE_sMollieStatusProcessing'               => 'Status in Bearbeitung',
    'SHOP_MODULE_sMollieStatusCancelled'                => 'Status Storniert',
    'SHOP_MODULE_GROUP_MOLLIE_CRONJOBS'                 => 'Cronjobs',
    'SHOP_MODULE_sMollieCronOrderExpiryActive'          => 'Cronjob "Unbezahlte Bestellungen automatisch stornieren" aktiv',
    'SHOP_MODULE_sMollieCronFinishOrdersActive'         => 'Cronjob "Abschluss von bezahlten aber nicht vollendeten Bestellungen" aktiv',
    'SHOP_MODULE_sMollieCronSecondChanceActive'         => 'Cronjob "Versand einer Zahlungs-Erinnerungs-Email" aktiv',
    'SHOP_MODULE_iMollieCronSecondChanceTimeDiff'       => 'Zeitfenster nach dem Zahlungs-Erinnerungs-Email versendet wird',
    'SHOP_MODULE_sMollieCronOrderShipmentActive'        => 'Cronjob "&Uuml;bermittlung von Versandstatus an Mollie" aktiv',
    'SHOP_MODULE_sMollieCronSecureKey'                  => 'Cronjob Sicherheits-Schlüssel',
    'SHOP_MODULE_GROUP_MOLLIE_PAYMENTLOGOS'             => 'Alternative Zahlart Logos',
    'SHOP_MODULE_GROUP_MOLLIE_APPLEPAY'                 => 'Apple Pay',
    'SHOP_MODULE_blMollieApplePayButtonOnBasket'        => 'Apple Pay Button auf Warenkorb Seite anzeigen',
    'SHOP_MODULE_blMollieApplePayButtonOnDetails'       => 'Apple Pay Button auf Artikel Detailseite anzeigen',

    'HELP_SHOP_MODULE_blMollieShowIcons'                => 'Zahlungs-Icons beim Bezahlen Anzeigen',
    'HELP_SHOP_MODULE_blMollieLogTransactionInfo'       => 'Log-Datei zu finden unter SHOPROOT/log/MollieTransactions.log',
    'HELP_SHOP_MODULE_blMollieRemoveDeactivatedMethods' => 'Entfernt in der Zahlart-Auswahl im Frontend die Zahlarten, die im Mollie Dashboard nicht aktiviert wurden und somit zu einem Fehler f&uuml;hren w&uuml;rden.',
    'HELP_SHOP_MODULE_sMollieStatusPending'             => 'Legen Sie den Bestell-Status fest, bevor der Kunde zum Zahlungs-Gateway umgeleitet wird',
    'HELP_SHOP_MODULE_sMollieStatusProcessing'          => 'Legen Sie den Bestell-Status für Abgeschlossene Zahlungen fest',
    'HELP_SHOP_MODULE_sMollieStatusCancelled'           => 'Legen Sie den Bestell-Status für stornierte Bestellungen fest',
    'HELP_SHOP_MODULE_sMollieCronOrderExpiryActive'     => 'Damit dieser Cronjob arbeitet, m&uuml;ssen Sie zus&auml;tzlich zu dieser Checkbox noch sicherstellen dass der Mollie Cronjob eingerichtet ist. Mehr dazu erfahren Sie in der README.md Datei des Moduls.',
    'HELP_SHOP_MODULE_sMollieCronFinishOrdersActive'    => 'Dieser Cronjob hat zur Aufgabe Bestellungen abzuschließen, bei denen der Kunde erfolgreich bezahlt hat, aber scheinbar nicht wieder in den Shop zurückgekehrt ist für den Abschluss des Bestellvorgangs. Der Cronjob finalisiert nur Bestellungen aus den letzten 24 Stunden, um keine Bestellungen zu ver&auml;ndern die wahrscheinlich schon manuell bearbeitet wurden.<br><br>Damit dieser Cronjob arbeitet, m&uuml;ssen Sie zus&auml;tzlich zu dieser Checkbox noch sicherstellen dass der Mollie Cronjob eingerichtet ist. Mehr dazu erfahren Sie in der README.md Datei des Moduls.',
    'HELP_SHOP_MODULE_sMollieCronSecondChanceActive'    => 'Damit dieser Cronjob arbeitet, m&uuml;ssen Sie zus&auml;tzlich zu dieser Checkbox noch sicherstellen dass der Mollie Cronjob eingerichtet ist. Mehr dazu erfahren Sie in der README.md Datei des Moduls.',
    'HELP_SHOP_MODULE_sMollieCronOrderShipmentActive'   => 'Dieser Cronjob ist nur notwendig, wenn der Versandstatus in Ihrem Shop &uuml;ber ein externes System gesetzt wird und NICHT &uuml;ber den "Jetzt versenden" Button im Oxid-Admin. Damit dieser Cronjob arbeitet, m&uuml;ssen Sie zus&auml;tzlich zu dieser Checkbox noch sicherstellen dass der Mollie Cronjob eingerichtet ist. Mehr dazu erfahren Sie in der README.md Datei des Moduls.',
    'HELP_SHOP_MODULE_sMollieCronSecureKey'             => 'Diese Option ben&ouml;tigen Sie nur, wenn Sie den Cronjob per URL aufrufen m&uuml;ssen anstelle von dem Aufruf über die php cli. Konfigurieren Sie hier einen Sicherheitsschlüssel, den Sie über den GET-Parameter "secureKey" an den Aufruf des Cronjobs anf&uuml;gen. Bsp.: https://YOUR-SHOP-URL-HERE/modules/mollie/molliepayment/cron.php?secureKey=YOUR-SECURE-KEY',

    'MOLLIE_YES'                                        => 'Ja',
    'MOLLIE_NO'                                         => 'Nein',
    'MOLLIE_DAY'                                        => 'Tag',
    'MOLLIE_DAYS'                                       => 'Tage',
    'MOLLIE_IS_MOLLIE'                                  => 'Dies ist eine Mollie Zahlungsart',
    'MOLLIE_IS_METHOD_ACTIVATED'                        => 'Diese Zahlungsart ist in Ihrem Mollie Account nicht aktiviert!',
    'MOLLIE_TOKEN_NOT_CONFIGURED'                       => 'Ihr Mollie-Token wurde noch nicht konfiguriert!',
    'MOLLIE_CONFIG_METHOD'                              => 'API Methode',
    'MOLLIE_DUE_DATE'                                   => 'F&auml;lligkeitstage',
    'MOLLIE_BANKTRANSFER_PENDING'                       => 'Status Ausstehend',
    'MOLLIE_LIST_STYLE'                                 => 'Listen Anzeige-Stil',
    'MOLLIE_LIST_STYLE_DROPDOWN'                        => 'Dropdown',
    'MOLLIE_LIST_STYLE_IMAGES'                          => 'Liste mit Bildern',
    'MOLLIE_LIST_STYLE_DONT_SHOW'                       => 'Keine Liste anzeigen',
    'MOLLIE_ADD_QR'                                     => 'QR-Code Option in Liste anzeigen',
    'MOLLIE_ORDER_REFUND'                               => 'Mollie',
    'MOLLIE_REFUND_SUCCESSFUL'                          => 'R&uuml;ckerstattung war erfolgreich.',
    'MOLLIE_NO_MOLLIE_PAYMENT'                          => 'Diese Bestellung wurde nicht mit Mollie bezahlt.',
    'MOLLIE_REFUND_QUANTITY'                            => 'Erstattungsmenge',
    'MOLLIE_REFUND_AMOUNT'                              => 'Erstattungsbetrag',
    'MOLLIE_TYPE_SELECT_LABEL'                          => 'Erstattung durchf&uuml;hren &uuml;ber',
    'MOLLIE_QUANTITY'                                   => 'Menge',
    'MOLLIE_NOTICE'                                     => 'Hinweis',
    'MOLLIE_AMOUNT'                                     => 'Betrag',
    'MOLLIE_HEADER_ORDERED'                             => 'Bezahlt',
    'MOLLIE_HEADER_REFUNDED'                            => 'Erstattet',
    'MOLLIE_HEADER_SINGLE_PRICE'                        => 'Einzelpreis',
    'MOLLIE_SHIPPINGCOST'                               => "Versandkosten",
    'MOLLIE_PAYMENTTYPESURCHARGE'                       => "Zahlungsart-Aufschlag",
    'MOLLIE_WRAPPING'                                   => "Geschenkverpackung",
    'MOLLIE_GIFTCARD'                                   => "Grußkarte",
    'MOLLIE_VOUCHER'                                    => 'Gutschein',
    'MOLLIE_DISCOUNT'                                   => 'Rabatt',
    'MOLLIE_REFUND_SUBMIT'                              => 'Erstattung durchf&uuml;hren',
    'MOLLIE_FULL_REFUND'                                => 'Vollst&auml;ndige R&uuml;ckerstattung',
    'MOLLIE_PARTIAL_REFUND'                             => 'Teilerstattung',
    'MOLLIE_FULL_REFUND_TEXT'                           => 'Vollst&auml;ndige Erstattung durchf&uuml;hren &uuml;ber Betrag von',
    'MOLLIE_FULL_REFUND_NOT_AVAILABLE'                  => 'Eine Vollst&auml;ndige R&uuml;ckerstattung ist f&uuml;r diese Bestellung nicht mehr verf&uuml;gbar, da Sie bereits Einzelpositionen zur&uuml;ckerstattet haben.',
    'MOLLIE_REFUND_DESCRIPTION'                         => 'Erstattungs-Hinweis',
    'MOLLIE_REFUND_DESCRIPTION_PLACEHOLDER'             => 'Optional - Max 140 Zeichen',
    'MOLLIE_REFUND_FREE_AMOUNT'                         => 'Freier Erstattungsbetrag',
    'MOLLIE_REFUND_FREE_1'                              => 'Vom Gesamtbetrag',
    'MOLLIE_REFUND_FREE_2'                              => 'wurden bereits',
    'MOLLIE_REFUND_FREE_3'                              => 'erstattet. Restbetrag',
    'MOLLIE_ORDER_NOT_REFUNDABLE'                       => 'Diese Bestellung wurde bereits vollst&auml;ndig erstattet.',
    'MOLLIE_REFUND_REMAINING'                           => 'Verbliebene Summe erstatten',
    'MOLLIE_VOUCHERS_EXISTING'                          => 'Achtung - Diese Bestellung enth&auml;lt Gutscheine oder Rabatte. Diese können nicht teilerstattet werden, sondern m&uuml;ssen &uuml;ber die vollst&auml;ndige oder die Rest-R&uuml;ckerstattung abgewickelt werden.',
    'MOLLIE_CREDITCARD_DATA_INPUT'                      => 'Kreditkarten-Daten',
    'MOLLIE_CREDITCARD_DATA_INPUT_HELP'                      => 'Diese Option bestimmt, wo der Kunde die Kreditkartendaten eingeben muss.<br>Die empfohlene Methode ist "Eingabe im Checkout &uuml;ber Iframe Formular-Felder".',
    'MOLLIE_CC_HOSTED_CHECKOUT'                         => 'Eingabe auf externer Mollie-Webseite',
    'MOLLIE_CC_CHECKOUT_INTEGRATION'                    => 'Eingabe im Checkout &uuml;ber Iframe Formular-Felder',
    'MOLLIE_APPLE_PAY_BUTTON_ONLY_LIVE_MODE'            => 'Hinweis: Bezahlung mit Apple Pay Button ist nur im Live-Modus m&ouml;glich',
    'MOLLIE_APIKEY_CONNECTED'                           => 'Verbindung erfolgreich',
    'MOLLIE_APIKEY_DISCONNECTED'                        => 'Verbindung nicht erfolgreich',
    'MOLLIE_ORDER_EXPIRY'                               => 'Automatische Stornierung nach',
    'MOLLIE_ORDER_EXPIRY_DAYS'                          => 'Tage',
    'MOLLIE_DEACTIVATED'                                => 'Deaktiviert',
    'MOLLIE_ORDER_EXPIRY_HELP'                          => 'Das Mollie Modul bietet das Feature Bestellungen die seit einem hier zu konfigurierenden Zeitraum auf die Bezahlung warten automatisch zu stornieren. Dies trifft auf Bestellungen die im von Ihnen konfigurierten "Status Ausstehend" sind zu. Hierf&uuml;r m&uuml;ssen Sie den Mollie Cronjob einrichten. Mehr dazu erfahren Sie in der README.md Datei des Moduls.',
    'MOLLIE_ALTLOGO_ERROR'                              => 'Beim Datei-Upload ist ein Fehler aufgetreten. Bitte pr&uuml;fen Sie die Berechtigung des Ordners SHOPROOT/source/modules/mollie/molliepayment/out/img/',
    'MOLLIE_ALTLOGO_LABEL'                              => 'Alternatives Logo',
    'MOLLIE_ALTLOGO_FILENAME'                           => 'Dateiname',
    'MOLLIE_ALTLOGO_DELETE'                             => 'Logo l&ouml;schen',
    'MOLLIE_SINGLE_CLICK'                               => 'Single Click payments aktivieren',
    'MOLLIE_SINGLE_CLICK_HELP'                          => 'Single Click payment bedeutet, dass die Zahlungsdaten des Kunden bei Mollie gespeichert werden, sodass der Kunde ohne Eingabe der Daten beim n&auml;chsten mal direkt bezahlen kann. Dies muss vom Kunden im Checkout noch mal explizit best&auml;tigt werden. Diese Option hat nur Effekt, wenn der Kreditkarten-Daten Modus auf "Eingabe auf externer Mollie-Webseite" gestellt ist.',
    'MOLLIE_PAYMENT_API_LINK_1'                         => 'Mehr Informationen zur Payment-API finden Sie',
    'MOLLIE_PAYMENT_API_LINK_2'                         => 'hier',
    'MOLLIE_ORDER_API_LINK_1'                           => 'Mehr Informationen zur Order-API finden Sie',
    'MOLLIE_ORDER_API_LINK_2'                           => 'hier',
    'MOLLIE_CONNECTION_DATA'                            => 'Abruf der Verbindungsdaten &uuml;ber:',
    'MOLLIE_ORDER_PAYMENT_URL'                          => 'Link zum Abschluss der Zahlung',
    'MOLLIE_SEND_SECOND_CHANCE_MAIL'                    => 'Second Chance Email versenden',
    'MOLLIE_SECOND_CHANCE_MAIL_ALREADY_SENT'            => 'Die Email wurde bereits versendet.',
    'MOLLIE_SUBSEQUENT_ORDER_COMPLETION'                => 'Nachtr&auml;glicher Bestellabschluss',
    'MOLLIE_PAYMENT_DESCRIPTION'                        => 'Zahlungsbezeichnung',
    'MOLLIE_PAYMENT_DESCRIPTION_HELP'                   => 'Dies wird auf dem Kontoauszug des Kunden angezeigt soweit m&ouml;glich.<br><br>Sie k&ouml;nnen die folgenden Parameter benutzen:<br>{orderId}<br>{orderNumber}<br>{storeName}<br>{customer.firstname}<br>{customer.lastname}<br>{customer.company}',
    'MOLLIE_MODULE_VERSION_OUTDATED'                    => 'Achtung! Die aktuellste Modulversion ist',
    'MOLLIE_SUPPORT_HEADER'                             => 'Kontaktieren Sie uns - Technischer Support',
    'MOLLIE_SUPPORT_REQUIRED_FIELDS'                    => 'Bitte f&uuml;llen Sie alle Pflichtfelder aus.',
    'MOLLIE_SUPPORT_FORM_NAME'                          => 'Name',
    'MOLLIE_SUPPORT_FORM_EMAIL'                         => 'E-mail',
    'MOLLIE_SUPPORT_FORM_SUBJECT'                       => 'Betreff',
    'MOLLIE_SUPPORT_FORM_ENQUIRY'                       => 'Anfrage',
    'MOLLIE_SUPPORT_FORM_ENQUIRY_PLACEHOLDER'           => 'Wie k&ouml;nnen wir Ihnen helfen?',
    'MOLLIE_SUPPORT_FORM_SUBMIT'                        => 'Absenden',
    'MOLLIE_SUPPORT_EMAIL_SENT'                         => 'Ihre Anfrage wurde erfolgreich versendet. Sie werden eine Kopie der Email erhalten.',
    'MOLLIE_PAYMENT_LIMITATION'                         => 'Mollie Beschr&auml;nkung',
    'MOLLIE_PAYMENT_LIMITATION_FROM'                    => 'Von',
    'MOLLIE_PAYMENT_LIMITATION_TO'                      => 'bis',
    'MOLLIE_PAYMENT_LIMITATION_UNLIMITED'               => 'unbegrenzt',
);
