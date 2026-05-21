# MunichTech EXPO 2026 — Premium Venture Capital & Startup Platform

**The elite digital nerve center for Munich’s deep-tech economy.**

MunichTech EXPO 2026 is a **bespoke venture capital and deep-tech incubation management hub**—engineered for high-profile funds, growth-stage startups, and corporate innovation leaders operating across Bavaria’s premier technology corridor. Built on **Laravel 11**, **Blade**, **Bootstrap 5**, and **SQLite**, the platform unifies delegate onboarding, structured collaboration workflows, interactive project delivery, and executive-grade moderation into a single, meticulously crafted operational environment.

When investors, incubator directors, or strategic partners open this repository, they encounter not a prototype—but a **production-ready digital ecosystem** aligned with the same warm-neutral, premium interface language trusted by tier-one SaaS products (Stripe Dashboard, Vercel, Money20/20).

---

## Executive Overview

| Dimension | Capability |
|-----------|------------|
| **Identity & Access** | Enterprise Google OAuth 2.0 + hardened email/password authentication |
| **Deal Flow & Collaboration** | Validated partnership requests with role-aware project spin-up |
| **Delivery Workspace** | Milestone hierarchies, task assignment, and real-time status routing |
| **Capital Intelligence** | Rule-based matchmaking engine for investor–startup alignment |
| **Governance** | Admin moderation layer with full audit trail and registration oversight |

---

## 💎 Visual Design System

The application interface has been architected as a **dark, warm-neutral corporate experience**—calm, expensive, and deliberately restrained. No neon accents, no crypto-style glow, and no glassmorphism; surfaces are **solid, layered, and precision-crafted**.

### Design tokens & typography

| Layer | Specification |
|-------|----------------|
| **Surfaces** | `--bg-void`, `--bg-base`, `--bg-raised`, `--bg-overlay` — warm charcoal palette (not cold blue-black) |
| **Borders** | Subtle rgba whites (`--border-subtle` → `--border-strong`) for depth without visual noise |
| **Accent** | Desaturated warm coral (`--accent: #c8614a`) — MunichTech signature hue |
| **Semantics** | `--success`, `--warning`, `--danger`, `--info` for status, badges, and alerts |
| **Typography** | **Syne** (800) for commanding headings · **Inter** (300–600) for interface copy at 14px base |

### Immersive interface modules

- **Corporate landing experience** — Executive hero, innovation track cards, hackathon module, and collaboration workflow narrative on `welcome.blade.php`.
- **Live Sponsor Marquee** — Infinite horizontal scroll loop beneath the hero, showcasing monochromatic wordmarks for Munich tech leaders (**BMW**, **Siemens**, **Allianz**, **SAP**, **Infineon**, **Google Munich**) under *“Trusted by Tech Leaders & Global Investors.”*
- **About & Partner sections** — Dual-column 2026 program briefing (AI, Deep Tech, Venture Capital tracks) and a premium *Partner with MunichTech Expo 2026* sponsorship CTA block.
- **Structured corporate footer** — Four-column layout (brand, legal, company, Messe München location) with monochromatic social icons (LinkedIn, X, YouTube) and rights line.
- **Advanced status interaction controls** — Context-aware `<select>` and badge styling on project workspaces for instant milestone/task state transitions without leaving the detail view.

All styling is centralized in `resources/views/layouts/app.blade.php` with token-driven overrides—ensuring **visual parity across every authenticated and public view**.

---

## ⚙️ Core Product Engine

### Google OAuth 2.0 Authentication

Frictionless, enterprise-grade sign-in with Google via **Laravel Socialite**. Delegates register or authenticate in one gesture while traditional email/password flows remain fully supported. Credentials are environment-governed and validated prior to any OAuth redirect.

### Interactive Project Management

Dynamic project workspaces powered by **Bootstrap 5 modals**—create milestones and tasks without navigation churn. Team leads define target dates, assign collaborators, and organize delivery under milestone hierarchies or standalone workstreams.

### Real-time Task Status Updates

Authorised operators update delivery state directly from the project command center:

| Entity | Allowed statuses |
|--------|------------------|
| **Milestones** | `pending`, `in_progress`, `completed` |
| **Tasks** | `todo`, `in_progress`, `review`, `done` |

Status mutations route through dedicated `PATCH` endpoints and reconcile immediately post-redirect, with optional one-click **Mark as done** accelerators for task completion.

### Internationalization & Interface Refinement

- **Phone prefix selector** on registration: countries sorted alphabetically by English name, with Unicode flag emojis (e.g. `🇩🇪 Germany (+49)`).
- **Sticky footer layout** using the Bootstrap 5 flexbox pattern (`min-vh-100`, `flex-grow-1`, `mt-auto`) so the corporate footer remains anchored on short pages.
- **Remember me** support with Laravel’s persistent authentication cookie.
- **English-first UI** across views, flash messages, and documentation for international stakeholders.

### Extended Platform Capabilities

- Collaboration requests with validated proposal messages (minimum 20 characters).
- AI-style matchmaking suggestions on the dashboard (rule-based, role + interests).
- Admin dashboard for user moderation, event registrations, and project `admin_status`.
- Audit logging for administrative actions.

---

## 🛠️ System Architecture & Data Schema

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

### Technology stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 11 (PHP 8.2+) |
| Database | SQLite (dev) / PostgreSQL recommended (production) |
| Frontend | Blade Templates + Bootstrap 5.3 |
| OAuth authentication | Laravel Socialite (Google) |
| ORM | Eloquent |
| Assets | Vite (optional) |

### Main route structure

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

## 🚀 Deployment & Operation Workflow

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

## Engineering Principles & Design Decisions

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

MIT — MunichTech EXPO 2026 demonstration platform.
