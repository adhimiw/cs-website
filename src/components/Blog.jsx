import './Blog.css';

const posts = [
  {
    title: "Workday's Last Workday? The Goliath Has Heard This Before",
    excerpt: "A Practitioner's Take On The A16z Thesis, The CHRO's Crossroads, And What",
    date: 'May 8, 2026',
    href: 'https://climbsphere.whydev.co.in/workday-ai-disruption/',
    image: '/images/blog_post_01.png',
  },
  {
    title: 'Why Great Software Often Fails Great People: Lessons From The',
    excerpt: 'It Is Entirely Possible To Fall In Love With An HCM Product',
    date: 'May 8, 2026',
    href: 'https://climbsphere.whydev.co.in/post-demo-paradox-hr-tech/',
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
  return (
    <section className="blog" id="blog">
      <div className="container">
        <div className="blog-header text-center" data-aos="fade-up" data-aos-delay="200">
          <span className="blog-sub-title">NEWS & BLOGS</span>
          <h2 className="section-title">Read Our Latest Updates</h2>
        </div>

        <div className="blog-grid">
          {posts.map((post, index) => (
            <article 
              key={post.title} 
              className="blog-card"
              data-aos="fade-up"
              data-aos-delay={300 + index * 200}
            >
              <div className="blog-thumb">
                <a href={post.href}>
                  <img src={post.image} alt={post.title} />
                </a>
                <span className="blog-tag">Blog</span>
              </div>
              <div className="blog-content">
                <h3 className="blog-title">
                  <a href={post.href}>{post.title}</a>
                </h3>
                <p className="blog-excerpt">{post.excerpt}</p>
                <div className="blog-meta">
                  <div className="blog-author">
                    <span className="blog-admin" aria-hidden="true" />
                    <a href="https://climbsphere.whydev.co.in/author/admin/">Admin</a>
                  </div>
                  <span className="blog-date">
                    <CalendarIcon />
                    {post.date}
                  </span>
                </div>
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
