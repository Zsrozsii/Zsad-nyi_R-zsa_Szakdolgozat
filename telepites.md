# 1. Előfeltételek

A demo környezet előállításához a következő előfeltételeknek kell teljesülniük:
- Windows Subsystem for Linux (WSL) telepítve (Windows esetén)
- Ubuntu 20.04 (vagy újabb) WSL disztibúció telepítve, konfigurálva 
- Docker telepítve
- Docker WSL integráció az Ubuntu disztribúcióra be legyen állítva

# 2. Telepítés

0. Windows esetén léépjünk be az Ubuntu disztibúció termináljába.

1. A projektet másoljuk át egy tetszőleges mappába. Windows esetén az Ubuntu disztibúció fájlszerkezetén belülre.

2. Lépjünk a projekt gyükérmappájába, majd futtassuk le a `docker-compose up` parancsot. Ez eltarthat néhány percig.

3. Miután létrejött a projekt környezet, lépjünk be a Laravel-t futtató docker konténerbe. Ehhez futtassuk le a következő parancsot: `docker exec -it laravel-app bash`

4. A konténeren belül pedig a következő két parancsot kell lefuttatnunk egymás után: `php artisan migrate` és `php artisan db:seed`

Ezzel előállt a környezet, a weboldal megtekinthető a http://localhost:3000 elérési úton.