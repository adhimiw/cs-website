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
      if (scriptTag) {
        scriptTag.remove();
      }
    };
  }, [pageKey, loading, getCMSContent, defaultTitle, defaultDesc, defaultKeywords]);

  return null;
}
