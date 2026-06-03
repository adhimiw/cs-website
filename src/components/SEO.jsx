import { useEffect } from 'react';
import { useCMS } from '../context/CMSContext';

export default function SEO({ pageKey, defaultTitle = 'ClimbSphere Technologies', defaultDesc = '', defaultKeywords = '' }) {
  const { getCMSContent, loading } = useCMS();

  useEffect(() => {
    if (loading) return;

    // 1. Resolve SEO Metadata values
    const title = getCMSContent(`${pageKey}.seo.title`, defaultTitle);
    const description = getCMSContent(`${pageKey}.seo.description`, defaultDesc);
    const keywords = getCMSContent(`${pageKey}.seo.keywords`, defaultKeywords);
    const faqsRaw = getCMSContent(`${pageKey}.aeo.faqs`, null);

    // 2. Update Title
    const originalTitle = document.title;
    document.title = title;

    // 3. Update Meta Description
    let metaDesc = document.querySelector('meta[name="description"]');
    const originalDesc = metaDesc ? metaDesc.getAttribute('content') : '';
    if (description) {
      if (!metaDesc) {
        metaDesc = document.createElement('meta');
        metaDesc.setAttribute('name', 'description');
        document.head.appendChild(metaDesc);
      }
      metaDesc.setAttribute('content', description);
    }

    // 4. Update Meta Keywords
    let metaKeywords = document.querySelector('meta[name="keywords"]');
    const originalKeywords = metaKeywords ? metaKeywords.getAttribute('content') : '';
    if (keywords) {
      if (!metaKeywords) {
        metaKeywords = document.createElement('meta');
        metaKeywords.setAttribute('name', 'keywords');
        document.head.appendChild(metaKeywords);
      }
      metaKeywords.setAttribute('content', keywords);
    }

    // 4b. Update Canonical URL
    let canonicalLink = document.querySelector('link[rel="canonical"]');
    const currentUrl = `https://climbsphere.ai${window.location.pathname}`;
    if (!canonicalLink) {
      canonicalLink = document.createElement('link');
      canonicalLink.setAttribute('rel', 'canonical');
      document.head.appendChild(canonicalLink);
    }
    canonicalLink.setAttribute('href', currentUrl);

    // 4c. Update Open Graph Tags
    const updateOgTag = (property, content) => {
      let tag = document.querySelector(`meta[property="${property}"]`);
      if (content) {
        if (!tag) {
          tag = document.createElement('meta');
          tag.setAttribute('property', property);
          document.head.appendChild(tag);
        }
        tag.setAttribute('content', content);
      }
    };

    updateOgTag('og:title', title);
    updateOgTag('og:description', description);
    updateOgTag('og:url', currentUrl);
    updateOgTag('og:type', 'website');
    updateOgTag('og:image', 'https://climbsphere.ai/images/favicon-192.png');

    // 5. Inject JSON-LD AEO FAQ Schema if present
    let scriptTag = document.getElementById(`aeo-faq-schema-${pageKey}`);
    if (faqsRaw) {
      try {
        const faqs = typeof faqsRaw === 'string' ? JSON.parse(faqsRaw) : faqsRaw;
        if (Array.isArray(faqs) && faqs.length > 0) {
          const schemaData = {
            '@context': 'https://schema.org',
            '@type': 'FAQPage',
            'mainEntity': faqs.map(faq => ({
              '@type': 'Question',
              'name': faq.question,
              'acceptedAnswer': {
                '@type': 'Answer',
                'text': faq.answer
              }
            }))
          };

          if (!scriptTag) {
            scriptTag = document.createElement('script');
            scriptTag.id = `aeo-faq-schema-${pageKey}`;
            scriptTag.type = 'application/ld+json';
            document.head.appendChild(scriptTag);
          }
          scriptTag.text = JSON.stringify(schemaData);
        }
      } catch (err) {
        console.error('Failed to parse FAQ schema for page:', pageKey, err);
      }
    }

    // 6. Inject JSON-LD ProfessionalService LocalBusiness Schema on Home Page
    let localBusinessScript = document.getElementById('local-business-schema');
    if (pageKey === 'home') {
      try {
        const localBusinessData = {
          '@context': 'https://schema.org',
          '@type': 'ProfessionalService',
          '@id': 'https://climbsphere.ai/#organization',
          'name': 'ClimbSphere Technologies',
          'url': 'https://climbsphere.ai',
          'logo': 'https://climbsphere.ai/images/climbsphere-logo-header.png',
          'image': 'https://climbsphere.ai/images/climbsphere-logo-header.png',
          'description': 'ClimbSphere Technologies is a premium B2B IT services and technology consulting company in Chennai, India. Specializing in digital transformation, HCM/HR Tech adoption, and Service Desk ticketing setups.',
          'address': {
            '@type': 'PostalAddress',
            'streetAddress': '1E, 1st Floor, Eldorado Building, Nungambakkam',
            'addressLocality': 'Chennai',
            'addressRegion': 'Tamil Nadu',
            'postalCode': '600034',
            'addressCountry': 'IN'
          },
          'geo': {
            '@type': 'GeoCoordinates',
            'latitude': 13.061021,
            'longitude': 80.247478
          },
          'telephone': '+91 861 048 6636',
          'email': 'sales@climbsphere.ai',
          'priceRange': '$$',
          'openingHoursSpecification': {
            '@type': 'OpeningHoursSpecification',
            'dayOfWeek': [
              'Monday',
              'Tuesday',
              'Wednesday',
              'Thursday',
              'Friday'
            ],
            'opens': '09:00',
            'closes': '18:00'
          },
          'sameAs': [
            'https://www.linkedin.com/company/climbsphere-technologies/'
          ]
        };

        if (!localBusinessScript) {
          localBusinessScript = document.createElement('script');
          localBusinessScript.id = 'local-business-schema';
          localBusinessScript.type = 'application/ld+json';
          document.head.appendChild(localBusinessScript);
        }
        localBusinessScript.text = JSON.stringify(localBusinessData);
      } catch (err) {
        console.error('Failed to inject local business schema:', err);
      }
    }

    // Cleanup on unmount or key change
    return () => {
      document.title = originalTitle;
      if (metaDesc) {
        if (originalDesc) {
          metaDesc.setAttribute('content', originalDesc);
        } else {
          metaDesc.remove();
        }
      }
      if (metaKeywords) {
        if (originalKeywords) {
          metaKeywords.setAttribute('content', originalKeywords);
        } else {
          metaKeywords.remove();
        }
      }

      if (canonicalLink) {
        canonicalLink.remove();
      }
      const ogProps = ['og:title', 'og:description', 'og:url', 'og:type', 'og:image'];
      ogProps.forEach(prop => {
        const tag = document.querySelector(`meta[property="${prop}"]`);
        if (tag) tag.remove();
      });
      
      const faqScript = document.getElementById(`aeo-faq-schema-${pageKey}`);
      if (faqScript) {
        faqScript.remove();
      }

      const bizScript = document.getElementById('local-business-schema');
      if (bizScript) {
        bizScript.remove();
      }
    };
  }, [pageKey, loading, getCMSContent, defaultTitle, defaultDesc, defaultKeywords]);

  return null;
}
