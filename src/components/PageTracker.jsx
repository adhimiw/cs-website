import { useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { getApiUrl } from '../context/CMSContext';

export default function PageTracker() {
  const location = useLocation();

  useEffect(() => {
    const trackPage = async () => {
      try {
        const fullUrl = window.location.href;
        const referrer = document.referrer;

        // Parse query params for UTM
        const urlParams = new URLSearchParams(window.location.search);
        const utmData = {
          utm_source: urlParams.get('utm_source'),
          utm_medium: urlParams.get('utm_medium'),
          utm_campaign: urlParams.get('utm_campaign'),
          utm_term: urlParams.get('utm_term'),
          utm_content: urlParams.get('utm_content'),
        };

        await fetch(getApiUrl('/api/track-visit'), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            url: fullUrl,
            referrer: referrer,
            ...utmData,
          }),
        });
      } catch (err) {
        console.warn('Failed to log page visit:', err);
      }
    };

    trackPage();
  }, [location.pathname, location.search]);

  return null;
}
