# AGENT.md — MunichTech EXPO 2026

## 1. PROJECT OVERVIEW

### 1.1 Architecture

| Layer | Technology | Version |
|-------|-----------|---------|
| Framework | Laravel | 11.x (PHP ^8.2) |
| Frontend | Blade + Bootstrap | 5.3 |
| CSS Preprocessing | Vite + Tailwind (optional) | - |
| Database | SQLite (dev) / PostgreSQL (prod) | - |
| OAuth | Laravel Socialite | ^5.16 |
| ORM | Eloquent | - |
| Session Store | `database` driver | - |
| Queue | `database` driver | - |
| Cache | `database` driver | - |

### 1.2 Directory Layout

```
munichtech-project/           ← All commands run from HERE
├── app/
│   ├── Http/
│   │   ├── Controllers/      ← Admin, Auth, Collaboration, EventRegistration, Project, Search
│   │   └── Middleware/
│   │       └── AuthAdmin.php ← Admin gate (checks is_admin boolean)
│   ├── Models/
│   │   ├── User.php          ← Contains ROLES[] and MATCH_ROLE_MAP[]
│   │   ├── Project.php       ← Dual status: status + admin_status
│   │   ├── ProjectMember.php ← Pivot with role enum
│   │   ├── CollaborationRequest.php
│   │   ├── ProjectMilestone.php
│   │   ├── ProjectTask.php
│   │   ├── AuditLog.php
│   │   ├── Notification.php
│   │   └── EventRegistration.php
│   └── Support/
│       └── PhoneCountryPrefixes.php  ← Used in registration form
├── bootstrap/
│   └── app.php               ← Middleware alias 'admin' → AuthAdmin
├── database/
│   └── migrations/           ← 0001_* (Laravel defaults) + 2026_05_20_* (app schema)
├── resources/
│   └── views/
│       └── layouts/
│           └── app.blade.php ← Master layout with design tokens
└── routes/
    └── web.php               ← All application routes (single file)
```

### 1.3 Database Schema (Critical Enums & Constraints)

| Table | Key Column | Enum Values / Constraints |
|-------|-----------|--------------------------|
| `users` | `role` | `Startup, Investor, Service Provider, Company, Attendee, Hackathon Participant` |
| `users` | `is_admin` | `boolean`, default `false` |
| `users` | `is_active` | `boolean`, default `true` |
| `users` | `google_id` | `nullable`, `unique` |
| `collaboration_requests` | `status` | `pending, accepted, rejected, cancelled` |
| `collaboration_requests` | `(sender_id, receiver_id)` | `UNIQUE` constraint — one request per pair |
| `projects` | `status` | `planning, active, paused, completed` |
| `projects` | `admin_status` | `active, inactive, suspended` |
| `projects` | `progress` | `integer unsigned`, default `0` |
| `project_members` | `role` | `owner, lead, contributor, viewer` |
| `project_members` | `(project_id, user_id)` | `UNIQUE` constraint — one membership per user per project |
| `project_milestones` | `status` | `pending, in_progress, completed` |
| `project_tasks` | `status` | `todo, in_progress, review, done` |

### 1.4 Model Relationships

```
User ──hasMany──> EventRegistration, CollaborationRequest(as sender/receiver), Project(as owner)
User ──belongsToMany──> Project (via project_members pivot with 'role')
Project ──belongsTo──> User(owner), CollaborationRequest
Project ──hasMany──> ProjectMilestone, ProjectTask
ProjectMilestone ──hasMany──> ProjectTask
CollaborationRequest ──belongsTo──> User(sender, receiver)
AuditLog ──belongsTo──> User (nullable, set null on delete)
```

---

## 2. CORE OPERATIONAL COMMANDS

### 2.1 Local Development (First Time)

```bash
cd munichtech-project
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan serve
```

### 2.2 Local Development (Daily)

```bash
cd munichtech-project
php artisan serve                          # http://127.0.0.1:8000
```

### 2.3 Safe Migration Commands

```bash
# LOCAL: Destructive reset with seeders
php artisan migrate:fresh --seed

# PRODUCTION (Render): Non-destructive, applies only pending migrations
php artisan migrate --force

# PRODUCTION (Render): After env variable changes
php artisan config:clear
php artisan route:cache
php artisan view:cache
```

### 2.4 Cache Management

```bash
php artisan config:clear                   # After any .env change
php artisan cache:clear                    # Clear application cache
php artisan route:clear                    # Clear route cache
php artisan view:clear                     # Clear compiled Blade views
```

