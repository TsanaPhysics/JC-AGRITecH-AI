"""
AgriLLM Context Retriever and Augmenter
Indexes knowledge bases and builds context-grounded prompts for agricultural queries.
"""

import os
import glob
import json
from .vector_store import AgriVectorStore

class AgriRetriever:
    def __init__(self):
        self.store = AgriVectorStore()
        self.base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
        self.kb_dir = os.path.join(self.base_dir, "data", "kb_docs")
        self.qa_file = os.path.join(self.base_dir, "data", "processed", "agrillm_curated_qa.json")

    def sync_documents(self):
        """Scans kb_docs markdown files and QA pairs and indexes into vector store."""
        count = 0
        # 1. Index Markdown Knowledge Docs
        md_files = glob.glob(os.path.join(self.kb_dir, "*.md"))
        for fpath in md_files:
            fname = os.path.basename(fpath)
            with open(fpath, "r", encoding="utf-8") as f:
                content = f.read()
            # Split sections by header (## )
            sections = content.split("## ")
            for i, sec in enumerate(sections):
                if not sec.strip():
                    continue
                lines = sec.strip().split("\n")
                title = lines[0].replace("#", "").strip()
                body = "\n".join(lines[1:]).strip()
                chunk_id = f"DOC-{fname}-{i}"
                self.store.add_chunk(chunk_id, title, body, source=fname, category="SmartFarm Guidelines")
                count += 1

        # 2. Index Curated Q&A pairs
        if os.path.exists(self.qa_file):
            with open(self.qa_file, "r", encoding="utf-8") as f:
                qa_data = json.load(f)
            for item in qa_data:
                chunk_id = item["id"]
                title = f"{item.get('category', 'Agri QA')}: {item['question']}"
                content = f"คำถาม: {item['question']}\nคำแนะนำที่ถูกต้อง: {item['answer']}"
                self.store.add_chunk(chunk_id, title, content, source=item.get("source", "AgriLLM-146k"), category=item.get("category", "QA"))
                count += 1

        self.store._load_and_index()
        return count

    def get_context_for_query(self, query, top_k=3):
        results = self.store.search(query, top_k=top_k)
        if not results:
            return ""

        context_blocks = []
        for i, res in enumerate(results, 1):
            block = f"[ข้อมูลอ้างอิงที่ {i}] แหล่งที่มา: {res['source']} ({res['category']})\n{res['content']}"
            context_blocks.append(block)

        return "\n\n".join(context_blocks)
