import SEO from '../components/SEO';
import Hero from '../components/Hero';
import About from '../components/About';
import Features from '../components/Features';
import Services from '../components/Services';
import Testimonials from '../components/Testimonials';
import Blog from '../components/Blog';
import Contact from '../components/Contact';

export default function Home() {
  return (
    <>
      <SEO pageKey="home" />
      <Hero />
      <Features />
      <About />
      <Services />
      <Testimonials />
      <Blog />
      <Contact />
    </>
  );
}

