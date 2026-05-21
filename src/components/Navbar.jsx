import { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import './Navbar.css';

const navLinks = [
  { label: 'Home', href: '/' },
  { label: 'About Us', href: '/about-us' },
  { label: 'Services', href: '/our-services' },
  { label: 'Blog', href: '/blog' },
  { label: 'Contact Us', href: '/contact-us' },
];

const socialLinks = [
  {
    label: 'Facebook',
    href: 'http://facebook.com',
    icon: (
      <svg viewBox="0 0 320 512" aria-hidden="true">
        <path d="M279.14 288l14.22-92.83h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" />
      </svg>
    ),
  },
  {
    label: 'X',
    href: 'http://twitter.com',
    icon: (
      <svg viewBox="0 0 512 512" aria-hidden="true">
        <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8l164.9-188.5L26.8 48h145.6l100.5 132.9L389.2 48zm-24.8 373.8h39.1L151.1 88h-42l255.3 333.8z" />
      </svg>
    ),
  },
  {
    label: 'Instagram',
    href: 'http://instagram.com',
    icon: (
      <svg viewBox="0 0 448 512" aria-hidden="true">
        <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9S352.3 35.1 316.4 33.3c-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1S3.1 127.6 1.3 163.5c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.5 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2s34.5-58 36.2-93.9c2.1-37 2.1-147.8.1-184.9zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
      </svg>
    ),
  },
];

export default function Navbar() {
  const [menuOpen, setMenuOpen] = useState(false);
  const location = useLocation();

  return (
    <header className="site-header">
      <div className="header-top">
        <div className="container header-top-inner">
          <div className="header-contact">
            <a href="mailto:sales@climbsphere.ai" className="top-contact-link">
              <svg viewBox="0 0 512 512" aria-hidden="true">
                <path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z" />
              </svg>
              sales@climbsphere.ai
            </a>
            <a href="tel:+918610486636" className="top-contact-link">
              <svg viewBox="0 0 512 512" aria-hidden="true">
                <path d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z" />
              </svg>
              +91 8610486636
            </a>
          </div>
          <div className="header-social">
            {socialLinks.map((item) => (
              <a key={item.label} href={item.href} aria-label={item.label}>
                {item.icon}
              </a>
            ))}
          </div>
        </div>
      </div>

      <div className="header-main">
        <div className="container header-main-inner">
          <Link to="/" className="navbar-logo" onClick={() => setMenuOpen(false)}>
            <img
              src="/images/climbsphere-logo-header.png"
              alt="ClimbSphere Technologies"
            />
          </Link>

          <nav className={`navbar-nav ${menuOpen ? 'open' : ''}`} aria-label="Main navigation">
            {navLinks.map((link) => (
              <Link
                key={link.label}
                to={link.href}
                className={`nav-link ${location.pathname === link.href ? 'active' : ''}`}
                onClick={() => setMenuOpen(false)}
              >
                {link.label}
              </Link>
            ))}
          </nav>

          <Link to="/contact-us" className="nav-btn-cta">
            Book an Appointment
          </Link>

          <button
            className={`hamburger ${menuOpen ? 'open' : ''}`}
            onClick={() => setMenuOpen((value) => !value)}
            aria-label="Toggle menu"
            aria-expanded={menuOpen}
          >
            <span />
            <span />
            <span />
          </button>
        </div>
      </div>
    </header>
  );
}
