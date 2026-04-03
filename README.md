# Blogy

- PHP 8.1+
- Smarty
- MySQL
- PDO
- Phinx
- Docker

## Функционал

### Главная (`/`)
- Выводит только категории, в которых есть статьи.
- Для каждой категории показывает 3 последних поста по дате публикации.
- Для каждой категории есть кнопка `Все статьи`.

### Страница категории (`/category/{id}`)
- Название, описание категории и список статей.
- Сортировка:
  - по дате публикации
  - по количеству просмотров
- Пагинация.

### Страница статьи (`/post/{id}`)
- Полная информация по статье.
- Увеличение счетчика просмотров при открытии.
- Блок из 3 похожих статей.

## Архитектура

Точка входа: `public/index.php`

Структура слоев:

- `src/Domain`
  - `Entity` — доменные сущности (`Category`, `Post`)
  - `Repository` — интерфейсы репозиториев
- `src/Application`
  - use-case директории (`GetHomePage`, `GetCategoryPage`, `GetPostPage`)
  - в каждом use-case:
    - `DTO/RequestDTO`
    - `DTO/ResponseDTO`
    - `Get...Service`
- `src/Infrastructure`
  - `Persistence/MySql` — реализация репозиториев на PDO
  - `Database` — фабрика подключения PDO
  - `Http` — инфраструктурные HTTP-компоненты (`Request`, `Router`)
  - `Templating` — фабрика Smarty
  - `Config` — загрузка env
  - `Bootstrap` — композиция приложения (DI/роутинг)
- `src/Presentation`
  - `Http/Controller` — тонкие контроллеры

Дополнительно:

- `templates/` — Smarty-шаблоны
- `assets/` — стили (`scss` и готовый `css`)
- `db/migrations` — миграции Phinx
  - отдельная миграция на каждую таблицу (`categories`, `posts`, `post_categories`)
- `db/seeds` — сидеры Phinx

## Быстрый старт

1. Создать env:

```bash
cp .env.example .env
```

2. Поднять окружение:

```bash
docker compose up -d --build
```

3. Установить зависимости:

```bash
docker compose exec app composer install
```

4. Применить миграции:

```bash
docker compose exec app vendor/bin/phinx migrate -e development
```

5. Заполнить базу тестовыми данными:

```bash
docker compose exec app vendor/bin/phinx seed:run -e development -s BlogSeeder
```

6. Открыть в браузере:

- [http://localhost:8080](http://localhost:8080)

## Полезные команды

```bash
docker compose exec app vendor/bin/phinx status -e development
docker compose exec app vendor/bin/phinx rollback -e development
```
