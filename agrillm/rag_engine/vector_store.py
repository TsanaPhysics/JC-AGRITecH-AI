"""
AgriLLM Vector Knowledge Store
Manages persistent chunks, embeddings, and similarity search for agricultural RAG.
"""

import os
import sqlite3
import json
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity
from .embeddings import AgriEmbedder

DB_PATH = os.path.join(os.path.dirname(os.path.dirname(__file__)), "data", "vector_store.db")

class AgriVectorStore:
    def __init__(self, db_path=DB_PATH):
        self.db_path = db_path
        self.embedder = AgriEmbedder()
        self.doc_cache = []
        self._init_db()
        self._load_and_index()

    def _init_db(self):
        os.makedirs(os.path.dirname(self.db_path), exist_ok=True)
        with sqlite3.connect(self.db_path) as conn:
            cursor = conn.cursor()
            cursor.execute("""
                CREATE TABLE IF NOT EXISTS kb_chunks (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    chunk_id TEXT UNIQUE,
                    title TEXT,
                    content TEXT,
                    source TEXT,
                    category TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            """)
            conn.commit()

    def add_chunk(self, chunk_id, title, content, source, category="General Agriculture"):
        with sqlite3.connect(self.db_path) as conn:
            cursor = conn.cursor()
            cursor.execute("""
                INSERT OR REPLACE INTO kb_chunks (chunk_id, title, content, source, category)
                VALUES (?, ?, ?, ?, ?)
            """, (chunk_id, title, content, source, category))
            conn.commit()

    def _load_and_index(self):
        with sqlite3.connect(self.db_path) as conn:
            cursor = conn.cursor()
            cursor.execute("SELECT chunk_id, title, content, source, category FROM kb_chunks")
            rows = cursor.fetchall()

        self.doc_cache = []
        texts = []
        for r in rows:
            doc = {
                "chunk_id": r[0],
                "title": r[1],
                "content": r[2],
                "source": r[3],
                "category": r[4]
            }
            self.doc_cache.append(doc)
            texts.append(f"{r[1]} {r[2]} {r[4]}")

        if texts:
            self.embedder.fit(texts)

    def search(self, query, top_k=3, threshold=0.15):
        if not self.doc_cache:
            self._load_and_index()
        if not self.doc_cache:
            return []

        query_vec = self.embedder.embed_query(query).reshape(1, -1)
        corpus_texts = [f"{d['title']} {d['content']} {d['category']}" for d in self.doc_cache]
        corpus_vecs = self.embedder.transform(corpus_texts)

        sims = cosine_similarity(query_vec, corpus_vecs)[0]
        results = []
        for idx, score in enumerate(sims):
            if score >= threshold:
                doc = dict(self.doc_cache[idx])
                doc["score"] = float(score)
                results.append(doc)

        results.sort(key=lambda x: x["score"], reverse=True)
        return results[:top_k]
