contao-recent
=============

extension to Web-CMS Contao for listing recently changed articles

(c) 2010-2013, Cepharum GmbH, Berlin
http://www.cepharum.de


Introduction
------------

This extension provides insert tag for listing a set of most recently published
or updated articles limited in size. Articles require to enable showing teaser
to be included in this list. Furthermore they must be placed in main area of
page unless different focus has been selected. Sorting order depends on time of
last update unless start of publishing is set on article to overrule the former.

A separate template is used to render the resulting list. Used in a module this
extension features conveniently listing eye-catchers to recently published or
updated articles on your site.

Additionally a second insert tag {{recent_articles_list}} is introduced to
support using different template for rendering navigation element addressing all
recent articles. This second insert tag supports the same options as {{recent}}.

Addressing articles usually refers to containing page unless article isn't set
in main area of page or there is more than one article in column main.


Examples:
---------

{{recent}} lists up to 3 articles meeting criteria mentioned above

{{recent::5}} extends that limit to a maximum of 5 articles to be listed

{{recent::5::left}} lists up to 5 articles basically meeting same criteria as
mentioned before. But this time only articles associated with left area of page
will match.

{{recent_articles_list::5}} lists up to 5 links to articles meeting same
criteria as before.



German Translation / Deutsche Übersetzung:
------------------------------------------

Diese Erweiterung für Contao ergänzt einen Insert-Tag, mit dem man eine in ihrer
Zahl begrenzte Menge kürzlich freigegebener oder aktualisierter Artikel
auflisten kann. Um dabei berücksichtigt zu werden, muss für einen Artikel die
Anzeige des Teasers aktiviert sein. Weiterhin muss der Artikel mit dem
Hauptbereich der Seite assoziiert sein, sofern durch den Insert-Tag kein anderer
Seitenbereich ausgewählt wird. Die Reihenfolge der Anzeige hängt vom Zeitpunkt
der letzten Änderung am Artikel fest, sofern nicht eine Startzeit für die
automatische Freigabe hinterlegt wurde, welche dann anstelle des
Änderungszeitpunkts für die Sortierung fest genutzt wird.

Ein weiteres Template wird zur Anzeige dieser integriert. In einem Modul kann
diese Erweiterung eine bequeme Listung kürzlich veröffentlichter oder
überarbeiteter Artikel als Blickfang im Seitentemplate integriert ermöglichen.

Zusätzlich wird ein zweiter Insert-Tag {{recent_articles_list}} eingeführt,
welcher ein Navigationselement erzeugt, dessen Einträge auf die aktuellen
Artikel verweist. Dieser Tag unterstützt die gleichen Argumente wie {{recent}}.

Die Artikel werden über die sie enthaltende Seite addressiert, jedoch nur bei 
Artikeln, welche in der Hauptspalte als dort einziger Artikel der Seite gesetzt 
wurden.

Beispiele:
----------

{{recent}} listet bis zu 3 Artikel, die den obigen Kriterien genügen.

{{recent::5}} erweitert diese Grenze auf höchstens 5 Artikel in der Liste.

{{recent::5::left}} listet bis zu 5 Artikel, die den selben Kriterien genügen
wie zuvor. Nur diesmal werden nur Artikel berücksichtigt, die dem linken
Seitenbereich zugeordnet sind.

{{recent_articles_list::5}} zeigt eine Liste mit bis zu 5 Verweisen auf kürzlich
freigegebene oder überarbeitete Artikel.

