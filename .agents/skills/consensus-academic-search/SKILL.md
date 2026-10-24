---
name: consensus-academic-search
description: ค้นหาและสังเคราะห์งานวิจัย เอกสารวิชาการ และข้อค้นพบเชิงประจักษ์ (Scientific Consensus) จากฐานข้อมูล Consensus.app (400M+ Peer-reviewed Papers) พร้อมดึงค่า SJR Quartile (Q1-Q4), AI Key Takeaway, Citation Count และ DOI Link โดยอัตโนมัติ
---

# Consensus Academic Search & Research Synthesis

ทักษะสำหรับการค้นหาและสังเคราะห์งานวิจัยระดับนานาชาติจาก Consensus.app ผ่าน API Key: `ak_1A9YJP15Q4SKT72M692KYPJM3Y6RMGBF`

## ข้อมูลการเชื่อมต่อ (MCP Server & API)
- **MCP Server Name:** `consensus`
- **MCP Server Path:** `/Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/.agents/mcp_servers/consensus_server.py`
- **Global Config:** `/Users/chewathassana/.gemini/config/mcp_config.json`
- **API Endpoint:** `https://api.consensus.app/v1/search`
- **Header:** `x-api-key: ak_1A9YJP15Q4SKT72M692KYPJM3Y6RMGBF`

## วิธีการเรียกใช้งาน

### 1. ผ่าน Model Context Protocol (MCP)
- เครื่องมือ: `consensus_search`
- พารามิเตอร์:
  - `query` (string, บังคับ): คำค้นหา หัวข้อวิจัย หรือคำถามวิทยาศาสตร์ เช่น `"durian fruit postharvest Trichoderma"`, `"soil moisture IoT sensor precision agriculture"`
  - `year_min` (integer, ไม่บังคับ): ปีก่อตั้งต่ำสุด เช่น `2018`
  - `year_max` (integer, ไม่บังคับ): ปีสูงสุด เช่น `2026`
  - `page_size` (integer, ไม่บังคับ): จำนวนบทความที่ต้องการ (ค่าเริ่มต้น 10, สูงสุด 20)

### 2. เรียกใช้งานผ่านคำสั่ง CLI ด่วน
หากต้องการทดสอบหรือค้นหาผ่านเทอร์มินัลโดยตรง:
```bash
python3 /Applications/XAMPP/xamppfiles/htdocs/G3_Agri_Microbiology_SmartFarm/3.1_เกษตรทั่วไปและสมาร์ทฟาร์ม/handysense/.agents/mcp_servers/consensus_server.py --search "soil pH sensor compensation AI"
```

## ผลลัพธ์ที่ได้รับ
1. ชื่อบทความวิจัย (Title) พร้อมลิงก์เข้าหน้าเปเปอร์บน Consensus.app
2. คณะผู้วิจัย (Authors) และปีที่ตีพิมพ์ (Publish Year)
3. วารสารวิชาการ (Journal Name) และระดับควอร์ไทล์ (**SJR Q1, Q2, Q3, Q4**)
4. จำนวนการอ้างอิง (**Citations**) และประเภทการศึกษา (**Study Type**)
5. **Consensus AI Key Finding / Takeaway:** สรุปใจความสำคัญและข้อค้นพบเชิงประจักษ์โดย AI ของ Consensus
6. รหัสประจำเอกสารวิชาการ (**DOI Link**)
