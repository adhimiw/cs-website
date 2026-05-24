import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { getApiUrl, getBlogImageUrl } from '../context/CMSContext';
import './Blog.css';

const defaultPosts = [
  {
    title: "Workday's Last Workday? The Goliath Has Heard This Before",
    excerpt: "A Practitioner's Take On The A16z Thesis, The CHRO's Crossroads, And What",
    date: 'May 8, 2026',
    slug: 'workday-ai-disruption',
    image: '/images/blog_post_01.png',
  },
  {
    title: 'Why Great Software Often Fails Great People: Lessons From The Post-Demo Paradox',
    excerpt: 'It Is Entirely Possible To Fall In Love With An HCM Product',
    date: 'May 8, 2026',
    slug: 'post-demo-paradox-hr-tech',
    image: '/images/blog_post_02.jpg',
  },
];

function CalendarIcon() {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" width="16" height="16">
      <rect x="3" y="4" width="18" height="16" rx="2" ry="2" stroke="currentColor" strokeWidth="2" fill="none" />
      <line x1="16" y1="2" x2="16" y2="6" stroke="currentColor" strokeWidth="2" />
      <line x1="8" y1="2" x2="8" y2="6" stroke="currentColor" strokeWidth="2" />
      <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" strokeWidth="2" />
    </svg>
  );
}

export default function Blog() {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchPosts() {
      try {
        const res = await fetch(getApiUrl('/api/blogs'));
        if (!res.ok) throw new Error('Failed to fetch blogs');
        const data = await res.json();
        if (data && data.length > 0) {
          setPosts(data);
        } else {
          setPosts(defaultPosts);
        }
      } catch (err) {
        console.error('Error fetching blogs, using defaults:', err);
        setPosts(defaultPosts);
      } finally {
        setLoading(false);
      }
    }
    fetchPosts();
  }, []);

  return (
    <section className="blog" id="blog">
      <div className="container">
        <div className="blog-header text-center" data-aos="fade-up" data-aos-delay="200">
          <span className="blog-sub-title">NEWS & BLOGS</span>
          <h2 className="section-title">Read Our Latest Updates</h2>
        </div>

        {loading ? (
          <div className="text-center" style={{ padding: '40px 0', fontSize: '18px', color: 'var(--text-secondary)' }}>
            Loading blog posts...
          </div>
        ) : (
          <div className="blog-grid">
            {posts.map((post, index) => (
              <article 
                key={post.slug || post.title} 
                className="blog-card"
                data-aos="fade-up"
                data-aos-delay={300 + index * 200}
              >
                <div className="blog-thumb">
                  <Link to={`/blog/${post.slug}`}>
                    <img src={getBlogImageUrl(post.image)} alt={post.title} />
                  </Link>
                  <span className="blog-tag">Blog</span>
                </div>
                <div className="blog-content">
                  <h3 className="blog-title">
                    <Link to={`/blog/${post.slug}`}>{post.title}</Link>
                  </h3>
                  <p className="blog-excerpt">
                    {post.excerpt || (post.content ? post.content.replace(/<[^>]*>/g, '').substring(0, 120) + '...' : '')}
                  </p>
                  <div className="blog-meta">
                    <div className="blog-author">
                      <span className="blog-admin" aria-hidden="true" />
                      <span>{post.author || 'Admin'}</span>
                    </div>
                    <span className="blog-date">
                      <CalendarIcon />
                      {post.published_at ? new Date(post.published_at).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                      }) : post.date || 'May 8, 2026'}
                    </span>
                  </div>
                </div>
              </article>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}
