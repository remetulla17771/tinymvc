# TinyMVC

Мини-фреймворк на PHP в стиле Yii2 (упрощённый MVC): `web/index.php → Router → Controller → View/Layout`, простые компоненты из конфига, ActiveRecord/Query, i18n, ассеты, обработка ошибок и логирование.

> Репозиторий: `remetulla17771/tinymvc`  
> Лицензия: Apache 2.0 (см. `LICENSE`)

---

## Возможности

- **Front Controller**: единая точка входа `web/index.php`.
- **Routing**: `/{controller}/{action}` + параметры через `$_GET` (Reflection).
- **Modules**: префикс в URL `/{module}/{controller}/{action}` (пример: `/admin/site/index`).
- **MVC**: контроллеры в `app/controllers`, view в `views/<controller>`, layout в `views/layouts`.
- **Компоненты** (мини DI-контейнер): создаются из `app/config/web.php` и доступны как свойства `$this->user`, `$this->urlManager`, `$this->lang`, `$this->response`, и т.д.
- **DB**: PDO singleton (`app/Db.php`).
- **ActiveRecord**: базовый CRUD + `Query` (`where()`, `one()`, `all()`, `limit/offset/orderBy/count`).
- **Response**: HTML/JSON/redirect, с `send()`.
- **i18n**: переводы в `app/messages/<lang>/<category>.php` (`ru`, `kk`, `pr`).
- **Ошибки**: `ErrorHandler` ловит exception/error/fatal, пишет логи в `runtime/logs`.
- **Console Gii**: генераторы кода через `bin/console.php`.

---

## Требования

- PHP **7.4+**
- Apache + `mod_rewrite` (или nginx с rewrite на `web/index.php`)
- MySQL/MariaDB (если используешь модели с БД)
- Composer (рекомендуется)

---

## Установка (Composer)

В корне проекта:

```bash
composer install
composer dump-autoload -o
```

Убедись, что в `web/index.php` и `bin/console.php` подключён autoload:

```php
require __DIR__ . '/../vendor/autoload.php';
```

---

## Структура проекта

```
app/
  App.php
  Router.php
  Controller.php
  Request.php
  Response.php
  UrlManager.php
  Db.php
  ActiveRecord.php
  Query.php
  Migration.php
  ErrorHandler.php
  AuthService.php
  config/
    web.php
    db.php
  controllers/
    SiteController.php
    ErrorController.php
  console/
    ConsoleApplication.php
    CommandInterface.php
    Input.php
    Output.php
    HelpCommand.php
    MakeControllerCommand.php
    MakeModelCommand.php
    MakeCrudCommand.php
    MakeMigrationCommand.php
    MigrateCommand.php
    MakeModuleCommand.php
  helpers/
    I18n.php
    Html.php
    ActiveForm.php
    GridView.php
    DetailView.php
    LinkPager.php
    Pagination.php
    Alert.php
    Modal.php
    Session.php
    MetaTagManager.php
    NavBar.php
  assets/
    AppAsset.php
    BootstrapAsset.php
    FontAwesomeAsset.php

modules/
  admin/
    Module.php
    controllers/
      SiteController.php
    views/
      layouts/
        main.php
      site/
        index.php

views/
  layouts/
    main.php
    error.php
  site/
    index.php
    view.php
  error/
    error.php
    trace.php

web/
  index.php
  .htaccess
  assets/...

migrations/

runtime/
  logs/
```

---

## Быстрый старт (OpenServer / Apache)

1) Размести проект в домене (например `tinymvc.loc`).
2) **DocumentRoot должен быть `.../tinymvc/web`** (важно).
3) Проверь, что `.htaccess` работает (mod_rewrite включён).

Открой:
- `http://tinymvc.loc/` → по умолчанию `site/index`

### `web/.htaccess`

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```

---

## Как работает запрос

1) `web/index.php`:
   - стартует session
   - регистрирует `ErrorHandler`
   - подключает autoload
   - создаёт `new app\App()` и вызывает `run()`

2) `App`:
   - создаёт `Request`, `Router`
   - грузит `app/config/web.php`
   - создаёт компоненты и кладёт их в `$this-><key>`

3) `Router::resolve()`:
   - определяет `controller/action` по сегментам URL
   - если первый сегмент совпадает с папкой в `modules/` — это **module**
   - вызывает `Controller::actionX(...)` через Reflection
   - если action вернул `Response` → вызывает `send()`

---

## Routing

Формат:

- `/{controller}/{action}`
- `/{module}/{controller}/{action}`
- параметры action берутся из `$_GET` по имени параметра

Примеры:

- `/site/view?id=5` → `SiteController::actionView($id)`
- `/admin/site/index` → `modules\admin\controllers\SiteController::actionIndex()`

---

## Контроллеры

Файл: `app/controllers/SiteController.php`  
Класс: `SiteController`  
Методы: `actionIndex()`, `actionView($id)`, и т.д.

Пример:

```php
namespace app\controllers;

