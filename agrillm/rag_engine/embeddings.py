"""
AgriLLM Semantic Embedding & Featurizer
Provides vector representations of agricultural documents and queries.
"""

import re
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer

class AgriEmbedder:
    def __init__(self):
        self.vectorizer = TfidfVectorizer(
            lowercase=True,
            token_pattern=r"(?u)\b\w+\b",
            ngram_range=(1, 2)
        )
        self.is_fitted = False

    def fit(self, documents):
        """Fits vectorizer on corpus of agricultural texts."""
        if not documents:
            return
        self.vectorizer.fit(documents)
        self.is_fitted = True

    def transform(self, texts):
        """Transforms a list of texts into dense vectors."""
        if not self.is_fitted:
            # Fit on fly if not yet fitted
            self.fit(texts)
        sparse_vec = self.vectorizer.transform(texts)
        return sparse_vec.toarray()

    def embed_query(self, query):
        """Embeds a single query."""
        if not self.is_fitted:
            return np.zeros((1, 10))
        return self.vectorizer.transform([query]).toarray()[0]
