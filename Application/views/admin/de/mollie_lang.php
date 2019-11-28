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
    'SHOP_MODULE_sMollieStatusPending'                  => 'Status Ausstehend',
    'SHOP_MODULE_sMollieStatusProcessing'               => 'Status in Bearbeitung',

    'HELP_SHOP_MODULE_blMollieShowIcons'                => 'Zahlungs-Icons beim Bezahlen Anzeigen',
    'HELP_SHOP_MODULE_blMollieLogTransactionInfo'       => 'Log-Datei zu finden unter SHOPROOT/log/MollieTransactions.log',
    'HELP_SHOP_MODULE_blMollieRemoveDeactivatedMethods' => 'Entfernt in der Zahlart-Auswahl im Frontend die Zahlarten, die im Mollie Dashboard nicht aktiviert wurden und somit zu einem Fehler f&uuml;hren w&uuml;rden.',
    'HELP_SHOP_MODULE_sMollieStatusPending'             => 'Legen Sie den Bestell-Status fest, bevor der Kunde zum Zahlungs-Gateway umgeleitet wird',
    'HELP_SHOP_MODULE_sMollieStatusProcessing'          => 'Legen Sie den Bestell-Status für Abgeschlossene Zahlungen fest',

    'MOLLIE_YES'                                        => 'Ja',
    'MOLLIE_NO'                                         => 'Nein',
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
    'MOLLIE_ORDER_NOT_REFUNDABLE'                       => 'Diese Bestellung wurde bereits vollst&auml;ndig erstattet.',
    'MOLLIE_REFUND_REMAINING'                           => 'Verbliebene Summe erstatten',
    'MOLLIE_VOUCHERS_EXISTING'                          => 'Achtung - Diese Bestellung enth&auml;lt Gutscheine oder Rabatte. Diese können nicht teilerstattet werden, sondern m&uuml;ssen &uuml;ber die vollst&auml;ndige oder die Rest-R&uuml;ckerstattung abgewickelt werden.',
    'MOLLIE_CREDITCARD_DATA_INPUT'                      => 'Kreditkarten-Daten',
    'MOLLIE_CC_HOSTED_CHECKOUT'                         => 'Eingabe auf externer Mollie-Webseite',
    'MOLLIE_CC_CHECKOUT_INTEGRATION'                    => 'Eingabe im Checkout &uuml;ber Iframe Formular-Felder',
);
