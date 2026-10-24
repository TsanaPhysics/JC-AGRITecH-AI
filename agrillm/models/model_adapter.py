"""
AgriLLM Model Adapter & Telemetry Context Injector
Connects RAG, sensor telemetry from HandySense, and LLM backends (Ollama/API/Expert).
"""

import os
import json
import urllib.request
import urllib.error
from ..rag_engine.retriever import AgriRetriever

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PROMPT_FILE = os.path.join(BASE_DIR, "models", "modelfiles", "agri_system_prompt.txt")
CONFIG_FILE = os.path.join(BASE_DIR, "config.json")

class AgriModelAdapter:
    def __init__(self):
        self.retriever = AgriRetriever()
        self.retriever.sync_documents()
        self.config = self._load_config()
        self.system_prompt = self._load_system_prompt()

    def _load_config(self):
        if os.path.exists(CONFIG_FILE):
            try:
                with open(CONFIG_FILE, "r", encoding="utf-8") as f:
                    return json.load(f)
            except Exception:
                pass
        return {}

    def _load_system_prompt(self):
        if os.path.exists(PROMPT_FILE):
            with open(PROMPT_FILE, "r", encoding="utf-8") as f:
                return f.read().strip()
        return "คุณคือ AgriLLM ผู้เชี่ยวชาญการเกษตรอัจฉริยะ"

    def format_telemetry(self, telemetry: dict) -> str:
        """Transforms HandySense sensor payload into agronomic contextual summary."""
        if not telemetry:
            return ""

        lines = ["=== ข้อมูลสภาพแวดล้อมและดินจากเซนเซอร์ HandySense ==="]
        if "crop_name" in telemetry:
            lines.append(f"- พืชเป้าหมาย: {telemetry['crop_name']}")
        if "soil_moisture" in telemetry:
            lines.append(f"- ความชื้นในดิน (Soil Moisture): {telemetry['soil_moisture']:.1f}%")
        if "soil_ph" in telemetry:
            lines.append(f"- ความเป็นกรด-ด่างดิน (pH): {telemetry['soil_ph']:.2f}")
        if "soil_ec" in telemetry:
            lines.append(f"- ค่าการนำไฟฟ้าดิน (EC): {telemetry['soil_ec']:.2f} mS/cm")
        if "soil_n" in telemetry and "soil_p" in telemetry and "soil_k" in telemetry:
            lines.append(f"- ธาตุอาหารในดิน (N-P-K): N={telemetry['soil_n']} mg/kg, P={telemetry['soil_p']} mg/kg, K={telemetry['soil_k']} mg/kg")
        if "air_temp" in telemetry:
            lines.append(f"- อุณหภูมิอากาศ (SHT45): {telemetry['air_temp']:.1f} °C")
        if "air_humidity" in telemetry:
            lines.append(f"- ความชื้นสัมพัทธ์ในอากาศ (SHT45): {telemetry['air_humidity']:.1f}%")
        if "light_lux" in telemetry:
            lines.append(f"- ความเข้มแสงแดด (BH1750): {telemetry['light_lux']:,.0f} Lux")

        return "\n".join(lines)

    def _call_ollama(self, prompt: str, model_name="qwen2.5:7b-instruct"):
        """Attempts to call local Ollama endpoint."""
        url = "http://127.0.0.1:11434/api/generate"
        payload = {
            "model": model_name,
            "prompt": prompt,
            "system": self.system_prompt,
            "stream": False,
            "options": {"temperature": 0.3}
        }
        try:
            req = urllib.request.Request(
                url,
                data=json.dumps(payload).encode("utf-8"),
                headers={"Content-Type": "application/json"}
            )
            with urllib.request.urlopen(req, timeout=8) as response:
                res_data = json.loads(response.read().decode("utf-8"))
                return res_data.get("response", "").strip()
        except Exception:
            return None

    def _expert_fallback_generate(self, user_query: str, context: str, telemetry: dict) -> str:
        """High-precision domain rule synthesis when external LLM is offline."""
        analysis_parts = []

        # Analyze sensor thresholds
        if telemetry:
            if "soil_ph" in telemetry:
                ph = telemetry["soil_ph"]
                if ph < 5.5:
                    analysis_parts.append(f"• **การจัดการค่า pH ดิน ({ph:.2f})**: ดินมีสภาพกรดจัด แนะนำใส่ปูนโดโลไมต์หรือปูนมาร์ล 100-200 กก./ไร่ และเติมเชื้อจุลินทรีย์ไตรโคเดอร์มาเพื่อป้องกันโรครากเน่า")
                elif ph > 7.5:
                    analysis_parts.append(f"• **การจัดการค่า pH ดิน ({ph:.2f})**: ดินมีสภาพด่าง พืชอาจขาดจุลธาตุเหล็กและสังกะสี ควรปรับปรุงด้วยการเติมอินทรียวัตถุหรือกำมะถันผง")
                else:
                    analysis_parts.append(f"• **ค่า pH ดิน ({ph:.2f})**: อยู่ในเกณฑ์เหมาะสมอย่างยิ่งต่อการดูดซึมธาตุอาหารหลัก (N-P-K)")

            if "soil_moisture" in telemetry:
                sm = telemetry["soil_moisture"]
                if sm < 35.0:
                    analysis_parts.append(f"• **การจัดการน้ำ (ความชื้น {sm:.1f}%)**: ดินแห้งเกินเกณฑ์มาตรฐาน (<35%) เสี่ยงต่อการชะงักการเจริญเติบโต ควรเปิดระบบน้ำหยด/มินิสปริงเกลอร์อัตโนมัติ")
                elif sm > 75.0:
                    analysis_parts.append(f"• **การจัดการน้ำ (ความชื้น {sm:.1f}%)**: ดินแฉะเกินไป (>75%) เสี่ยงต่อการขาดออกซิเจนในเขตรากและโรครากเน่า ควรระบายน้ำและชะลอการให้น้ำ")
                else:
                    analysis_parts.append(f"• **ความชื้นในดิน ({sm:.1f}%)**: อยู่ในเกณฑ์สมดุลดีเยี่ยมสำหรับการดูดซึมน้ำของรากพืช")

            if "air_humidity" in telemetry and telemetry["air_humidity"] > 80.0:
                rh = telemetry["air_humidity"]
                analysis_parts.append(f"• **ความเสี่ยงโรคพืช (ความชื้นสัมพัทธ์ {rh:.1f}%)**: สูงเกิน 80% เป็นสภาวะเสี่ยงต่อการระบาดของโรคราน้ำค้าง ใบจุด และแอนแทรคโนส แนะนำงดพ่นน้ำทางใบและพิจารณาใช้ชีวภัณฑ์ป้องกันล่วงหน้า")

        response = [
            "### 🌾 บทวิเคราะห์และคำแนะนำจาก AgriLLM (RAG-Augmented)",
            f"**คำถาม / โจทย์การเกษตร**: {user_query}\n"
        ]

        if analysis_parts:
            response.append("####  ผลการวิเคราะห์เซนเซอร์ HandySense เชิงฟิสิกส์เกษตร:")
            response.extend(analysis_parts)
            response.append("")

        if context:
            response.append("#### 📚 ข้อเท็จจริงและองค์ความรู้สนับสนุน (AgriLLM Knowledge Base):")
            response.append(context)
            response.append("")

        response.append("#### 🚜 ข้อสรุปและแนวทางปฏิบัติภาคสนาม:")
        response.append("1. **การปรับสภาพดินและสารอาหาร**: ยึดเกณฑ์ความสมดุลของ pH และค่า EC ก่อนใส่ปุ๋ยเคมีทุกครั้ง")
        response.append("2. **การสั่งการสมาร์ทฟาร์ม**: ปรับลูปควบคุม HandySense Relay ตามช่วงความชื้นที่ระบุเพื่อประหยัดน้ำและพลังงาน")
        response.append("3. **การเฝ้าระวังทางชีวภาพ**: หมั่นตรวจแปลงและใช้จุลินทรีย์ปฏิปักษ์ เช่น ไตรโคเดอร์มา เป็นเกราะป้องกันโรคพืชแบบยั่งยืน")

        return "\n".join(response)

    def generate(self, user_query: str, telemetry: dict = None, use_rag: bool = True) -> dict:
        """Main generation pipeline."""
        context = ""
        telemetry_str = self.format_telemetry(telemetry)

        # 1. RAG Retrieval
        if use_rag:
            search_query = f"{user_query} {telemetry.get('crop_name', '') if telemetry else ''}"
            context = self.retriever.get_context_for_query(search_query, top_k=3)

        # 2. Build full prompt
        full_prompt = f"{self.system_prompt}\n\n"
        if telemetry_str:
            full_prompt += f"{telemetry_str}\n\n"
        if context:
            full_prompt += f"=== บริบทความรู้ทางการเกษตร (RAG Context) ===\n{context}\n\n"
        full_prompt += f"คำถามจากผู้ใช้: {user_query}\nคำแนะนำ:"

        # 3. Model execution
        model_used = "domain_rules_rag"
        response_text = self._call_ollama(full_prompt)

        if response_text:
            model_used = "ollama_local"
        else:
            response_text = self._expert_fallback_generate(user_query, context, telemetry)

        return {
            "query": user_query,
            "response": response_text,
            "model_used": model_used,
            "rag_applied": bool(context),
            "telemetry_applied": bool(telemetry),
            "context_snippets": context
        }
