#!/usr/bin/env python3
"""
Consensus MCP Server
Provides integration with Consensus.app API (400M+ research papers, AI summaries & consensus).
API Key: ak_1A9YJP15Q4SKT72M692KYPJM3Y6RMGBF
"""

import sys
import os
import json
import urllib.request
import urllib.parse

API_KEY = os.environ.get("CONSENSUS_API_KEY", "ak_1A9YJP15Q4SKT72M692KYPJM3Y6RMGBF")
API_BASE_URL = "https://api.consensus.app/v1/search"

def search_consensus(query: str, year_min: int = None, year_max: int = None, page_size: int = 10):
    params = {"query": query}
    if year_min is not None:
        params["year_min"] = year_min
    if year_max is not None:
        params["year_max"] = year_max
    if page_size:
        params["page_size"] = page_size

    url = f"{API_BASE_URL}?{urllib.parse.urlencode(params)}"
    req = urllib.request.Request(
        url,
        headers={
            "x-api-key": API_KEY,
            "User-Agent": "Consensus-MCP/1.0",
            "Accept": "application/json"
        }
    )

    try:
        with urllib.request.urlopen(req, timeout=15) as response:
            data = json.loads(response.read().decode("utf-8"))
    except urllib.error.HTTPError as e:
        error_msg = e.read().decode("utf-8", errors="replace")
        return f"Consensus API HTTP Error {e.code}: {error_msg}"
    except Exception as e:
        return f"Consensus API Error: {str(e)}"

    results = data.get("results", [])
    if not results:
        return f"No papers found for query: '{query}'"

    formatted_outputs = [
        f"### 🔬 Consensus Academic Search Results for: *{query}*",
        f"**Found {len(results)} peer-reviewed papers** (Total reported: {data.get('total_results', len(results))})\n"
    ]

    for idx, paper in enumerate(results, 1):
        title = paper.get("title", "Untitled")
        year = paper.get("publish_year") or "N/A"
        journal = paper.get("journal_name") or "Unknown Journal"
        doi = paper.get("doi") or ""
        url_link = paper.get("url") or (f"https://doi.org/{doi}" if doi else "")
        citations = paper.get("citation_count", 0)
        sjr = paper.get("sjr_best_quartile")
        study_type = paper.get("study_type") or "Scientific Study"
        takeaway = paper.get("takeaway") or ""
        abstract = paper.get("abstract") or ""

        authors = paper.get("authors") or []
        if isinstance(authors, list):
            if len(authors) > 3:
                author_str = ", ".join(authors[:3]) + " et al."
            else:
                author_str = ", ".join(authors)
        else:
            author_str = str(authors)

        badge_parts = [f"📅 {year}", f"📖 {journal}"]
        if sjr:
            badge_parts.append(f"⭐ {sjr}")
        if citations:
            badge_parts.append(f"📊 {citations} citations")
        if study_type:
            badge_parts.append(f"🧪 {study_type}")

        meta_line = " | ".join(badge_parts)

        entry = [
            f"#### {idx}. [{title}]({url_link})",
            f"**Authors:** {author_str}",
            f"**Metadata:** {meta_line}"
        ]

        if takeaway:
            entry.append(f"💡 **Consensus AI Key Finding / Takeaway:**\n> {takeaway.strip()}")
        elif abstract:
            snippet = abstract[:300] + "..." if len(abstract) > 300 else abstract
            entry.append(f"📄 **Abstract Snippet:**\n> {snippet.strip()}")

        if doi:
            entry.append(f"🔗 **DOI:** [{doi}](https://doi.org/{doi})")

        formatted_outputs.append("\n".join(entry) + "\n---")

    return "\n\n".join(formatted_outputs)

def handle_json_rpc():
    """Stdio JSON-RPC 2.0 loop for Model Context Protocol (MCP)"""
    for line in sys.stdin:
        line = line.strip()
        if not line:
            continue
        try:
            req = json.loads(line)
        except Exception:
            continue

        msg_id = req.get("id")
        method = req.get("method")
        params = req.get("params", {})

        if method == "initialize":
            res = {
                "jsonrpc": "2.0",
                "id": msg_id,
                "result": {
                    "protocolVersion": "2024-11-05",
                    "capabilities": {
                        "tools": {}
                    },
                    "serverInfo": {
                        "name": "consensus",
                        "version": "1.0.0"
                    }
                }
            }
            sys.stdout.write(json.dumps(res) + "\n")
            sys.stdout.flush()

        elif method == "notifications/initialized":
            # Client notification, no response required
            pass

        elif method == "tools/list":
            res = {
                "jsonrpc": "2.0",
                "id": msg_id,
                "result": {
                    "tools": [
                        {
                            "name": "consensus_search",
                            "description": "Search 400M+ peer-reviewed scientific papers, literature synthesis, and AI consensus takeaways via Consensus.app. Returns paper titles, authors, journals, SJR quartiles (Q1-Q4), citations, DOI links, and Consensus AI findings.",
                            "inputSchema": {
                                "type": "object",
                                "properties": {
                                    "query": {
                                        "type": "string",
                                        "description": "Scientific question, research topic, or inquiry (e.g. 'biochar soil water retention', 'trichoderma biocontrol durian root rot')"
                                    },
                                    "year_min": {
                                        "type": "integer",
                                        "description": "Optional minimum publication year filter (e.g. 2018)"
                                    },
                                    "year_max": {
                                        "type": "integer",
                                        "description": "Optional maximum publication year filter (e.g. 2026)"
                                    },
                                    "page_size": {
                                        "type": "integer",
                                        "description": "Number of papers to retrieve (default: 10, max: 20)"
                                    }
                                },
                                "required": ["query"]
                            }
                        }
                    ]
                }
            }
            sys.stdout.write(json.dumps(res) + "\n")
            sys.stdout.flush()

        elif method == "tools/call":
            tool_name = params.get("name")
            tool_args = params.get("arguments", {})

            if tool_name == "consensus_search":
                q = tool_args.get("query", "")
                y_min = tool_args.get("year_min")
                y_max = tool_args.get("year_max")
                size = tool_args.get("page_size", 10)

                result_text = search_consensus(q, year_min=y_min, year_max=y_max, page_size=size)

                res = {
                    "jsonrpc": "2.0",
                    "id": msg_id,
                    "result": {
                        "content": [
                            {
                                "type": "text",
                                "text": result_text
                            }
                        ],
                        "isError": False
                    }
                }
            else:
                res = {
                    "jsonrpc": "2.0",
                    "id": msg_id,
                    "error": {
                        "code": -32601,
                        "message": f"Tool '{tool_name}' not found"
                    }
                }
            sys.stdout.write(json.dumps(res) + "\n")
            sys.stdout.flush()

        elif method == "ping":
            res = {
                "jsonrpc": "2.0",
                "id": msg_id,
                "result": {}
            }
            sys.stdout.write(json.dumps(res) + "\n")
            sys.stdout.flush()

        else:
            if msg_id is not None:
                res = {
                    "jsonrpc": "2.0",
                    "id": msg_id,
                    "error": {
                        "code": -32601,
                        "message": f"Method '{method}' not implemented"
                    }
                }
                sys.stdout.write(json.dumps(res) + "\n")
                sys.stdout.flush()

if __name__ == "__main__":
    if len(sys.argv) > 1 and sys.argv[1] == "--search":
        # CLI test mode
        q = " ".join(sys.argv[2:]) if len(sys.argv) > 2 else "smart farming IoT"
        print(search_consensus(q))
    else:
        # MCP Stdio Mode
        handle_json_rpc()
