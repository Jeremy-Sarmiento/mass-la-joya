# Development

The project is a PHP 8 MVC application (no build step, no Composer).

Start the development server from the project root:

```
php -S localhost:8000 -t .
```

Then open <http://localhost:8000/>. The app also works from a subdirectory
(e.g. `http://localhost/mass-la-joya/index.php`) because all internal URLs are
derived from `index.php`'s location.

## Tests

```
php tests/smoke.php
find app config tests index.php -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Conventions

- Namespace `App\` → `app/` (PSR-4 autoloader in `app/bootstrap.php`).
- Flow: `index.php` → `App\Core\Router` → controller → model → view.
- Add a page route in `index.php`, a controller in `app/Controllers`, a model in
  `app/Models` and a view in `app/Views`.
- Escape every dynamic value in views with `e()`. Use `money()` for prices,
  `url()` for internal links, `csrf_field()` in forms.
- Catalog data: `app/Data/*.json`. User records: `storage/*.php` via
  `App\Services\Storage` (JSON + file lock + PHP guard).
- Never trust prices or totals from the browser: recalculate them in models.
- API endpoints answer JSON to `fetch()` and redirect with a flash message for
  plain HTML form posts.

## Documentation

- PHP manual: https://www.php.net/manual/es/
- PHP sessions: https://www.php.net/manual/es/book.session.php
- Apache `DirectoryIndex`: https://httpd.apache.org/docs/current/mod/mod_dir.html
- Bootstrap 5.3: https://getbootstrap.com/docs/5.3/