### 2.5 Testing / Seeding

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@munichtech.test` | `SecurePass123!` |
| Startup | `valentina@innovabiz.test` | `Password123!` |
| Investor | `marcelo@capitalvc.test` | `Password123!` |

---

## 3. STRICT GUARDRAILS FOR AGENTS

### 3.1 Infrastructure Isolation (Render vs Local)

- **NEVER** run `migrate:fresh --seed` on Render/production. This drops all tables.
- **NEVER** commit `.env` or any file containing `GOOGLE_CLIENT_SECRET`.
- The `.env.example` uses `DB_CONNECTION=sqlite`. For Render production, override to `postgresql` via the Render dashboard environment variables.
- The `railway.json` and `Dockerfile` exist but **Render is the primary production target** as documented in README.

### 3.2 Google OAuth 2.0 Security

- The OAuth credentials are **environment-governed only**. If `GOOGLE_CLIENT_ID` or `GOOGLE_CLIENT_SECRET` are missing, the login page shows a configuration error — the app does not crash.
- `GOOGLE_REDIRECT_URI` must match **exactly** (including scheme and path) the URI registered in Google Cloud Console.
- Local redirect: `http://127.0.0.1:8000/auth/google/callback`
- Production redirect: `https://your-app.onrender.com/auth/google/callback`

### 3.3 PATCH Routes & CSRF

- Status updates for milestones and tasks use **PATCH**, not POST.
- Every PATCH form **must** include `@csrf` and `@method('PATCH')` Blade directives.
- Routes:
  - `PATCH /milestones/{milestone}/status` → `ProjectController@updateMilestoneStatus`
  - `PATCH /tasks/{task}/status` → `ProjectController@updateTaskStatus`
- These endpoints redirect after update — there is **no JSON API**. Do not add `Accept: application/json` expectations.

### 3.4 Schema Mutation Rules

- **NEVER** modify an existing migration file that has already been committed. Create a new migration with `php artisan make:migration`.
- The migration prefix convention is `2026_05_20_00XXXX` for app-specific tables.
- Laravel default migrations (`0001_01_01_000000`–`000002`) provide `users`, `cache`, `jobs`, and `sessions` tables. Do not remove or alter these.
- The `sessions` table is **required** because `SESSION_DRIVER=database` in `.env.example`.

### 3.5 AuthAdmin Middleware (CRITICAL — DO NOT BREAK)

- The middleware class is `App\Http\Middleware\AuthAdmin`.
- It is registered as alias `admin` in `bootstrap/app.php` via `$middleware->alias([...])`.
- **NEVER** change the alias name from `'admin'` — all admin routes in `web.php` depend on `->middleware('admin')`.
- The middleware checks: `$request->user()->is_admin === true`. Missing or false results in `abort(403, 'Access restricted to administrators.')`.
- Self-deactivation protection: `AdminController@toggleUserActive` prevents an admin from deactivating their own account (`auth()->id()` check).

### 3.6 Project Membership Access Control (CRITICAL)

- `ProjectController@show` enforces membership checks: the authenticated user must be either the `owner_id` or exist in `project_members` for that project.
- Violation returns `abort(403)`.
- **NEVER** bypass this check in the controller. If adding new project routes, apply the same authorization logic.
- The `project_members` table has a `UNIQUE(project_id, user_id)` constraint at the database level. Attempting to insert duplicate memberships will throw a SQL exception.

### 3.7 Collaboration Request Uniqueness (CRITICAL)

- `collaboration_requests` has `UNIQUE(sender_id, receiver_id)`. A user cannot send multiple requests to the same target.
- Status flow: `pending` → `accepted`/`rejected` (via `respond`) → project creation. No reverse transitions are implemented.
- Accepted collaborations auto-populate the **Create Project** button linking to `/projects/create?collaboration_id=X`.

### 3.8 Dual Project Status System (CRITICAL)

- `projects.status` = workflow state (`planning, active, paused, completed`) — managed by project members.
- `projects.admin_status` = moderation state (`active, inactive, suspended`) — managed by admins only via `AdminController@updateProjectAdminStatus`.
- **These are independent concepts.** Do not conflate them. Admin actions target `admin_status`; user actions target `status`.

### 3.9 Audit Logging (CRITICAL)

- `AdminController` logs all administrative mutations via `AuditLog::create([...])` with IP address and User-Agent.
- The `audit_logs` table stores: `user_id` (nullable, `ON DELETE SET NULL`), `action`, `ip_address`, `user_agent`, `details`.
- **Any new admin action must include a corresponding audit log entry.**

### 3.10 Session & Queue Configuration (CRITICAL)

- `SESSION_DRIVER=database` requires the `sessions` table (created by `0001_01_01_000000_create_users_table.php`).
- `QUEUE_CONNECTION=database` requires the `jobs` table (created by `0001_01_01_000002_create_jobs_table.php`).
- `CACHE_STORE=database` requires the `cache` table (created by `0001_01_01_000001_create_cache_table.php`).
- **If any of these tables are missing, the application will fail on first request.** Do not change these drivers in `.env` without ensuring the corresponding tables exist.

