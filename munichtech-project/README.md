# MunichTech EXPO

A professional event management, hackathon, and corporate–startup collaboration platform built with **Laravel 11**, **Blade**, **Bootstrap 5**, and **SQLite**. MunichTech EXPO connects innovators, investors, and enterprises through structured collaboration workflows, project delivery tooling, and an admin moderation layer.

---

## Key Features

### Google OAuth 2.0 Authentication

Fast, secure sign-in with Google via **Laravel Socialite**. Users can register or log in with one click while traditional email/password authentication remains available. Credentials are managed through environment variables and validated before redirecting to Google.

### Interactive Project Management

Dynamic project workspaces with **Bootstrap 5 modals** for creating milestones and tasks without leaving the project view. Team members can add milestones with target dates, assign tasks to collaborators, and organize work under a clear milestone hierarchy or as standalone tasks.

### Real-time Task Status Updates

Interactive status controls on the project detail page let authorized users update progress instantly:

| Entity | Allowed statuses |
|--------|------------------|
| **Milestones** | `pending`, `in_progress`, `completed` |
| **Tasks** | `todo`, `in_progress`, `review`, `done` |

Status changes are submitted via `PATCH` routes and reflected immediately after redirect, with optional one-click **Mark as done** actions for tasks.

### Internationalization & UI Polish

- **Phone prefix selector** on registration: countries sorted alphabetically by English name, with Unicode flag emojis (e.g. `🇩🇪 Germany (+49)`).
- **Sticky footer layout** using the Bootstrap 5 flexbox pattern (`min-vh-100`, `flex-grow-1`, `mt-auto`) so the footer stays anchored on short pages.
- **Remember me** support with Laravel’s persistent authentication cookie.
- **English-first UI** across views, flash messages, and documentation for international stakeholders.

### Additional Capabilities

- Collaboration requests with validated proposal messages (minimum 20 characters).
- AI-style matchmaking suggestions on the dashboard (rule-based, role + interests).
- Admin dashboard for user moderation, event registrations, and project `admin_status`.
- Audit logging for administrative actions.

---

## Installation and Setup Guide

### Prerequisites

- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 18 (optional, for Vite assets)
- PHP extensions: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`

### Local installation

```bash
# 1. Clone and enter the project directory
cd munichtech-project

# 2. Install PHP dependencies (includes Laravel Socialite)
composer install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Create SQLite database
touch database/database.sqlite

# 5. Run migrations and seeders
php artisan migrate:fresh --seed

# 6. Start development server
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`.

### Test credentials (seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@munichtech.test | SecurePass123! |
| Startup | valentina@innovabiz.test | Password123! |
| Investor | marcelo@capitalvc.test | Password123! |

### Google OAuth (local development)

Add the following to your `.env`:

```env
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

Then clear the configuration cache:

```bash
php artisan config:clear
```

