import sys
import json
from playwright.sync_api import sync_playwright

EXPECTED_METADATA = {
    "home": {
        "url_path": "/",
        "title": "ClimbSphere | B2B Business Transformation & HR Tech Consulting",
        "description": "ClimbSphere specializes in B2B digital transformation, HCM/HR Tech adoption, Service Desk ticketing systems, and program governance. Partnering to deliver scale and impact.",
        "keywords": "digital transformation, HCM consulting, HR Tech adoption, service desk ticketing, project management, Chennai, B2B consulting",
        "quick_answer": "ClimbSphere is an enterprise B2B technology consulting agency based in Chennai, India. Specializing in digital transformation, HCM/HR Technology implementations, Service Desk ticketing setups, and agile program governance, ClimbSphere replaces manual guesswork with structured, evidence-based roadmaps."
    },
    "about": {
        "url_path": "/about",
        "title": "About Us | ClimbSphere Technologies",
        "description": "With nearly 50 years of combined global experience, ClimbSphere leads large-scale HCM and Service Desk transformation programs across India, North America, Europe, APAC, and the Middle East.",
        "keywords": "Manoj Cheruvathoor, Ranjit Kumar, Barath Silvester, climbsphere team, HCM transformation, enterprise SaaS, global delivery",
        "quick_answer": "ClimbSphere is a B2B transformation agency led by Manoj Cheruvathoor, Ranjit Kumar, and Barath Silvester. The leadership team has nearly 50 years of combined global experience delivering large-scale HCM, Service Desk, and operations transformation programs across major international markets."
    },
    "services": {
        "url_path": "/services",
        "title": "Our Services | ClimbSphere Technologies",
        "description": "Explore ClimbSphere's core B2B services, including Digital Maturity Assessments, HR Technology adoption, Service Desk ticketing systems, and Agile/Hybrid Project Management governance.",
        "keywords": "Digital Maturity Assessment, HR technology platform selection, ticketing system SLA, enterprise project governance, product partnerships",
        "quick_answer": "ClimbSphere offers core services across two segments. For businesses, we provide Digital Maturity Assessments, Digital Transformation strategy, HR Technology platform selection, Service Desk ticketing setups, and Project Management. For technology partners, we provide Product Partnerships and Professional Services."
    },
    "contact": {
        "url_path": "/contact",
        "title": "Contact Us | ClimbSphere Technologies",
        "description": "Contact the ClimbSphere team at sales@climbsphere.ai or visit our office at Eldorado Building, Nungambakkam, Chennai, to discuss your B2B transformation needs.",
        "keywords": "contact climbsphere, climbsphere chennai address, climbsphere email, climbsphere phone number",
        "quick_answer": "You can contact ClimbSphere by emailing sales@climbsphere.ai or calling +91 861 048 6636. Their corporate office is located at 1E, 1st Floor, Eldorado Building, Nungambakkam, Chennai - 600034, Tamil Nadu, India."
    }
}

