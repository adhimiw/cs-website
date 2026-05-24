from playwright.sync_api import sync_playwright

def verify_new_blog():
    url = "http://127.0.0.1:5174/blog/unlocking-digital-maturity-a-roadmap-for-indian-enterprises"
    print(f"Connecting to: {url}")
    
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        page.goto(url)
        page.wait_for_load_state("networkidle")
        page.wait_for_timeout(1000)
        
        # Extract title and headings
        doc_title = page.title()
        blog_title = page.locator(".blog-read-title").inner_text()
        
        # Extract description meta
        desc_meta = page.locator('meta[name="description"]').get_attribute("content")
        
        # Extract Callout
        callout_text = page.locator(".aeo-answer-callout .callout-text").inner_text()
        
        # Extract FAQs
        faq_questions = [btn.inner_text().strip() for btn in page.locator(".faq-question-btn").all()]
        
        print("\n==============================================================")
        print("FRONTEND VERIFICATION OF NEW DYNAMIC BLOG")
        print("==============================================================")
        print(f"  - Browser Document Title: '{doc_title}'")
        print(f"  - Rendered Blog Title:    '{blog_title}'")
        print(f"  - Meta Description:       '{desc_meta}'")
        print(f"  - AEO Quick Answer Box:   '{callout_text}'")
        print(f"  - Rendered AEO/GEO FAQs:  {faq_questions}")
        print("==============================================================\n")
        
        browser.close()

if __name__ == "__main__":
    verify_new_blog()
