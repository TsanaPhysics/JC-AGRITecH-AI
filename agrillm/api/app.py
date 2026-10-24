#!/usr/bin/env python3
"""
AgriLLM SmartFarm API Service & Web Server
Provides REST endpoints and static web dashboard serving for HandySense integration.
"""

import os
import sys
import json
import urllib.parse
from http.server import HTTPServer, SimpleHTTPRequestHandler

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
sys.path.insert(0, os.path.dirname(BASE_DIR))

from agrillm.models.model_adapter import AgriModelAdapter

PORT = 8089
WEB_DIR = os.path.join(BASE_DIR, "web_ui")

# Global adapter instance
adapter = AgriModelAdapter()

class AgriRequestHandler(SimpleHTTPRequestHandler):
    directory = WEB_DIR

    def _send_json(self, data, status=200):
        self.send_response(status)
        self.send_header("Content-Type", "application/json; charset=utf-8")
        self.send_header("Access-Control-Allow-Origin", "*")
        self.send_header("Access-Control-Allow-Methods", "GET, POST, OPTIONS")
        self.send_header("Access-Control-Allow-Headers", "Content-Type")
        self.end_headers()
        self.wfile.write(json.dumps(data, ensure_ascii=False).encode("utf-8"))

    def do_OPTIONS(self):
        self.send_response(200)
        self.send_header("Access-Control-Allow-Origin", "*")
        self.send_header("Access-Control-Allow-Methods", "GET, POST, OPTIONS")
        self.send_header("Access-Control-Allow-Headers", "Content-Type")
        self.end_headers()

    def do_GET(self):
        parsed = urllib.parse.urlparse(self.path)
        path = parsed.path

        if path == "/api/status":
            status_data = {
                "status": "online",
                "system": "AgriLLM SmartFarm Copilot",
                "version": "1.0.0",
                "model_status": "Ready",
                "vector_store_chunks": len(adapter.retriever.store.doc_cache),
                "active_config": adapter.config.get("agrillm_hf", {})
            }
            self._send_json(status_data)
            return

        if path == "/api/kb/search":
            query_params = urllib.parse.parse_qs(parsed.query)
            q = query_params.get("q", [""])[0]
            top_k = int(query_params.get("top_k", [3])[0])
            results = adapter.retriever.store.search(q, top_k=top_k)
            self._send_json({"query": q, "count": len(results), "results": results})
            return

        if path == "/api/benchmark/latest":
            report_file = os.path.join(BASE_DIR, "eval", "benchmark_report.json")
            if os.path.exists(report_file):
                with open(report_file, "r", encoding="utf-8") as f:
                    self._send_json(json.load(f))
            else:
                self._send_json({"error": "No benchmark report found. Please run benchmark first."}, status=404)
            return

        if path == "/api/data/sync":
            count = adapter.retriever.sync_documents()
            self._send_json({"status": "success", "synced_chunks": count})
            return

        # Fallback to static web dashboard files
        return super().do_GET()

    def do_POST(self):
        parsed = urllib.parse.urlparse(self.path)
        path = parsed.path

        content_len = int(self.headers.get("Content-Length", 0))
        post_body = self.rfile.read(content_len).decode("utf-8")

        try:
            req_data = json.loads(post_body) if post_body else {}
        except Exception:
            req_data = {}

        if path == "/api/chat":
            query = req_data.get("query", "").strip()
            telemetry = req_data.get("telemetry", None)
            use_rag = req_data.get("use_rag", True)

            if not query and not telemetry:
                self._send_json({"error": "Query or telemetry is required"}, status=400)
                return

            if not query and telemetry:
                query = "กรุณาวิเคราะห์ค่าเซนเซอร์ HandySense และแนะนำแนวทางจัดการพืช"

            result = adapter.generate(query, telemetry=telemetry, use_rag=use_rag)
            self._send_json(result)
            return

        if path == "/api/agronomist-advice":
            telemetry = req_data.get("telemetry", {})
            crop_name = req_data.get("crop_name", "พืชทั่วไป")
            telemetry["crop_name"] = crop_name
            query = f"วิเคราะห์สุขภาพและสรีรวิทยาของ {crop_name} จากข้อมูลเซนเซอร์"
            result = adapter.generate(query, telemetry=telemetry, use_rag=True)
            self._send_json(result)
            return

        if path == "/api/benchmark/run":
            from agrillm.eval.benchmark_runner import run_benchmark, REPORT_FILE
            run_benchmark()
            if os.path.exists(REPORT_FILE):
                with open(REPORT_FILE, "r", encoding="utf-8") as f:
                    self._send_json(json.load(f))
            else:
                self._send_json({"status": "completed"})
            return

        if path == "/api/analytics/vpd":
            import math
            temp = float(req_data.get("air_temp", 30.0))
            rh = float(req_data.get("air_humidity", 70.0))
            ph = float(req_data.get("soil_ph", 6.5))
            sm = float(req_data.get("soil_moisture", 50.0))
            n = float(req_data.get("soil_n", 40))
            p = float(req_data.get("soil_p", 25))
            k = float(req_data.get("soil_k", 80))

            # Tetens formula for saturated vapor pressure
            vp_sat = 0.61078 * math.exp((17.27 * temp) / (temp + 237.3))
            vp_act = vp_sat * (rh / 100.0)
            vpd = max(0.0, vp_sat - vp_act)

            if vpd < 0.4:
                vpd_status = "danger"
                vpd_label = "ความชื้นสูงเกินไป (Low Transpiration) เสี่ยงเชื้อราก่อโรค"
            elif vpd <= 1.25:
                vpd_status = "optimal"
                vpd_label = "สมบูรณ์แบบ (Optimal Transpiration) พืชสังเคราะห์แสงและดูดปุ๋ยได้ดีที่สุด"
            elif vpd <= 1.6:
                vpd_status = "warning"
                vpd_label = "อากาศแห้งปานกลาง พืชเริ่มคายน้ำสูง แนะนำพ่นหมอกลดอุณหภูมิ"
            else:
                vpd_status = "danger"
                vpd_label = "อากาศแห้งแล้งจัด (Extreme Vapor Stress) ปากใบปิดชะงักการโต"

            analytics_data = {
                "air_temp": temp,
                "air_humidity": rh,
                "vpd_kpa": round(vpd, 3),
                "vpd_status": vpd_status,
                "vpd_label": vpd_label,
                "soil_summary": {
                    "ph": ph,
                    "ph_status": "optimal" if 5.5 <= ph <= 6.8 else ("danger" if ph < 5.2 else "warning"),
                    "moisture": sm,
                    "npk_ratio": f"{int(n)}:{int(p)}:{int(k)}"
                }
            }
            self._send_json(analytics_data)
            return

        self._send_json({"error": "Endpoint not found"}, status=404)

def run_server(port=PORT):
    server_address = ("0.0.0.0", port)
    httpd = HTTPServer(server_address, AgriRequestHandler)
    print(f" AgriLLM Server running at http://localhost:{port}/")
    print(f" Serving Web Dashboard from {WEB_DIR}")
    print(f" REST API endpoints available at http://localhost:{port}/api/")
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print("\nShutting down AgriLLM Server...")
        httpd.server_close()

if __name__ == "__main__":
    run_server()