use app\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', ['title' => 'Hello']);
    }

    public function actionView(int $id)
    {
        return $this->render('view', ['id' => $id]);
    }
}
```

---

## Views и Layouts

- View: `views/<controller>/<view>.php`
- Layout: `views/layouts/main.php` (по умолчанию)

Для модулей:
- View: `modules/<module>/views/<controller>/<view>.php`
- Layout: `modules/<module>/views/layouts/main.php`

Рендер из контроллера:
- `render($view, $params)` — view + layout
- `renderPartial($view, $params)` — только view

---

## Компоненты и конфиг

`app/config/web.php`:

```php
return [
  'components' => [
    'user' => ['class' => '\app\AuthService'],
    'urlManager' => ['class' => '\app\UrlManager'],
    'response' => ['class' => '\app\Response'],
    'lang' => ['class' => '\app\helpers\I18n'],
    'session' => ['class' => '\app\helpers\Session'],
  ],
];
```

---

## i18n (переводы)

Файлы: `app/messages/<lang>/<category>.php` (`ru`, `kk`, `pr`).

Использование:

```php
use app\App;

echo App::$app->t('app', 'Hello');
```

---

## База данных

### Настройка

`app/config/db.php`:

```php
return [
  'dsn' => 'mysql:host=localhost;dbname=auth;charset=utf8',
  'user' => 'root',
  'password' => 'root',
];
```

> Важно: не храни реальные пароли в git. Вынеси в `.env`/`db.local.php` и добавь в `.gitignore`.

---

## Console Gii (консольные генераторы)

Показать список команд:

```bash
php bin/console.php help
```

### make:controller

```bash
php bin/console.php make:controller Site
# или в модуль:
php bin/console.php make:controller Site --module=admin
```

### make:model

```bash
php bin/console.php make:model User --table=user
```

### make:crud

```bash
php bin/console.php make:crud User --table=user
# CRUD внутрь модуля:
php bin/console.php make:crud User --table=user --module=admin --force
```

Опции:
- `--table=...` — обязательно (если модель не умеет `tableName()`).
- `--controller=Имя` — имя контроллера (без `Controller`).
- `--modelNamespace=app\models` — где искать модель (по умолчанию `app\models`).
- `--module=admin` — генерирует **внутрь `modules/admin`** и строит ссылки `/admin/<controller>/<action>`.
- `--force` — перезаписать файлы.

> Важно: `make:model` и `make:crud` требуют рабочее подключение к БД (`Db::getInstance()`), иначе не смогут прочитать схему таблицы.

---

## Модули

### make:module

Создаёт модуль со стартовым контроллером/вьюшкой/лейаутом:

```bash
php bin/console.php make:module admin
```

Создаст:
- `modules/admin/Module.php`
- `modules/admin/controllers/SiteController.php`
- `modules/admin/views/layouts/main.php`
- `modules/admin/views/site/index.php`

Проверка:
- открой `/admin/site/index`

---

## Миграции

### make:migration

Создаёт файл миграции в папке `migrations/`:

```bash
php bin/console.php make:migration create_user_table
```

### migrate

Применяет/откатывает миграции. Состояние хранится в таблице `migration` (по умолчанию).

Применить все новые миграции:

```bash
php bin/console.php migrate
```

Откатить последнюю миграцию:

```bash
php bin/console.php migrate down 1
```

Опции:
- `--dir=migrations` — папка миграций
- `--table=migration` — имя таблицы для учёта применённых миграций

### Базовый класс Migration

Файл: `app/Migration.php`. В миграциях доступны хелперы:

- `createTable($table, $columns, $options)`
- `dropTable($table)`
- `addColumn($table, $column, $type)`
- `dropColumn($table, $column)`
- `createIndex($name, $table, $columns, $unique=false)`
- `dropIndex($name, $table)`

Типы:
- `$this->pk()`, `$this->int()`, `$this->bool()`, `$this->string($len)`, `$this->text()`, `$this->datetime()`, `$this->timestamp()`, `$this->decimal($p,$s)`
- модификаторы: `$this->notNull()`, `$this->defaultValue($v)`, `$this->defaultExpr('CURRENT_TIMESTAMP')`

---

## Безопасность

Сейчас в проекте может встречаться **plain-text** работа с паролями. Для реального проекта:
- используй `password_hash()` / `password_verify()`
- вынеси секреты (DB, токены) из репозитория
- исключи логи и конфиги из git

Рекомендуемый `.gitignore`:

```gitignore
/vendor/
runtime/logs/
.env
app/config/db.local.php
```

---

## Лицензия

Apache License 2.0 — см. файл `LICENSE`.
