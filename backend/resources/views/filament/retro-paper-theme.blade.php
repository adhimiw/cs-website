{{-- Retro Vintage Doodle Paper Theme for ClimbSphere Filament Admin --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Delius&family=JetBrains+Mono:wght@400;500;600;700&family=Macondo&family=Silkscreen:wght@400;700&display=swap" rel="stylesheet">

<style>
    /* ═══════════════════════════════════════════════════════════════════
       RETRO / VINTAGE / DOODLE / PAPER / IMPECCABLE CORE TOKENS
       ═══════════════════════════════════════════════════════════════════ */
    :root {
        --paper-cream-50: #fbf8f1;
        --paper-cream-100: #f7f3e8;
        --paper-cream-200: #efe7d5;
        --paper-cream-300: #e2d6be;
        --paper-card-bg: #fffdf7;
        --paper-card-alt: #f5eedf;
        --ink-primary: #211e1b;
        --ink-secondary: #574e44;
        --ink-muted: #827667;
        --burnt-orange: #c25e2e;
        --burnt-orange-dark: #9e461e;
        --burnt-orange-light: #f5cfbc;
        --vintage-sage: #536c53;
        --vintage-blue: #2d4f7c;
        --vintage-stamp-red: #a83526;
        --washi-tape: rgba(228, 214, 182, 0.88);
        --doodle-border: 2px solid #211e1b;
        --doodle-radius: 255px 15px 225px 15px / 15px 225px 15px 255px;
        --doodle-radius-sm: 180px 8px 160px 8px / 8px 160px 8px 180px;
        --ink-shadow: 3px 3px 0px #211e1b;
        --ink-shadow-sm: 2px 2px 0px #211e1b;
        --ink-shadow-lg: 5px 5px 0px #211e1b;
    }

    /* ═══════════════════════════════════════════════════════════════════
       GLOBAL CANVAS & HALFTONE / INK DOTS BACKGROUND
       ═══════════════════════════════════════════════════════════════════ */
    html, body, .fi-body {
        font-family: 'Chakra Petch', sans-serif !important;
        color: var(--ink-primary) !important;
        background-color: var(--paper-cream-100) !important;
        background-image: 
            radial-gradient(circle, #ded1b8 1.1px, transparent 1.1px),
            radial-gradient(circle, #ded1b8 1.1px, var(--paper-cream-100) 1.1px) !important;
        background-size: 20px 20px !important;
        background-position: 0 0, 10px 10px !important;
        -webkit-font-smoothing: antialiased;
    }

    /* ═══════════════════════════════════════════════════════════════════
       TYPOGRAPHY MAPPING
       ═══════════════════════════════════════════════════════════════════ */
    /* Macondo Display for Page Titles, Brand, Big Headers */
    .fi-header-heading,
    .fi-logo,
    .retro-display,
    h1.fi-header-heading,
    .section-title span,
    .brand-title {
        font-family: 'Macondo', cursive !important;
        letter-spacing: 0.02em !important;
        color: var(--ink-primary) !important;
        text-shadow: 1px 1px 0px rgba(194, 94, 46, 0.2) !important;
    }

    .fi-header-heading {
        font-size: 1.85rem !important;
        font-weight: 700 !important;
    }

    /* Subheadings and Helper text in Delius handwritten script */
    .fi-header-subheading,
    .section-desc,
    .doodle-note,
    .fi-fo-field-wrp-helper-text,
    .form-helper {
        font-family: 'Delius', cursive !important;
        color: var(--ink-secondary) !important;
        font-size: 0.92rem !important;
    }

    /* Silkscreen pixel/retro typography for stamps, tags, badges */
    .fi-badge,
    .stamp-badge,
    .stamp,
    .fi-ta-header-cell-label,
    .stat-label {
        font-family: 'Silkscreen', monospace !important;
        letter-spacing: 0.06em !important;
    }

    /* JetBrains Mono for metrics, code, timestamps, numbers */
    .font-mono,
    code,
    pre,
    .stat-value,
    .fi-ta-cell-time,
    .terminal-code,
    .log-timestamp {
        font-family: 'JetBrains Mono', monospace !important;
    }

    /* ═══════════════════════════════════════════════════════════════════
       SIDEBAR & NAVIGATION (PARCHMENT & INK)
       ═══════════════════════════════════════════════════════════════════ */
    .fi-sidebar {
        background-color: var(--paper-cream-50) !important;
        border-right: 2px solid var(--ink-primary) !important;
        box-shadow: 3px 0 0 rgba(33, 30, 27, 0.08) !important;
    }

    .fi-sidebar-header {
        border-bottom: 2px dashed var(--ink-muted) !important;
        background-color: var(--paper-cream-200) !important;
        padding-top: 14px !important;
        padding-bottom: 14px !important;
        position: relative;
    }

    /* Washi tape accent on top of sidebar */
    .fi-sidebar-header::before {
        content: '';
        position: absolute;
        top: -6px;
        left: 20%;
        width: 60%;
        height: 12px;
        background: var(--washi-tape);
        border-left: 1px dashed rgba(33, 30, 27, 0.3);
        border-right: 1px dashed rgba(33, 30, 27, 0.3);
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        transform: rotate(-1deg);
        z-index: 20;
        pointer-events: none;
    }

    .fi-sidebar-item-button {
        border-radius: 6px !important;
        transition: all 0.15s ease !important;
        margin: 2px 8px !important;
        border: 1px solid transparent !important;
        font-weight: 500 !important;
    }

    .fi-sidebar-item-button:hover {
        background-color: var(--paper-cream-200) !important;
        border: 1px dashed var(--ink-muted) !important;
        transform: translateX(2px) !important;
    }

    .fi-sidebar-item-active .fi-sidebar-item-button {
        background-color: var(--burnt-orange) !important;
        color: #ffffff !important;
        border: 2px solid var(--ink-primary) !important;
        box-shadow: var(--ink-shadow-sm) !important;
    }

    .fi-sidebar-item-active .fi-sidebar-item-button * {
        color: #ffffff !important;
    }

    .fi-sidebar-group-label {
        font-family: 'Silkscreen', monospace !important;
        font-size: 0.68rem !important;
        letter-spacing: 0.1em !important;
        color: var(--burnt-orange) !important;
        text-transform: uppercase !important;
        margin-top: 14px !important;
    }

    /* ═══════════════════════════════════════════════════════════════════
       TOPBAR (PARCHMENT STRIP)
       ═══════════════════════════════════════════════════════════════════ */
    .fi-topbar {
        background-color: var(--paper-cream-50) !important;
        border-bottom: 2px solid var(--ink-primary) !important;
        box-shadow: 0 2px 0 rgba(33, 30, 27, 0.05) !important;
    }

    /* ═══════════════════════════════════════════════════════════════════
       CARDS, PANELS & SECTIONS (DOODLE INK CARDS WITH WASHI TAPE)
       ═══════════════════════════════════════════════════════════════════ */
    .fi-section,
    .fi-wi-stats-overview-stat,
    .settings-section,
    .panel-container,
    .info-card,
    .stat-card,
    .fi-ta-ctn {
        background-color: var(--paper-card-bg) !important;
        border: 2px solid var(--ink-primary) !important;
        border-radius: 12px !important;
        box-shadow: var(--ink-shadow) !important;
        position: relative !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }

    .fi-section:hover,
    .stat-card:hover,
    .info-card:hover {
        transform: translate(-1px, -1px) !important;
        box-shadow: var(--ink-shadow-lg) !important;
    }

    /* Decorative Washi Tape strip on cards */
    .settings-section::before,
    .panel-container::before,
    .stat-card::after {
        content: '';
        position: absolute;
        top: -8px;
        right: 28px;
        width: 72px;
        height: 15px;
        background: var(--washi-tape);
        border-left: 2px dashed rgba(33, 30, 27, 0.25);
        border-right: 2px dashed rgba(33, 30, 27, 0.25);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        transform: rotate(2deg);
        z-index: 10;
        pointer-events: none;
    }

    /* Section Headers */
    .fi-section-header,
    .section-header,
    .panel-header {
        background-color: var(--paper-card-alt) !important;
        border-bottom: 2px solid var(--ink-primary) !important;
        padding: 14px 20px !important;
    }

    /* Alternating Burnt Orange Banner Sections */
    .burnt-orange-section {
        background-color: var(--burnt-orange) !important;
        color: #fffef9 !important;
        border: 2px solid var(--ink-primary) !important;
        box-shadow: var(--ink-shadow) !important;
        border-radius: 12px !important;
    }

    .burnt-orange-section h2,
    .burnt-orange-section h3,
    .burnt-orange-section p,
    .burnt-orange-section span {
        color: #fffef9 !important;
    }

    /* ═══════════════════════════════════════════════════════════════════
       TABLES (INK-RULED PARCHMENT LEDGER)
       ═══════════════════════════════════════════════════════════════════ */
    .fi-ta-table {
        border-collapse: collapse !important;
        width: 100% !important;
    }

    .fi-ta-header-cell {
        background-color: var(--paper-cream-200) !important;
        border-bottom: 2px solid var(--ink-primary) !important;
        padding: 12px 16px !important;
        color: var(--ink-primary) !important;
    }

    .fi-ta-header-cell-label {
        font-family: 'Silkscreen', monospace !important;
        font-size: 0.72rem !important;
        color: var(--ink-secondary) !important;
        text-transform: uppercase !important;
    }

    .fi-ta-row {
        transition: background-color 0.12s ease !important;
        border-bottom: 1px solid var(--paper-cream-300) !important;
    }

    .fi-ta-row:nth-child(odd) {
        background-color: var(--paper-card-bg) !important;
    }

    .fi-ta-row:nth-child(even) {
        background-color: var(--paper-card-alt) !important;
    }

    .fi-ta-row:hover {
        background-color: #f7e6d7 !important; /* warm burnt-orange tint on hover */
    }

    .fi-ta-cell {
        color: var(--ink-primary) !important;
        padding: 14px 16px !important;
    }

    /* ═══════════════════════════════════════════════════════════════════
       BUTTONS (STAMP / PRESS EFFECT)
       ═══════════════════════════════════════════════════════════════════ */
    .fi-btn {
        font-family: 'Chakra Petch', sans-serif !important;
        font-weight: 600 !important;
        letter-spacing: 0.03em !important;
        border: 2px solid var(--ink-primary) !important;
        box-shadow: var(--ink-shadow-sm) !important;
        transition: all 0.12s ease !important;
        border-radius: 8px !important;
    }

    .fi-btn:hover {
        transform: translate(-1px, -1px) !important;
        box-shadow: var(--ink-shadow) !important;
    }

    .fi-btn:active {
        transform: translate(1px, 1px) !important;
        box-shadow: 1px 1px 0px var(--ink-primary) !important;
    }

    .fi-btn-color-primary {
        background-color: var(--burnt-orange) !important;
        color: #ffffff !important;
    }

    .fi-btn-color-primary:hover {
        background-color: var(--burnt-orange-dark) !important;
    }

    .fi-btn-color-gray {
        background-color: var(--paper-cream-200) !important;
        color: var(--ink-primary) !important;
    }

    /* ═══════════════════════════════════════════════════════════════════
       INPUTS & FORM CONTROLS (CRISP INK ON PARCHMENT)
       ═══════════════════════════════════════════════════════════════════ */
    .fi-input,
    .fi-select-input,
    .form-input,
    .form-select,
    textarea.fi-input {
        background-color: var(--paper-card-bg) !important;
        border: 2px solid var(--ink-primary) !important;
        border-radius: 8px !important;
        color: var(--ink-primary) !important;
        font-family: 'Chakra Petch', sans-serif !important;
        font-size: 0.925rem !important;
        box-shadow: 2px 2px 0px rgba(33, 30, 27, 0.12) !important;
        transition: border-color 0.15s, box-shadow 0.15s !important;
    }

    .fi-input:focus,
    .fi-select-input:focus,
    .form-input:focus,
    .form-select:focus {
        border-color: var(--burnt-orange) !important;
        box-shadow: 3px 3px 0px var(--burnt-orange) !important;
        outline: none !important;
    }

    .fi-fo-field-wrp-label,
    .form-label {
        font-family: 'Chakra Petch', sans-serif !important;
        font-weight: 600 !important;
        color: var(--ink-primary) !important;
        letter-spacing: 0.02em !important;
    }

    /* ═══════════════════════════════════════════════════════════════════
       STAMPS & BADGES
       ═══════════════════════════════════════════════════════════════════ */
    .stamp {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-family: 'Silkscreen', monospace;
        font-size: 0.72rem;
        padding: 3px 8px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border: 2px dashed currentColor;
        border-radius: 4px;
        transform: rotate(-2deg);
        box-shadow: 1px 1px 0px rgba(0,0,0,0.15);
    }

    .stamp-orange {
        color: var(--burnt-orange);
        background-color: rgba(194, 94, 46, 0.08);
    }

    .stamp-ink {
        color: var(--ink-primary);
        background-color: rgba(33, 30, 27, 0.06);
    }

    .stamp-sage {
        color: var(--vintage-sage);
        background-color: rgba(83, 108, 83, 0.08);
    }

    .stamp-red {
        color: var(--vintage-stamp-red);
        background-color: rgba(168, 53, 38, 0.08);
        transform: rotate(2deg);
    }

    .stamp-blue {
        color: var(--vintage-blue);
        background-color: rgba(45, 79, 124, 0.08);
    }

    .fi-badge {
        border: 1.5px solid currentColor !important;
        box-shadow: 1px 1px 0px rgba(33, 30, 27, 0.2) !important;
        border-radius: 4px !important;
        padding: 2px 7px !important;
        font-size: 0.68rem !important;
    }

    /* ═══════════════════════════════════════════════════════════════════
       MODALS & DIALOGS
       ═══════════════════════════════════════════════════════════════════ */
    .fi-modal-window {
        background-color: var(--paper-card-bg) !important;
        border: 2px solid var(--ink-primary) !important;
        box-shadow: var(--ink-shadow-lg) !important;
        border-radius: 14px !important;
    }

    .fi-modal-header {
        background-color: var(--paper-card-alt) !important;
        border-bottom: 2px solid var(--ink-primary) !important;
    }

    /* ═══════════════════════════════════════════════════════════════════
       CUSTOM SCROLLBAR (INK RULE)
       ═══════════════════════════════════════════════════════════════════ */
    ::-webkit-scrollbar {
        width: 9px;
        height: 9px;
    }
    ::-webkit-scrollbar-track {
        background: var(--paper-cream-200);
        border-left: 1px solid var(--ink-muted);
    }
    ::-webkit-scrollbar-thumb {
        background: var(--ink-secondary);
        border-radius: 4px;
        border: 2px solid var(--paper-cream-200);
    }
    ::-webkit-scrollbar-thumb:hover {
        background: var(--burnt-orange);
    }
</style>
