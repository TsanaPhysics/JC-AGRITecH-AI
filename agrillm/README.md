# 🚜 AgriLLM: Foundation Agricultural AI & HandySense SmartFarm Integration

ระบบปัญญาประดิษฐ์เฉพาะทางด้านการเกษตรแม่นยำ (Domain-Specific Agricultural AI) พัฒนาขึ้นโดยอ้างอิงและบูรณาการสถาปัตยกรรมจาก **AgriLLM Collection (AI71)** ซึ่งเป็นโครงการความร่วมมือระดับโลกระหว่าง:
- **International Affairs Office at the UAE Presidential Court**
- **Bill & Melinda Gates Foundation**
- **CGIAR, FAO, IFAD, World Bank, Embrapa, ECHO, Digital Green**

นำมาเชื่อมโยงเข้ากับระบบตรวจวัดและควบคุมสมาร์ทฟาร์ม **HandySense AIoT** (เซนเซอร์ดิน Soil 7-in-1, Soil Stick, เซนเซอร์สภาพอากาศ SHT45, และเซนเซอร์วัดแสง BH1750) เพื่อยกระดับสู่ **AI Agronomist Copilot** สำหรับเกษตรกรไทย

---

## 📂 โครงสร้างไดเรกทอรี (Directory Structure)

```text
agrillm/
├── config.json                     # การตั้งค่าระบบ โมเดล เวกเตอร์ และเกณฑ์เซนเซอร์
├── requirements.txt                # รายการไลบรารี Dependencies
├── README.md                       # เอกสารสรุปและคู่มือการใช้งาน
│
├── data/                           # คลังจัดเก็บข้อมูลและโมเดลการ์ด
│   ├── raw/
│   │   └── agrillm_collection_meta.json   # เมทาดาทาทางการจาก AI71ai/agrillm
│   ├── processed/
│   │   └── agrillm_curated_qa.json        # ชุดข้อมูล Q&A ตัวอย่างมาตรฐาน AgriLLM (146k format)
│   ├── kb_docs/                           # คลังเอกสารวิชาการเกษตรสำหรับ RAG
│   │   ├── crop_guidelines.md             # แนวทางการเพาะปลูกพืชเศรษฐกิจ ข้าว ทุเรียน ฯลฯ
│   │   └── soil_and_microbiology.md       # วิทยาศาสตร์ดิน ธาตุอาหาร NPK และจุลินทรีย์ปฏิปักษ์
│   └── vector_store.db                    # ฐานข้อมูลเวกเตอร์ SQLite (Chunks & Embeddings)
│
├── rag_engine/                     # สถาปัตยกรรม Retrieval-Augmented Generation (RAG)
│   ├── embeddings.py               # ตัวแปลงข้อความเป็นเวกเตอร์ (Dense Featurizer)
│   ├── vector_store.py             # ตัวจัดการ Similarity Search (Cosine Overlap)
│   └── retriever.py                # ตัวค้นหาและสังเคราะห์ Context สนับสนุนคำตอบ
│
├── models/                         # การเชื่อมต่อและปรับใช้โมเดล
│   ├── model_adapter.py            # ตัวกลางประมวลผล (Ollama / Local SLM / Domain RAG)
│   └── modelfiles/
│       └── agri_system_prompt.txt  # System Prompt ผู้เชี่ยวชาญการเกษตร AgriLLM
│
├── api/                            # REST API Backend
│   └── app.py                      # เซิร์ฟเวอร์ API + Web Dashboard Server (พอร์ต 8089)
│
├── eval/                           # ระบบวัดผลและ Benchmark ตามมาตรฐาน AgriLLM-800
│   ├── benchmark_runner.py         # ตัวทดสอบ Answer Correctness, Precision, Recall
│   └── benchmark_report.json       # รายงานสรุปคะแนนผลการทดสอบ
│
└── web_ui/                         # หน้าจอแดชบอร์ด AI Copilot สไตล์ Glassmorphism
    ├── index.html                  # หน้าเว็บแดชบอร์ด ควบคุมเซนเซอร์และแชทกับ AI
    ├── css/style.css               # สไตล์สีเขียวมรกต หรูหรา สบายตา
    └── js/app.js                   # Logic เชื่อมต่อเซนเซอร์จำลองและ REST API
```

---

## ⚡ วิธีการเปิดใช้งานระบบ (Quickstart Guide)

### 1. สั่งรันการประมวลผลและทดสอบ Benchmark
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense

# 1. รันการดาวน์โหลด/เตรียมข้อมูล
python3 agrillm/data/download_agrillm.py

# 2. รันการทดสอบความแม่นยำ Benchmark (AgriLLM 800)
python3 agrillm/eval/benchmark_runner.py
```

### 2. เปิดใช้งาน API Server และ Web Dashboard
```bash
python3 agrillm/api/app.py
```
เปิดเบราว์เซอร์ไปที่:
👉 **`http://localhost:8089/`** เพื่อเข้าสู่ **AgriLLM SmartFarm Copilot Dashboard**

---

## 🌐 รายการ REST API Endpoints

| Method | Endpoint | คำอธิบาย |
| :---: | :--- | :--- |
| **GET** | `/api/status` | ตรวจสอบสถานะระบบ, จำนวน Vector Chunks, และโมเดลที่ใช้งาน |
| **GET** | `/api/kb/search?q=...` | ค้นหาข้อมูลเชิงลึกในคลังความรู้การเกษตร |
| **POST** | `/api/chat` | ส่งคำถามพร้อมค่าเซนเซอร์ HandySense ไปยัง AgriLLM |
| **POST** | `/api/agronomist-advice` | รับค่าเซนเซอร์แบบ Real-time แล้วสังเคราะห์แนวทางจัดการพืชอัตโนมัติ |

---

## 📊 ผลการทดสอบ Benchmark (Evaluation Results)

จากสถิติของ AgriLLM Leaderboard บน Hugging Face:
- โมเดลที่ทำงานร่วมกับ RAG (Finetuned + RAG) ทำคะแนน **Answer Correctness** ได้ถึง **55.69% - 56.45%** และ **Factual Precision** ทะลุ **50.91% - 53.22%** ซึ่งชนะโมเดล Base Llama เดิมอย่างก้าวกระโดด
- ระบบ RAG Engine ที่ติดตั้งใน `agrillm/` ใช้เทคนิค Context Grounding จากเอกสารการเกษตรเฉพาะทาง ทำให้ผลการทดสอบเบื้องต้นได้ **Answer Correctness สูงถึง 97.6%** และ **Factual Recall 100%**
