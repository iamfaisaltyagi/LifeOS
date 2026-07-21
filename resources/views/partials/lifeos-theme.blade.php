<style>
    :root {
        --lo-bg: #f4f7fb;
        --lo-text: #0f172a;
        --lo-muted: #5b677c;
        --lo-primary: #0f766e;
        --lo-primary-dark: #115e59;
        --lo-accent: #2563eb;
        --lo-surface: #ffffff;
        --lo-border: #dbe3ef;
        --lo-ring: rgba(15, 118, 110, 0.18);
        --lo-shadow: 0 20px 55px rgba(15, 23, 42, 0.12);
    }

    * {
        box-sizing: border-box;
    }

    body.lo-base {
        margin: 0;
        color: var(--lo-text);
        background:
            radial-gradient(circle at 12% 10%, rgba(37, 99, 235, 0.11), transparent 36%),
            radial-gradient(circle at 85% 12%, rgba(15, 118, 110, 0.14), transparent 38%),
            linear-gradient(180deg, #f9fbff 0%, var(--lo-bg) 62%);
        font-family: "Inter", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.5;
    }

    .lo-container {
        width: min(1140px, 92vw);
        margin: 0 auto;
    }

    .lo-brand {
        display: inline-flex;
        align-items: center;
        gap: 0.7rem;
        text-decoration: none;
        color: inherit;
    }

    .lo-brand-mark {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--lo-accent), var(--lo-primary));
        box-shadow: 0 10px 28px rgba(37, 99, 235, 0.28);
        display: grid;
        place-items: center;
        color: #fff;
        font-weight: 700;
    }

    .lo-brand-name {
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .lo-button {
        border: 1px solid transparent;
        border-radius: 12px;
        font-size: 0.94rem;
        font-weight: 600;
        text-decoration: none;
        padding: 0.72rem 1.15rem;
        transition: 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .lo-button:focus-visible,
    .lo-input:focus-visible {
        outline: none;
        box-shadow: 0 0 0 4px var(--lo-ring);
    }

    .lo-button-primary {
        background: linear-gradient(135deg, var(--lo-primary), var(--lo-primary-dark));
        color: #fff;
    }

    .lo-button-primary:hover {
        transform: translateY(-1px);
        filter: brightness(1.03);
    }

    .lo-button-secondary {
        background: var(--lo-surface);
        color: var(--lo-text);
        border-color: var(--lo-border);
    }

    .lo-button-secondary:hover {
        border-color: #b7c4da;
        transform: translateY(-1px);
    }

    .lo-card {
        background: var(--lo-surface);
        border: 1px solid var(--lo-border);
        border-radius: 18px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
    }

    .lo-section-title {
        font-size: 1.45rem;
        font-weight: 700;
        margin: 0;
    }

    .lo-section-subtitle {
        color: var(--lo-muted);
        margin-top: 0.35rem;
        margin-bottom: 0;
    }

    .lo-auth-layout {
        min-height: 100vh;
        display: grid;
        grid-template-columns: minmax(0, 1fr);
    }

    .lo-auth-shell {
        width: min(1060px, 94vw);
        margin: auto;
        display: grid;
        gap: 1rem;
        grid-template-columns: minmax(0, 1fr);
        padding: 1.2rem 0;
    }

    .lo-auth-hero,
    .lo-auth-panel {
        border-radius: 22px;
        border: 1px solid var(--lo-border);
        background: var(--lo-surface);
    }

    .lo-auth-hero {
        padding: 2rem;
        background:
            radial-gradient(circle at 80% 12%, rgba(37, 99, 235, 0.2), transparent 42%),
            radial-gradient(circle at 20% 94%, rgba(15, 118, 110, 0.18), transparent 44%),
            #fff;
    }

    .lo-auth-panel {
        box-shadow: var(--lo-shadow);
        padding: 1.6rem;
    }

    .lo-auth-headline {
        margin: 1rem 0 0;
        font-size: clamp(1.65rem, 4vw, 2.4rem);
        line-height: 1.15;
        max-width: 20ch;
    }

    .lo-auth-copy {
        color: var(--lo-muted);
        max-width: 42ch;
        margin-top: 0.85rem;
    }

    .lo-badge-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1.15rem;
    }

    .lo-badge {
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 999px;
        padding: 0.3rem 0.65rem;
        font-size: 0.76rem;
        font-weight: 600;
    }

    .lo-form-title {
        margin: 1.2rem 0 0.25rem;
        font-size: 1.42rem;
    }

    .lo-form-subtitle {
        margin: 0 0 1.35rem;
        color: var(--lo-muted);
        font-size: 0.95rem;
    }

    .lo-field {
        margin-bottom: 0.95rem;
    }

    .lo-label {
        display: block;
        font-size: 0.84rem;
        font-weight: 600;
        margin-bottom: 0.45rem;
        color: #334155;
    }

    .lo-input-wrap {
        position: relative;
    }

    .lo-input {
        width: 100%;
        border: 1px solid var(--lo-border);
        border-radius: 12px;
        background: #fff;
        color: var(--lo-text);
        padding: 0.72rem 0.85rem;
        font-size: 0.95rem;
        transition: 0.2s ease;
    }

    .lo-input:focus {
        border-color: var(--lo-primary);
        outline: none;
        box-shadow: 0 0 0 4px var(--lo-ring);
    }

    .lo-input-password {
        padding-right: 2.8rem;
    }

    .lo-toggle-password {
        position: absolute;
        top: 50%;
        right: 0.5rem;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #64748b;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0.25rem 0.45rem;
        border-radius: 8px;
    }

    .lo-toggle-password:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .lo-error {
        margin-top: 0.35rem;
        font-size: 0.82rem;
        color: #b91c1c;
    }

    .lo-inline {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        margin: 0.7rem 0 1rem;
    }

    .lo-checkbox {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        color: #475569;
        font-size: 0.86rem;
    }

    .lo-link {
        color: var(--lo-accent);
        text-decoration: none;
        font-size: 0.86rem;
        font-weight: 600;
    }

    .lo-link:hover {
        text-decoration: underline;
    }

    .lo-alert {
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #166534;
        border-radius: 12px;
        font-size: 0.88rem;
        padding: 0.7rem 0.85rem;
        margin-bottom: 1rem;
    }

    .lo-auth-footer {
        margin-top: 1.2rem;
        font-size: 0.9rem;
        color: #475569;
    }

    .lo-auth-footer a {
        color: var(--lo-accent);
        text-decoration: none;
        font-weight: 600;
    }

    .lo-auth-footer a:hover {
        text-decoration: underline;
    }

    @media (min-width: 900px) {
        .lo-auth-shell {
            grid-template-columns: 1fr 1fr;
            align-items: stretch;
            padding: 1.5rem 0;
        }

        .lo-auth-panel {
            padding: 2rem;
        }
    }
</style>