import { Link } from 'react-router-dom';
import './Footer.css';

const menuLinks = [
  { label: 'Home', href: '/' },
  { label: 'About Us', href: '/about-us' },
  { label: 'Services', href: '/our-services' },
  { label: 'Fractional Leadership', href: '/ftl' },
  { label: 'Blog', href: '/blog' },
  { label: 'Contact Us', href: '/contact-us' },
];

const serviceLinks = [
  'Digital Maturity Assessment',
  'Digital Transformation',
  'HR Technology',
  'Service Desk & Ticketing',
  'Project Management',
];

const socialLinks = [
  {
    label: 'LinkedIn',
    href: 'https://www.linkedin.com/company/climbsphere-technologies/',
    icon: (
      <svg viewBox="0 0 448 512" aria-hidden="true">
        <path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z" />
      </svg>
    ),
  },
];

function FooterTitle({ children }) {
  return (
    <h3 className="footer-title">
      {children}
      <span />
    </h3>
  );
}

export default function Footer() {
  return (
    <footer className="footer">
      <div className="container footer-inner">
        <div className="footer-grid">
          <div className="footer-col">
            <FooterTitle>About Us</FooterTitle>
            <img
              className="footer-logo"
              src="/images/climbsphere-logo-footer.png"
              alt="ClimbSphere Technologies"
              width="1536"
              height="663"
              loading="lazy"
            />
          </div>

          <div className="footer-col">
            <FooterTitle>Menu</FooterTitle>
            <ul className="footer-links">
              {menuLinks.map((link) => (
                <li key={link.label}>
                  <Link to={link.href}>{link.label}</Link>
                </li>
              ))}
            </ul>
          </div>

          <div className="footer-col">
            <FooterTitle>Services</FooterTitle>
            <ul className="footer-links">
              {serviceLinks.map((service) => (
                <li key={service}>
                  <Link to="/our-services">{service}</Link>
                </li>
              ))}
            </ul>
          </div>

          <div className="footer-col">
            <FooterTitle>Information</FooterTitle>
            <ul className="footer-contact">
              <li>
                <span className="contact-icon">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 21s7-6 7-12a7 7 0 1 0-14 0c0 6 7 12 7 12Z" />
                    <circle cx="12" cy="9" r="2.5" />
                  </svg>
                </span>
                <a href="http://google.com/maps?ll=13.061021,80.247478&z=14&t=m&hl=en-GB&gl=US&mapclient=embed&q=1E,+1st+Floor,+Eldorado+building,+Nungambakkam,+Chennai+600034">
                  1E, 1st Floor, Eldarado building, Nungambakkam, Chennai 600034
                </a>
              </li>
              <li>
                <span className="contact-icon">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 11.2 19 19.5 19.5 0 0 1 5 12.8 19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2.1Z" />
                  </svg>
                </span>
                <a href="tel:+918610486636">+91 861 048 6636</a>
              </li>
              <li>
                <span className="contact-icon">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 4h16v16H4z" />
                    <path d="m4 7 8 6 8-6" />
                  </svg>
                </span>
                <a href="mailto:sales@climbsphere.ai">sales@climbsphere.ai</a>
              </li>
            </ul>
          </div>
        </div>

        <div className="footer-bottom">
          <div className="footer-social">
            {socialLinks.map((item) => (
              <a 
                key={item.label} 
                href={item.href || '#'} 
                aria-label={item.label}
                target={item.href !== '#' ? '_blank' : undefined}
                rel={item.href !== '#' ? 'noopener noreferrer' : undefined}
              >
                {item.icon}
              </a>
            ))}
          </div>
        </div>
      </div>
    </footer>
  );
}
