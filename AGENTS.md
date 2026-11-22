# AGENTS.md — Lara (ILSCeV)

Laravel 8 app ("Lara") for club calendars and personnel scheduling. Domain/section names are German, but code, comments and identifiers must be English; PHP follows PSR-2, JS/HTML/CSS follow the Google style guides (`CONTRIBUTING.md`).

## Layout / entrypoints
- Real entrypoints: `routes/web.php` and `routes/api.php`. Controllers in `app/Http/Controllers`, models at the root of `app/` under namespace `Lara\`, Blade views in `resources/views`, frontend source in `resources/assets/{ts,sass}`.

## Frontend build
- `npm run dev` / `npm run prod` invoke plain **webpack** using `webpack.config.js`. `webpack.mix.js` is stale legacy config — editing it has no effect.
- `npm run prod` writes hashed bundles to `public/` plus `public/assets-manifest.json`. The committed `public/assets-manifest.php` just reads that JSON and is required by `public/index.php` **and by `tests/CreatesApplication.php`**, so a build must exist before PHP tests can bootstrap.

## PHP / tests
- PHP and Composer are not installed on this host; run these commands inside the devcontainer or the Docker image. MySQL/MariaDB is required (`DB_HOST=db` in `.env` matches the devcontainer service; CI uses `127.0.0.1`, database `lara`).
- Full verification, mirroring `.github/workflows/lara.yml`: `npm run prod` → `php artisan key:generate` → `php artisan migrate:fresh --seed` → `php artisan test`.
- Focused runs: `php artisan test --filter=ApplicationTest`, or `vendor/bin/phpunit --testsuite "Unit Tests"`.
- Most tests are not self-contained: `ApplicationTest` expects an already migrated/seeded DB; only `EventApiControllerTest` uses `RefreshDatabase`.
- CI runs PHP 8.0 while the Dockerfile builds PHP 8.5 — keep PHP syntax compatible with 8.0. Don't bump the Docker image to PHP 8.6 until imagick supports it (build fails on `XtOffsetOf`); `renovate.json` holds it back.
- `composer.lock` is gitignored, so CI resolves dependencies on every run. Laravel 8 is EOL, and Composer 2.11+ blocks packages with security advisories by default; `composer.json` sets `config.policy.advisories.block` to `false` so resolution still works. Remove that setting only after upgrading off Laravel 8.

## Seeding gotcha
- `db:seed` prefers `Database\Seeders\DatabaseSeeder` from `database/seeders/`, but Laravel falls back to the legacy global `DatabaseSeeder` in `database/seeds/` when that class is missing (the case on fresh checkouts, since `database/seeders/` is untracked). Seed order matters, and `--seed` wipes existing data.

## Operational notes
- Docker CMD runs `php artisan migrate --force` then `php artisan serve --host 0.0.0.0`; the devcontainer overrides the command with `sleep infinity` and uses a MariaDB 11.7 service.
- Custom commands (`lara:ldapsync`, `lara:clean-shifttypes`, …) live in `app/Console/Commands` and are scheduled in `app/Console/Kernel.php`.
- Generated-but-committed files: `_ide_helper.php` and `.phpstorm.meta.php` (composer `post-update-cmd`), `public/gitrevision.txt` (via `git-create-revisioninfo-hook.sh`, installed during the Docker build).
- Don't open PRs against `master`, `berta`, or `release/*`; target `develop` or a feature branch (`CONTRIBUTING.md`).