Register the callback URI in [Google Cloud Console](https://console.cloud.google.com/) → **APIs & Services** → **Credentials** → your OAuth 2.0 Client ID → **Authorized redirect URIs**.

---

## Production Environment Configuration

When deploying to **Render** (or any production host), configure these environment variables in the service dashboard so Google OAuth works end-to-end.

| Variable | Description |
|----------|-------------|
| `GOOGLE_CLIENT_ID` | OAuth 2.0 Client ID from Google Cloud Console (Web application type). |
| `GOOGLE_CLIENT_SECRET` | Client secret paired with the Client ID. Keep this private; never commit it to version control. |
| `GOOGLE_REDIRECT_URI` | Full HTTPS callback URL registered in Google Console. Must match exactly, including path. |

**Example for a Render web service:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com

GOOGLE_CLIENT_ID=123456789-xxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxxxxxxxxxxxxxx
GOOGLE_REDIRECT_URI=https://your-app-name.onrender.com/auth/google/callback
```

### Production checklist

1. Set `APP_URL` to your public Render URL (HTTPS).
2. Add the same `GOOGLE_REDIRECT_URI` under **Authorized redirect URIs** in Google Cloud Console.
3. Use a persistent database (`postgresql` on Render is recommended over SQLite for multi-instance deployments).
4. Run `php artisan config:clear` after changing environment variables (or redeploy so Render reloads them).
5. Ensure `SESSION_DRIVER=database` (or `redis`) and that sessions/migrations have been applied.

---

## Post-Deployment Commands

After pushing an update to Render—or whenever schema changes are included in a release—run migrations from the **Render Shell** so milestone and task tables (and any new columns) are applied to the production database.

```bash
# Apply pending migrations (safe for production; does not drop data)
php artisan migrate --force
```

Optional follow-up commands:

```bash
# Clear cached config after env changes
php artisan config:clear

# Cache routes and views for performance (production only)
php artisan route:cache
php artisan view:cache
```

> **Note:** Use `php artisan migrate:fresh --seed` only in local or staging environments. It drops all tables and is destructive on production data.

If this is the **first deployment**, also ensure:

```bash
php artisan key:generate   # only if APP_KEY is not set in Render env
php artisan migrate --force
php artisan db:seed --force   # optional: demo data for staging demos
```

---

## System Architecture

### Database structure (2026 schema)

| Table | Key description |
|-------|-----------------|
| `users` | Profiles with role, interests, `google_id`, `is_admin`, `is_active` |
| `event_registrations` | Tickets: `free`, `startup`, `investor`, `company`, `hackathon` + `status` |
| `collaboration_requests` | Requests between users (`pending`, `accepted`, `rejected`, `cancelled`) |
| `projects` | Projects with workflow `status` and moderation `admin_status` |
| `project_members` | Pivot with roles: `owner`, `lead`, `contributor`, `viewer` |
| `project_milestones` | Milestones with `target_date` and `status` enum |
| `project_tasks` | Tasks with `assigned_to`, `status`, `priority` |
| `audit_logs` | Administrative action logging |
| `notifications` | In-app notification system |

### Models and key interactions

```
User ──hasMany──> EventRegistration, CollaborationRequest, Project
User ──belongsToMany──> Project (via project_members + role pivot)
Project ──hasMany──> ProjectMilestone, ProjectTask
ProjectMilestone ──hasMany──> ProjectTask
CollaborationRequest ──belongsTo──> User (sender/receiver)
```

### Collaboration flow

1. User A sends a request → `collaboration_requests.status = pending`
2. User B accepts → `status = accepted`
3. Either party sees the **Create Project** button → redirects to `/projects/create?collaboration_id=X`
4. On save, the project is created and members are assigned automatically

### Project status update routes

| Method | Route | Purpose |
|--------|-------|---------|
| `PATCH` | `/milestones/{milestone}/status` | Update milestone status |
| `PATCH` | `/tasks/{task}/status` | Update task status |
| `POST` | `/projects/{project}/milestones` | Create milestone |
| `POST` | `/projects/{project}/tasks` | Create task |

---

## Technology Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 11 (PHP 8.2+) |
| Database | SQLite (dev) / PostgreSQL recommended (production) |
| Frontend | Blade Templates + Bootstrap 5.3 |
| OAuth authentication | Laravel Socialite (Google) |
| ORM | Eloquent |
| Assets | Vite (optional) |

---

## Security Protocols and Measures

| Measure | Implementation |
|---------|----------------|
| **Admin middleware** | `AuthAdmin` registered as `admin` alias in `bootstrap/app.php`, protects `/admin/*` routes |
| **CSRF** | Tokens on all POST/PATCH/DELETE forms |
| **Input validation** | `$request->validate()` in all controllers |
| **Access control** | `abort(403)` on projects without membership; inactive accounts blocked at login |
| **Sanitization** | Eloquent ORM (no raw SQL); automatic escaping in Blade `{{ }}` |
| **Audit trail** | `audit_logs` records admin actions with IP and User-Agent |
| **Forced HTTPS** | In `production` environment via `URL::forceScheme('https')` |
| **Passwords** | Automatic bcrypt hash via `hashed` cast |

---

## Development Assumptions and Design Decisions

1. **Unified migrations**: Duplicate migrations `0001_01_01_000003–000009` were removed; schema `2026_05_20_*` is the source of truth.
2. **Dual project status**: `status` (team workflow) and `admin_status` (moderation: active/inactive/suspended) are separate concepts.
3. **Simulated matchmaking**: Rule-based algorithm using complementary roles + shared interests (no external AI API).
4. **SQLite in development**: Ideal for demos and lightweight deployments; migratable to PostgreSQL/MySQL by changing `DB_CONNECTION`.
5. **Optional OAuth**: The app works without Google OAuth; the login button shows a configuration error if credentials are missing.
6. **Status updates via full page redirect**: Keeps implementation simple and CSRF-safe without a separate JavaScript API layer.

---

## Scalability Strategy and Future Improvements

| Area | Proposal |
|------|----------|
| Database | Migrate to PostgreSQL with read replicas on Render |
| Queues | Redis + Laravel Horizon for notifications and emails |
| Real AI | Integrate OpenAI/Anthropic API for semantic matchmaking |
| Real-time | Laravel Reverb / Pusher for live status updates without page reload |
| REST API | Laravel Sanctum for mobile event app |
| Tests | PHPUnit feature tests for auth, collaboration, milestones, and admin flows |
| CI/CD | GitHub Actions with `migrate --force` in staging pipeline |

---

## Main Route Structure

| Route | Description |
|-------|-------------|
| `/` | Corporate landing page |
| `/register`, `/login` | Authentication + Google OAuth |
| `/auth/google`, `/auth/google/callback` | OAuth redirect and callback |
| `/dashboard` | User dashboard with matchmaking suggestions |
| `/collaborations` | Collaboration request management |
| `/projects`, `/projects/{project}` | Project list and detail (milestones/tasks) |
| `/events` | Event registrations |
| `/admin` | CRUD dashboard (administrators only) |

---

## AI Tools Used in Development

This deliverable was accelerated with AI assistance in the following areas:

| Tool | Usage |
|------|-------|
| **Cursor (Composer Agent)** | Cross-workspace analysis, schema/view/controller inconsistency detection, full patch generation |
| **GitHub Copilot** | Blade boilerplate and Laravel validation autocompletion |
| **Generative AI** | Landing page design, README documentation, and rule-based matchmaking algorithm |

**Transparency**: All business logic, migrations, security middleware, and CRUD flows were manually reviewed and aligned with project requirements. AI tools acted as accelerators, not substitutes for architectural decisions.

---

## License

MIT — Academic project / MunichTech EXPO 2026 demonstration.
