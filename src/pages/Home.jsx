import SEO from '../components/SEO';
import { useCMS } from '../context/CMSContext';
import Hero from '../components/Hero';
import About from '../components/About';
import Features from '../components/Features';
import Services from '../components/Services';
import Testimonials from '../components/Testimonials';
import Blog from '../components/Blog';
import Contact from '../components/Contact';

export default function Home() {
  const { getCMSContent } = useCMS();
  const quickAnswer = getCMSContent('home.aeo.quick_answer', null);

  return (
    <>
      <SEO pageKey="home" />
      <Hero />
      {quickAnswer && (
        <div className="container" style={{ marginTop: '-20px', marginBottom: '20px' }}>
          <div className="aeo-answer-callout">
            <div className="callout-header">
              <span className="callout-badge">Quick Answer</span>
              <span className="callout-info">Direct facts for voice search and AI</span>
            </div>
            <p className="callout-text">{quickAnswer}</p>
          </div>
        </div>
      )}
      <Features />
      <About />
      <Services />
      <Testimonials />
      <Blog />
      <Contact />
    </>
  );
}