def run_audit():
    print("==============================================================")
    print("STARTING SEO / GEO / AEO AUTOMATED SYSTEM AUDIT (LOCAL)")
    print("==============================================================")
    
    base_url = "http://127.0.0.1:5174"
    failed_checks = []
    
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        
        # 1. Audit core pages
        for page_key, data in EXPECTED_METADATA.items():
            url = f"{base_url}{data['url_path']}"
            print(f"\nAuditing Page: {page_key.upper()} ({url})")
            
            # Go to page and wait for network idle to ensure CMS fetches are completed
            page.goto(url)
            page.wait_for_load_state("networkidle")
            page.wait_for_timeout(1000)  # Extra buffer for dynamic states
            
            # Audit Document Title
            current_title = page.title()
            print(f"  - Title: '{current_title}'")
            if current_title != data["title"]:
                err = f"Title mismatch on {page_key}. Expected: '{data['title']}', Got: '{current_title}'"
                print(f"    [FAIL] {err}")
                failed_checks.append(err)
            else:
                print("    [PASS] Title matches CMS data")
                
            # Title Length Audit (Google SEO recommends 50-60 chars)
            title_len = len(current_title)
            if 10 <= title_len <= 65:
                print(f"    [PASS] Title length is optimal: {title_len} chars")
            else:
                print(f"    [WARN] Title length {title_len} is outside optimal Google range (50-60 chars)")

            # Audit Meta Description
            desc_element = page.locator('meta[name="description"]')
            if desc_element.count() > 0:
                current_desc = desc_element.first.get_attribute("content")
                print(f"  - Description: '{current_desc}'")
                if current_desc != data["description"]:
                    err = f"Description mismatch on {page_key}. Expected: '{data['description']}', Got: '{current_desc}'"
                    print(f"    [FAIL] {err}")
                    failed_checks.append(err)
                else:
                    print("    [PASS] Description matches CMS data")
                
                desc_len = len(current_desc)
                if 120 <= desc_len <= 165:
                    print(f"    [PASS] Description length is optimal: {desc_len} chars")
                else:
                    print(f"    [WARN] Description length {desc_len} is outside optimal Google range (120-160 chars)")
            else:
                err = f"Meta description tag missing on {page_key}"
                print(f"    [FAIL] {err}")
                failed_checks.append(err)

            # Audit Meta Keywords
            keywords_element = page.locator('meta[name="keywords"]')
            if keywords_element.count() > 0:
                current_keywords = keywords_element.first.get_attribute("content")
                print(f"  - Keywords: '{current_keywords}'")
                if current_keywords != data["keywords"]:
                    err = f"Keywords mismatch on {page_key}. Expected: '{data['keywords']}', Got: '{current_keywords}'"
                    print(f"    [FAIL] {err}")
                    failed_checks.append(err)
                else:
                    print("    [PASS] Keywords match CMS data")
            else:
                err = f"Meta keywords tag missing on {page_key}"
                print(f"    [FAIL] {err}")
                failed_checks.append(err)

            # Audit AEO Featured Snippet Callout (Direct Answer Box)
            callout = page.locator(".aeo-answer-callout")
            if callout.count() > 0:
                text_locator = callout.locator(".callout-text")
                if text_locator.count() > 0:
                    current_answer = text_locator.first.inner_text().strip()
                    print(f"  - AEO Quick Answer Callout: '{current_answer}'")
                    if current_answer != data["quick_answer"]:
                        err = f"AEO Quick Answer mismatch on {page_key}. Expected: '{data['quick_answer']}', Got: '{current_answer}'"
                        print(f"    [FAIL] {err}")
                        failed_checks.append(err)
                    else:
                        print("    [PASS] AEO Callout box displays correct factual direct answer")
                else:
                    err = f"AEO callout text element not found on {page_key}"
                    print(f"    [FAIL] {err}")
                    failed_checks.append(err)
            else:
                err = f"AEO quick answer callout container (.aeo-answer-callout) missing on {page_key}"
                print(f"    [FAIL] {err}")
                failed_checks.append(err)

            # Audit JSON-LD Schema (AEO/GEO Structured Data)
            schema_script = page.locator(f"script#aeo-faq-schema-{page_key}")
            if schema_script.count() > 0:
                script_text = schema_script.first.inner_text().strip()
                try:
                    schema_json = json.loads(script_text)
                    print(f"  - Schema Detected: {schema_json.get('@type')} with {len(schema_json.get('mainEntity', []))} FAQs")
                    if schema_json.get("@type") == "FAQPage":
                        print("    [PASS] Schema JSON-LD FAQPage validates successfully")
                    else:
                        err = f"Invalid schema @type on {page_key}: expected FAQPage"
                        print(f"    [FAIL] {err}")
                        failed_checks.append(err)
                except Exception as e:
                    err = f"Failed to parse injected FAQ schema JSON on {page_key}: {e}"
                    print(f"    [FAIL] {err}")
                    failed_checks.append(err)
            else:
                err = f"Schema script tags (FAQPage) missing on {page_key}"
                print(f"    [FAIL] {err}")
                failed_checks.append(err)

        # 2. Audit Chatbot Widget Brand Phrasing
        print("\nAuditing Chatbot Widget B2B Phrasing...")
        page.goto(base_url)
        page.wait_for_load_state("networkidle")
        
        chat_fab = page.locator(".chatbot-fab")
        if chat_fab.count() > 0:
            print("  - Chatbot FAB button found")
            chat_fab.click()
            page.wait_for_timeout(500)
            
            chat_window = page.locator(".chatbot-window")
            if chat_window.is_visible():
                print("  - Chat window opened successfully")
                
                # Retrieve welcome message
                welcome_msg = page.locator(".chatbot-msg.assistant").first.inner_text().strip()
                print(f"  - Chatbot Greeting: '{welcome_msg}'")
                
                b2b_indicators = [
                    "B2B digital maturity assessments",
                    "HCM/HR Technology adoption",
                    "Service Desk automation",
                    "program governance"
                ]
                
                missing_indicators = [ind for ind in b2b_indicators if ind not in welcome_msg]
                if len(missing_indicators) == 0:
                    print("    [PASS] Chatbot greeting aligned with ClimbSphere B2B digital transformation services")
                else:
                    err = f"Chatbot greeting missing B2B brand indicators: {missing_indicators}"
                    print(f"    [FAIL] {err}")
                    failed_checks.append(err)
            else:
                err = "Chatbot window failed to open or become visible on click"
                print(f"    [FAIL] {err}")
                failed_checks.append(err)
        else:
            err = "Chatbot floating action button (.chatbot-fab) missing"
            print(f"    [FAIL] {err}")
            failed_checks.append(err)
            
        browser.close()
        
    print("\n==============================================================")
    print("AUDIT RESULTS SUMMARY")
    print("==============================================================")
    if len(failed_checks) == 0:
        print("ALL TESTS PASSED SUCCESSFULLY! The dynamic SEO/AEO/GEO optimization and chatbot B2B integration are working perfectly!")
        sys.exit(0)
    else:
        print(f"AUDIT FAILED with {len(failed_checks)} error(s):")
        for f in failed_checks:
            print(f"  - {f}")
        sys.exit(1)

if __name__ == "__main__":
    run_audit()
