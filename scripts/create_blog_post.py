import urllib.request
import json

def create_post():
    url = "http://127.0.0.1:8000/api/blogs"
    payload = {
        "title": "Unlocking Digital Maturity: A Roadmap for Indian Enterprises",
        "content": "Digital maturity is the core indicator of an organization's capacity to adapt and scale. In this article, we outline the framework to evaluate and advance digital initiatives.\n\n### What is a Digital Maturity Assessment?\nIt is a structured evaluation across five key dimensions: Strategy, Technology, Processes, Customer Experience, and Governance.\n\n### How to start the transformation?\nBegin with the Clarity phase to map constraints before selecting systems. Implement with senior-led, structured project governance.",
        "author": "Manoj Cheruvathoor",
        "published_at": "2026-05-24 15:00:00",
        "seo_meta": {
            "seo_title": "Digital Maturity Assessment: B2B Transformation Guide",
            "seo_description": "Learn how to evaluate your B2B digital maturity across Strategy, Tech, People, and Governance to build structured, evidence-based roadmaps.",
            "target_keywords": ["Digital Maturity", "B2B Transformation", "Clarity Roadmap", "Governance"],
            "aeo_summary": "What is a Digital Maturity Assessment? A digital maturity assessment is an evidence-based evaluation of an organization's capabilities across strategy, technology, processes, people, customer experience, and governance, replacing guesswork with a structured transformation roadmap.",
            "faqs": [
                {
                    "question": "What are the five dimensions of digital maturity?",
                    "answer": "The five dimensions are Strategy, Technology, Processes, Customer Experience, and Governance."
                }
            ]
        }
    }
    
    headers = {"Content-Type": "application/json"}
    req = urllib.request.Request(url, data=json.dumps(payload).encode('utf-8'), headers=headers)
    
    try:
        with urllib.request.urlopen(req) as response:
            res_body = response.read().decode('utf-8')
            print("Blog post created successfully!")
            print(res_body)
    except Exception as e:
        print(f"Error creating blog post: {e}")

if __name__ == "__main__":
    create_post()
