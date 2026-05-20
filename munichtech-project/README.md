# MunichTech EXPO

Event management, hackathon, and corporate-startup collaboration platform built with **Laravel 11**, **Blade**, **Bootstrap 5**, and **SQLite**.

---

## 1. Installation and Setup Guide

### Prerequisites

- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 18 (optional, for Vite assets)
- PHP extensions: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`

### Installation steps

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

### Google OAuth configuration (optional)

Add to your `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

Register the callback URI in [Google Cloud Console](https://console.cloud.google.com/) → APIs & Services → Credentials.

---

## 2. System Architecture

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

---

## 3. Technology Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 11 (PHP 8.2+) |
| Database | SQLite |
| Frontend | Blade Templates + Bootstrap 5.3 |
| OAuth authentication | Laravel Socialite (Google) |
| ORM | Eloquent |
| Assets | Vite (optional) |

---

## 4. Security Protocols and Measures

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

## 5. Development Assumptions and Design Decisions

1. **Unified migrations**: Duplicate migrations `0001_01_01_000003–000009` were removed; schema `2026_05_20_*` is the source of truth.
2. **Dual project status**: `status` (team workflow) and `admin_status` (moderation: active/inactive/suspended) are separate concepts.
3. **Simulated matchmaking**: Rule-based algorithm using complementary roles + shared interests (no external AI API).
4. **SQLite in development**: Ideal for demos and lightweight deployments; migratable to PostgreSQL/MySQL by changing `DB_CONNECTION`.
5. **Optional OAuth**: The app works without Google OAuth; the button redirects but requires configured credentials.

---

## 6. Scalability Strategy and Future Improvements

| Area | Proposal |
|------|----------|
| Database | Migrate to PostgreSQL with read replicas |
| Queues | Redis + Laravel Horizon for notifications and emails |
| Real AI | Integrate OpenAI/Anthropic API for semantic matchmaking |
| Real-time | Laravel Reverb / Pusher for collaboration chat |
| REST API | Laravel Sanctum for mobile event app |
| Tests | PHPUnit feature tests for critical flows (auth, collaboration, admin) |
| CI/CD | GitHub Actions with `migrate --seed` in pipeline |

---

## 7. AI Tools Used in Development

This deliverable was accelerated with AI assistance in the following areas:

| Tool | Usage |
|------|-------|
| **Cursor (Composer Agent)** | Cross-workspace analysis, schema/view/controller inconsistency detection, full patch generation |
| **GitHub Copilot** | Blade boilerplate and Laravel validation autocompletion |
| **Generative AI** | Landing page design, README documentation, and rule-based matchmaking algorithm |

**Transparency**: All business logic, migrations, security middleware, and CRUD flows were manually reviewed and aligned with project requirements. AI tools acted as accelerators, not substitutes for architectural decisions.

---

## Main Route Structure

| Route | Description |
|-------|-------------|
| `/` | Corporate landing page |
| `/register`, `/login` | Authentication + Google OAuth |
| `/dashboard` | User dashboard with AI matchmaking |
| `/collaborations` | Collaboration request management |
| `/projects` | Project CRUD |
| `/events` | Event registrations |
| `/admin` | CRUD dashboard (administrators only) |

---

## License

MIT — Academic project / MunichTech EXPO 2026 demonstration.
