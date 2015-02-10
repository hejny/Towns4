## TOWNS 4

 Verze Towns 4

* * *
### Kontakt

**Organizace vývoje:** https://trello.com/townsgame

**Mail:** ph@towns.cz

* * *
### Struktura

Systém je rozdělený na několik částí: 


**app** Soubory Administrace, Editory, Další  pomocné aplikace 

**core** Aktuální verze aplikace, systému a administrace

**image** Všechny obrázky

**lib** Knihovny: JQuery, PHP

**tmp**

předgenerované soubory. Sem se ukládají předgenerované obrázky budov, mapy, místa možné registrace…

Pokud se tato složka smaže nic se nestane, jen je systém bude chvíli pomalejší, neý se soubory stihnou znovu vytvořit.

Kvůli rychlosti doporučuju tuto složku dát na SSD disk. (v Linuxu např. pomocí symlinku)

**index.php** Inicializační soubor

**favicon.ico** Ikonka

**.htaccess** Konfigurační soubor pro Apache

* * *
### Požadavky

**Apache** - Allowoverride All

**PHP** - Vypnutý safe mode

**CUrl**

**GD**


* * *
### Návod na instalaci

**1)** Vytvořit tmp adresář (mod 0777)

**2)** Vytvořit a nakonfigurovat index.php podle index.sample.php

**3)** Spustit prohlížeč a jít do Towns4Admin:        (např.: http://localhost/www/small/admin/)

	Pokud je vše správně nastaveno, tak se v databázi automaticky vytvoří první tabulka small_memory.

**4)** Přihlásit se (jméno a heslo jsou v konfiguraci)

**5)** Spustit import světa ze souboru do databáze. (Dump světa s aktuální strukturou je v /app/admin/files/backup/small.sql)

	V průběhu importu se stane, že se Towns4Admin odhlásí. Je potřeba zkopírovat URL, znovu se přihlásit. A vložit URL zpět do prohlížeče. Pokud by začal import celý znovu, došlo by opět k odhlášení.

	(To je samozřejmě BUG, ale momentálně ho neřeším)

	Tuhle operaci je asi lepší provést přes terminál pomocí příkazu source.

**5)** Spustit v Towns4Admin CreateTmp - trvá nějakou dobu

**7)** Vytvořit místa pro registraci nových uživatelů

	V administraci spustit SpawnMap a vybrat místa pro nové uživatele.

**8)** Teď by měly Towns fungovat (např. na: http://localhost/www/small/)



* * *
### Autoři

**[PH] Pavel Hejný:** https://www.facebook.com/hejny
**[DH] David Hrůša:** https://www.facebook.com/dhrusa
**Přemysl Černý:** https://www.facebook.com/longhorn86
**[MH] Marek Hám:** https://www.facebook.com/marek.ham

Další se připište!

* * *
### Struktura Databáze

Je definovaná v core/create.sql. V Towns4Admin je automatický nástroj pro její aktualizaci. Tzn. strukturu změníte v souboru a podle něj bude automaticky přetvořena tabulka v databázi.

* * *
### Commity

Každý commit by měl mít označení projektu + autora např.: [WorldLayer][PH] Změny v minimenu


* * *
### Soubory

Všechny soubory + databáze je v UTF-8 a jako oddělovač řádků používat \n

Psát poznámky a dokumentovat pomocí PhpDocumentator