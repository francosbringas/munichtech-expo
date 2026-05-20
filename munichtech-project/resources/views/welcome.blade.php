@extends('layouts.app')

@section('title', 'MunichTech EXPO — Innovation & Collaboration')

@push('styles')
<style>
    .landing-hero {
        background: linear-gradient(135deg, #0b1220 0%, #1a2744 50%, #0d3b66 100%);
        color: #fff;
        border-radius: 1.25rem;
        padding: 4rem 2rem;
        position: relative;
        overflow: hidden;
    }
    .landing-hero::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 60%; height: 200%;
        background: radial-gradient(ellipse, rgba(0,212,170,0.15) 0%, transparent 70%);
    }
    .section-title { font-weight: 700; letter-spacing: -0.03em; }
    .feature-card {
        border: none;
        border-radius: 1rem;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    }
    .icon-box {
        width: 56px; height: 56px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .bg-ai { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
    .bg-startup { background: linear-gradient(135deg, #f093fb, #f5576c); color: #fff; }
    .bg-hack { background: linear-gradient(135deg, #4facfe, #00f2fe); color: #fff; }
    .bg-collab { background: linear-gradient(135deg, #43e97b, #38f9d7); color: #fff; }
</style>
@endpush

@section('content')

<section class="landing-hero mb-5">
    <div class="row align-items-center position-relative">
        <div class="col-lg-7">
            <span class="badge bg-success mb-3">Munich · 2026</span>
            <h1 class="display-4 fw-bold mb-3">MunichTech EXPO</h1>
            <p class="lead opacity-90 mb-4">
                Europe's hub where startups, investors, and corporations converge to shape the future of AI, innovation, and enterprise collaboration.
            </p>
            <div class="d-flex flex-wrap gap-3">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-ticket-perforated me-2"></i>Get Ticket
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Access Dashboard
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                    </a>
                    <a href="{{ route('events.create') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-calendar-event me-2"></i>Register for Event
                    </a>
                @endguest
            </div>
        </div>
        <div class="col-lg-5 mt-4 mt-lg-0 text-center">
            <div class="display-1 opacity-25"><i class="bi bi-globe-europe-africa"></i></div>
            <p class="small opacity-75 mb-0">500+ attendees · 40 startups · 12 investors</p>
        </div>
    </div>
</section>

<section class="py-5" id="ai-innovation">
    <div class="text-center mb-5">
        <h2 class="section-title display-6">AI &amp; Innovation</h2>
        <p class="text-muted col-lg-8 mx-auto">Discover the technologies defining the next decade: generative AI, cybersecurity, IoT, and cloud computing.</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card feature-card shadow-sm">
                <div class="card-body p-4">
                    <div class="icon-box bg-ai mb-3"><i class="bi bi-cpu"></i></div>
                    <h5>Artificial Intelligence</h5>
                    <p class="text-muted small mb-0">Live demos of LLM models, automation, and predictive analytics applied to real businesses.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card shadow-sm">
                <div class="card-body p-4">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary mb-3"><i class="bi bi-shield-lock"></i></div>
                    <h5>Cybersecurity</h5>
                    <p class="text-muted small mb-0">Zero Trust workshops, GDPR compliance, and critical infrastructure protection.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card shadow-sm">
                <div class="card-body p-4">
                    <div class="icon-box bg-info bg-opacity-10 text-info mb-3"><i class="bi bi-cloud"></i></div>
                    <h5>Cloud &amp; IoT</h5>
                    <p class="text-muted small mb-0">Scalable architectures and connected ecosystems for smart cities and digital health.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-body-secondary rounded-4 px-3" id="startup-ecosystem">
    <div class="text-center mb-5">
        <h2 class="section-title display-6">Startup Ecosystem</h2>
        <p class="text-muted col-lg-8 mx-auto">Exclusive opportunities for founders, VCs, and corporations seeking high-impact synergies.</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card feature-card border-0 shadow">
                <div class="card-body text-center p-4">
                    <div class="icon-box bg-startup mx-auto mb-3"><i class="bi bi-rocket-takeoff"></i></div>
                    <h5>Startups</h5>
                    <p class="text-muted small">Pitch sessions, 1:1 mentoring, and access to angel investors and venture capital funds.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card feature-card border-0 shadow">
                <div class="card-body text-center p-4">
                    <div class="icon-box bg-warning text-dark mx-auto mb-3"><i class="bi bi-cash-stack"></i></div>
                    <h5>Investors</h5>
                    <p class="text-muted small">Curated deal flow, private meetings, and networking with committee pre-validated founders.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card feature-card border-0 shadow">
                <div class="card-body text-center p-4">
                    <div class="icon-box bg-secondary text-white mx-auto mb-3"><i class="bi bi-building"></i></div>
                    <h5>Corporations</h5>
                    <p class="text-muted small">Open innovation, corporate pilots, and strategic partnerships with service providers.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" id="hackathon">
    <div class="row align-items-center g-4">
        <div class="col-lg-6">
            <h2 class="section-title display-6">Hackathon Module</h2>
            <p class="text-muted">48 hours of intensive development with multidisciplinary teams. Build real MVPs with industry mentors and prizes for the best solutions in AI, IoT, and Fintech.</p>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Teams of up to 5 participants</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>APIs and datasets provided</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Projects integrated into the collaboration platform</li>
            </ul>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 bg-dark text-white rounded">
                    <div class="icon-box bg-hack d-inline-flex mb-3"><i class="bi bi-code-slash"></i></div>
                    <h4>Dev Sprint 2026</h4>
                    <p class="opacity-75 small mb-3">From concept to MVP in one weekend. Winning projects are automatically published on the collaboration dashboard.</p>
                    <span class="badge bg-success">Registrations open</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 mb-4" id="collaboration">
    <div class="text-center mb-5">
        <h2 class="section-title display-6">Collaboration Platform</h2>
        <p class="text-muted col-lg-8 mx-auto">Connect with strategic partners, manage collaboration requests, and launch joint projects with a professional end-to-end workflow.</p>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-md-3 text-center">
            <div class="icon-box bg-collab mx-auto mb-2"><i class="bi bi-person-plus"></i></div>
            <h6>1. Connect</h6>
            <p class="small text-muted">Send collaboration requests to compatible profiles.</p>
        </div>
        <div class="col-md-3 text-center">
            <div class="icon-box bg-primary text-white mx-auto mb-2"><i class="bi bi-handshake"></i></div>
            <h6>2. Accept</h6>
            <p class="small text-muted">Approve partnerships and define team roles.</p>
        </div>
        <div class="col-md-3 text-center">
            <div class="icon-box bg-success text-white mx-auto mb-2"><i class="bi bi-kanban"></i></div>
            <h6>3. Build</h6>
            <p class="small text-muted">Create projects with milestones, tasks, and progress tracking.</p>
        </div>
        <div class="col-md-3 text-center">
            <div class="icon-box bg-warning text-dark mx-auto mb-2"><i class="bi bi-stars"></i></div>
            <h6>4. AI Match</h6>
            <p class="small text-muted">Receive intelligent suggestions for relevant connections.</p>
        </div>
    </div>
    @guest
    <div class="text-center mt-5">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Join MunichTech EXPO</a>
    </div>
    @endguest
</section>

@endsection
