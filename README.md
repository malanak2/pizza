# Pizzerie — dokumentace

## Stručný přehled
Jednoduchá PHP aplikace pro stavbu a objednávku pizzy. Umožňuje:
- výběr základu a více ingrediencí,
- uložení košíku do sessionu,
- úpravu položek (odebrání pizzy nebo toppingu),
- odeslání objednávky s validací na serveru.

### 1) Teorie: předávání hodnot HTTP requesty a superglobální pole (web-článek)
Cíl: vysvětlit, jak aplikace předává data mezi klientem a serverem a jak s nimi PHP pracuje.

1. Co je HTTP request
- HTTP request je zpráva poslaná z klienta (prohlížeče) serveru. Obsahuje metodu (GET, POST, PUT, DELETE…), URL, hlavičky a volitelně tělo (body).
- Formuláře v HTML výchozně používají metody GET nebo POST. GET zakládá data do query stringu v URL (např. `?q=vyhledat`), POST posílá data v těle requestu.

2. Rozdíl GET vs POST
- GET: vhodný pro čtení a idempotentní dotazy; data jsou v URL; omezená délka; nejsou vhodná pro citlivé údaje.
- POST: vhodný pro zasílání formulářů, ukládání nebo změny stavu; data nejsou v URL; lze posílat větší množství dat.

3. PHP superglobální pole
- `$_GET` — obsahuje data z query stringu (GET).
- `$_POST` — obsahuje data odeslaná metodou POST.
- `$_SESSION` — per-uživatelská session; data žijí mezi requesty (session ID v cookies).

Praktický postup v projektu:
- Stav košíku je uložen v `$_SESSION['cart']`. Session se zahajuje `session_start()` a do session se zapisuje instance třídy `Pizza`.
- Formuláře (přidání pizzy, dokončení objednávky) odesílají data přes POST (např. `Order.php` čte `$_POST['customer_name']` apod.).
- V `index.php` se používá skrytý `input` s názvem `toppingsdata`, kam JavaScript zapíše JSON řetězec vybraných toppingů před odesláním. Na serveru se tento JSON rozparsuje (v implementaci musí být dekódován pomocí `json_decode()`).

4. Bezpečnost a sanitizace
- Vstupy z `$_POST`/`$_GET` jsou nikdy nedůvěryhodné. Před výpisem do HTML používat `htmlspecialchars()` k ochraně proti XSS.
- Pro uchování stavu použit `$_SESSION` (server-side). Dávejte pozor na session fixation / cookie nastavení při produkci.
- Při dalším rozvoji: CSRF tokeny pro formuláře, validace dat na serveru, omezení velikosti nahrávaných dat.

Příklady (ukázky):
```php
// čtení POST hodnoty s trimnutím a výpisem bezpečně
$name = trim($_POST['customer_name'] ?? '');
echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
```

### 2) Teorie: regulární výrazy
Regulární výrazy (regex) jsou způsob jak popsat vzor v řetězci. V PHP se nejčastěji používají funkce `preg_match`, `preg_replace` atd.

Základ:
- `^` začátek řetězce, `$` konec.
- `[]` množina, např. `[0-9A-Za-z]` znamená číslice a písmena.
- `{m,n}` od m do n opakování.
- `\s` whitespace znaky, `\d` číslice, `\w` alfanumerické + `_`.

Regexy v projektu:
- Poštovní kód: `/^[0-9A-Za-z \-]{3,10}$/` — povoluje 3–10 znaků: číslice, písmena, mezera a pomlčka.
- Telefon: `/^[0-9+\-() ]{6,20}$/` — povoluje číslice, `+`, `-`, závorky a mezery, 6–20 znaků.

Příklad validace:
```php
if (!preg_match('/^[0-9A-Za-z \-]{3,10}$/', $postal)) {
    $errors[] = 'Postal code is invalid';
}
```

## Popis a zdůvodnění vlastního řešení

1. Architektura a datový model
- Košík je session-based: zadání
- Třída `Pizza` drží `base` a pole `toppings`. `Enums.php` poskytuje typ-safe výčty pro `Base` a `Topping`.
- `index.php` slouží jako uživatelské rozhraní pro stavbu pizzy s JS pro výběr toppingů. Toppings se ukládají jako JSON do skrytého pole `toppingsdata` před odesláním, server poté provede `json_decode(...)` a vytvoří `Pizza` objekt.
- `Cart.php` zobrazuje obsah `$_SESSION['cart']` a nabízí odstranění položky nebo ingredience přes POST volání na `RemoveTopping.php`.
- `Order.php` provádí server-side validaci polí (jméno, e-mail, adresa, postal, volitelně telefon), ukládá chyby do `$_SESSION['order_errors']` a při chybách přesměruje zpět do košíku tak, aby byly zobrazeny (flash zprávy).
- Po úspěšném odeslání se košík vymaže a nastaví se `$_SESSION['order_success']`.

2. Bezpečnostní
- Výstupy jsou chráněny `htmlspecialchars()`. To brání XSS.

## Testování
- Testovat formuláře s validními i nevalidními daty, různé kombinace toppingů, mazání položek.
- Ověřit existenci `$_SESSION['cart']` a správné chování flash zpráv.

## Shrnutí
Tento projekt demonstruje základní principy webového vývoje v PHP: přenos dat mezi klientem a serverem pomocí GET/POST, uložení stavu v session, použití regulárních výrazů pro validaci a základní bezpečnostní opatření (escaping). Architektura je jednoduchá a vhodná pro rozšíření — doporučeno doplnit CSRF, spočítat ceny a odstranit drobné UX/HTML chyby.

---

## Zdroje
https://www.w3schools.com/php/
https://regex101.com/