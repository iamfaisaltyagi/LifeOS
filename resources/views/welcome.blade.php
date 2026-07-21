<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeOS | Plan your day. Build better habits. Improve your life.</title>
    @include('partials.lifeos-theme')
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        .lo-nav {
            position: sticky;
            top: 0;
            backdrop-filter: blur(10px);
            background: rgba(249, 251, 255, 0.9);
            border-bottom: 1px solid var(--lo-border);
            z-index: 20;
        }

        .lo-nav-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 0.9rem 0;
        }

        .lo-nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
        }

        .lo-hero {
            padding: 4rem 0 2.3rem;
        }

        .lo-hero-grid {
            display: grid;
            gap: 1.25rem;
            grid-template-columns: minmax(0, 1fr);
        }

        .lo-hero-title {
            margin: 0;
            font-size: clamp(2rem, 5.8vw, 3.4rem);
            line-height: 1.07;
            letter-spacing: -0.03em;
            max-width: 18ch;
        }

        .lo-hero-subtitle {
            margin-top: 0.95rem;
            color: var(--lo-muted);
            font-size: 1.03rem;
            max-width: 54ch;
        }

        .lo-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
            margin-top: 1.4rem;
        }

        .lo-kpis {
            display: grid;
            gap: 0.8rem;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            margin-top: 1.65rem;
        }

        .lo-kpi {
            background: #fff;
            border: 1px solid var(--lo-border);
            border-radius: 14px;
            padding: 0.9rem;
        }

        .lo-kpi-value {
            font-size: 1.35rem;
            font-weight: 700;
        }

        .lo-kpi-label {
            font-size: 0.82rem;
            color: var(--lo-muted);
        }

        .lo-surface-panel {
            padding: 1.3rem;
            min-height: 100%;
        }

        .lo-grid-two {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        .lo-feature-list {
            list-style: none;
            margin: 0.8rem 0 0;
            padding: 0;
            display: grid;
            gap: 0.66rem;
        }

        .lo-feature-item {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            color: #334155;
            font-size: 0.94rem;
        }

        .lo-feature-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--lo-primary), var(--lo-accent));
            flex: 0 0 auto;
        }

        .lo-ai {
            margin-top: 2rem;
            padding: 1.35rem;
            background:
                radial-gradient(circle at 8% 16%, rgba(15, 118, 110, 0.16), transparent 35%),
                radial-gradient(circle at 89% 22%, rgba(37, 99, 235, 0.17), transparent 45%),
                #ffffff;
        }

        .lo-ai p {
            color: #374151;
            margin: 0.5rem 0 0;
            max-width: 70ch;
        }

        .lo-footer {
            border-top: 1px solid var(--lo-border);
            margin-top: 2.2rem;
            padding: 1.4rem 0 1.8rem;
            color: #64748b;
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        @media (min-width: 980px) {
            .lo-hero {
                padding-top: 5.3rem;
            }

            .lo-hero-grid {
                grid-template-columns: 1.25fr 0.9fr;
                align-items: center;
                gap: 1.8rem;
            }

            .lo-grid-two {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.2rem;
            }
        }
    </style>
</head>
<body class="lo-base">
    <header class="lo-nav">
        <div class="lo-container lo-nav-inner">
            <a href="{{ url('/') }}" class="lo-brand" aria-label="LifeOS Home">
                <span class="lo-brand-mark">LO</span>
                <span class="lo-brand-name">LifeOS</span>
            </a>

            <nav class="lo-nav-links" aria-label="Primary">
                @auth
                    <a href="{{ route('dashboard') }}" class="lo-button lo-button-primary">Open Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="lo-button lo-button-secondary">Login</a>
                    <a href="{{ route('register') }}" class="lo-button lo-button-primary">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="lo-container">
        <section class="lo-hero">
            <div class="lo-hero-grid">
                <div>
                    <h1 class="lo-hero-title">Plan your day. Build better habits. Improve your life.</h1>
                    <p class="lo-hero-subtitle">
                        LifeOS is your productivity and wellness workspace for tasks, nutrition, workouts, habits, and intelligent planning.
                    </p>
                    <div class="lo-hero-actions">
                        @auth
                            <a href="{{ route('dashboard') }}" class="lo-button lo-button-primary">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="lo-button lo-button-primary">Get Started</a>
                            <a href="{{ route('login') }}" class="lo-button lo-button-secondary">Login</a>
                        @endauth
                    </div>

                    <div class="lo-kpis" aria-label="Value Highlights">
                        <div class="lo-kpi">
                            <div class="lo-kpi-value">All-in-one</div>
                            <div class="lo-kpi-label">Tasks, habits, diet, workouts</div>
                        </div>
                        <div class="lo-kpi">
                            <div class="lo-kpi-value">Private</div>
                            <div class="lo-kpi-label">User-isolated personal data</div>
                        </div>
                        <div class="lo-kpi">
                            <div class="lo-kpi-value">Daily Focus</div>
                            <div class="lo-kpi-label">Progress-led planning flow</div>
                        </div>
                    </div>
                </div>

                <aside class="lo-card lo-surface-panel" aria-label="LifeOS Summary">
                    <h2 class="lo-section-title">Your modern life system</h2>
                    <p class="lo-section-subtitle">A premium workspace designed for consistency and growth.</p>
                    <ul class="lo-feature-list">
                        <li class="lo-feature-item"><span class="lo-feature-dot"></span> Smart daily dashboard</li>
                        <li class="lo-feature-item"><span class="lo-feature-dot"></span> Priority task planning</li>
                        <li class="lo-feature-item"><span class="lo-feature-dot"></span> Habit streak tracking</li>
                        <li class="lo-feature-item"><span class="lo-feature-dot"></span> Nutrition and workout checklists</li>
                    </ul>
                </aside>
            </div>

            <div class="lo-grid-two">
                <section class="lo-card lo-surface-panel">
                    <h2 class="lo-section-title">Productivity Modules</h2>
                    <p class="lo-section-subtitle">Build momentum with daily execution clarity.</p>
                    <ul class="lo-feature-list">
                        <li class="lo-feature-item"><span class="lo-feature-dot"></span> Task management with priorities and due dates</li>
                        <li class="lo-feature-item"><span class="lo-feature-dot"></span> Calendar view for your daily activities</li>
                        <li class="lo-feature-item"><span class="lo-feature-dot"></span> Progress-focused personal dashboard</li>
                    </ul>
                </section>

                <section class="lo-card lo-surface-panel">
                    <h2 class="lo-section-title">Wellness Modules</h2>
                    <p class="lo-section-subtitle">Stay balanced while you scale your productivity.</p>
                    <ul class="lo-feature-list">
                        <li class="lo-feature-item"><span class="lo-feature-dot"></span> Diet checklist with macro tracking</li>
                        <li class="lo-feature-item"><span class="lo-feature-dot"></span> Workout planning and completion status</li>
                        <li class="lo-feature-item"><span class="lo-feature-dot"></span> Habit tracking with streak visibility</li>
                    </ul>
                </section>
            </div>

            <section class="lo-card lo-ai">
                <h2 class="lo-section-title">AI Assistant (Coming Next)</h2>
                <p>
                    LifeOS is prepared for an AI layer that will support smart task breakdown, daily planning, and personalized productivity guidance using only your own data.
                </p>
                <div class="lo-hero-actions" style="margin-top: 1rem;">
                    @auth
                        <a href="{{ route('dashboard') }}" class="lo-button lo-button-primary">Start Planning</a>
                    @else
                        <a href="{{ route('register') }}" class="lo-button lo-button-primary">Create Your Account</a>
                        <a href="{{ route('login') }}" class="lo-button lo-button-secondary">I already have an account</a>
                    @endauth
                </div>
            </section>

            <footer class="lo-footer">
                <span>LifeOS</span>
                <span>Plan your day. Build better habits. Improve your life.</span>
            </footer>
        </section>
    </main>
</body>
</html>
