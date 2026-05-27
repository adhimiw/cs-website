import { Link } from 'react-router-dom';
import './Footer.css';

const menuLinks = [
  { label: 'Home', href: '/' },
  { label: 'About Us', href: '/about-us' },
  { label: 'Services', href: '/our-services' },
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
    label: 'Facebook',
    href: '#',
    icon: (
      <svg viewBox="0 0 320 512" aria-hidden="true">
        <path d="M279.14 288l14.22-92.83h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" />
      </svg>
    ),
  },
  {
    label: 'Twitter',
    href: 'https://twitter.com/climbsphere',
    icon: (
      <svg viewBox="0 0 512 512" aria-hidden="true">
        <path d="M459.4 151.7c.3 4.5.3 9.1.3 13.6 0 138.7-105.6 298.6-298.6 298.6-59.5 0-114.7-17.2-161.1-47.1 8.4 1 16.6 1.3 25.3 1.3 49.1 0 94.2-16.6 130.3-44.8-46.1-1-84.8-31.2-98.1-72.8 6.5 1 13 1.6 19.8 1.6 9.4 0 18.8-1.3 27.6-3.6-48.1-9.7-84.1-52-84.1-103v-1.3c14 7.8 30.2 12.7 47.4 13.3-28.3-18.8-46.8-51-46.8-87.4 0-19.5 5.2-37.4 14.3-53 51.7 63.7 129.3 105.3 216.4 109.8-1.6-7.8-2.6-15.9-2.6-24 0-57.8 46.8-104.9 104.9-104.9 30.2 0 57.5 12.7 76.7 33.1 23.7-4.5 46.5-13.3 66.6-25.3-7.8 24.4-24.4 44.8-46.1 57.8 21.1-2.3 41.6-8.1 60.4-16.2-14.3 20.8-32.2 39.3-52.6 54.3z" />
      </svg>
    ),
  },
  {
    label: 'LinkedIn',
    href: 'https://www.linkedin.com/company/climbsphere-technologies/',
    icon: (
      <svg viewBox="0 0 448 512" aria-hidden="true">
        <path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z" />
      </svg>
    ),
  },
  {
    label: 'Instagram',
    href: '#',
    icon: (
      <svg viewBox="0 0 448 512" aria-hidden="true">
        <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
      </svg>
    ),
  },
  {
    label: 'YouTube',
    href: '#',
    icon: (
      <svg viewBox="0 0 576 512" aria-hidden="true">
        <path d="M549.7 124.1c-6.3-23.7-24.9-42.3-48.6-48.6C458.2 64 288 64 288 64S117.8 64 74.9 75.5c-23.7 6.3-42.3 24.9-48.6 48.6C14.8 167 14.8 256 14.8 256s0 89 11.5 131.9c6.3 23.7 24.9 42.3 48.6 48.6C117.8 448 288 448 288 448s170.2 0 213.1-11.5c23.7-6.3 42.3-24.9 48.6-48.6C561.2 345 561.2 256 561.2 256s0-89-11.5-131.9zM232 337.6V174.4L376 256 232 337.6z" />
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
          <a href="https://whyglobalservices.com/">Powered by WHY Global Services</a>
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