### 3.11 Forced HTTPS

- `routes/web.php` contains: `if (app()->environment('production')) { URL::forceScheme('https'); }`
- **Do not remove this.** Google OAuth and all production assets depend on HTTPS.

### 3.12 Role Constants & Matchmaking

- `User::ROLES` is a hardcoded array of 6 role strings. **Do not add values to this array without a corresponding migration** to update existing database records.
- `User::MATCH_ROLE_MAP` defines rule-based matchmaking (complementary roles + shared interests). This is a **static mapping**, not a database table. Modifying it changes matchmaking behavior globally.
- The `findMatchmakingSuggestions()` static method on `User` queries against these constants.

---

## 4. CODE STYLE & CONVENTIONS

### 4.1 Controllers

- All controllers extend `App\Http\Controllers\Controller`.
- Use `$request->validate([...])` for every write operation. No raw validation logic outside `validate()` calls.
- Use Eloquent model binding in method signatures where possible: `public function show(Project $project)`.
- Access control uses explicit `abort(403)` for unauthorized resource access — do not rely solely on middleware when resource-level authorization is needed.
- Admin methods must call `$this->logAction($action, $details)` after successful mutation.

### 4.2 Validation Rules

- Registration: `name` (required, max:255), `email` (required, email, unique:users), `password` (required, min:8, confirmed), `role` (required, in:ROLES).
- Phone: `phone_prefix` (nullable, max:6) + `phone_number` (nullable, max:20) are concatenated with a space separator before storage in `users.phone`.
- Collaboration request: `message` minimum 20 characters.
- Event registration: ticket types are `free`, `startup`, `investor`, `company`, `hackathon`.

### 4.3 XSS & Output Sanitization

- **Always** use `{{ $variable }}` (escaped) in Blade. Never use `{!! !!}` (raw) for user-generated content.
- The design system tokens are defined inline in `resources/views/layouts/app.blade.php`. Do not extract them to external CSS files unless Vite compilation is confirmed working.

### 4.4 Eloquent Patterns

- Use `HasFactory` on all models.
- Use `$fillable` arrays explicitly — no `Guarded` models.
- Use relationship methods consistently:
  - `belongsToMany` with `->withPivot('role')->withTimestamps()` for project membership.
  - `constrained()->onDelete('cascade')` or `nullOnDelete()` on all foreign keys in migrations.
- Passwords are hashed automatically via the `hashed` cast in `User::$casts`.

### 4.5 Route Conventions

- Named routes use dot notation: `projects.show`, `admin.dashboard`, `milestones.updateStatus`.
- Admin routes are grouped under `Route::prefix('admin')->name('admin.')->middleware('admin')`.
- Auth routes use the `guest` middleware for registration/login and `auth` middleware for all authenticated endpoints.
- Logout is a `POST` route (not GET) to prevent CSRF attacks via link prefetching.

---

## 5. CRITICAL FILES REFERENCE

| File | Purpose | Mutation Risk |
|------|---------|--------------|
| `bootstrap/app.php` | Middleware aliases, app config | **HIGH** — changing `'admin'` alias breaks all admin routes |
| `routes/web.php` | All HTTP routes | **HIGH** — PATCH routes must keep CSRF |
| `app/Http/Middleware/AuthAdmin.php` | Admin gate | **HIGH** — any logic change affects entire admin panel |
| `app/Models/User.php` | ROLES, MATCH_ROLE_MAP, matchmaking | **HIGH** — constants used across validations and queries |
| `database/migrations/0001_01_01_000000_*` | users, sessions, password_resets | **CRITICAL** — sessions table required for SESSION_DRIVER=database |
| `database/migrations/2026_05_20_000007_*` | project_members | **HIGH** — UNIQUE constraint prevents duplicate memberships |
| `app/Http/Controllers/ProjectController.php` | Project CRUD + membership enforcement | **HIGH** — bypassing membership checks exposes private projects |
| `app/Http/Controllers/AdminController.php` | Admin actions + audit logging | **MEDIUM** — missing logAction calls break audit trail |

---

## 6. KNOWN ARCHITECTURAL DECISIONS

1. **Status updates via full page redirect**, not AJAX/fetch. Keeps CSRF simple; no API layer exists.
2. **Matchmaking is rule-based** using `MATCH_ROLE_MAP` + shared interests — no external AI API.
3. **SQLite for development** — change `DB_CONNECTION` only; schema is portable to PostgreSQL/MySQL.
4. **Tags stored as comma-separated string** in `projects.tags`, parsed via `Project::getTags()`.
5. **Progress stored as integer** (0–100) on `projects`, manually managed or derived.
6. **Optional Google OAuth** — app works entirely with email/password if OAuth is unconfigured.
