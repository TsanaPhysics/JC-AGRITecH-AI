---
name: perplexity-web-search
description: ค้นหาข้อมูลเชิงลึกและคำตอบเรียลไทม์ผ่าน Perplexity.ai Web Scraper MCP โดยไม่ต้องใช้ API Key พร้อมดึงแหล่งอ้างอิง (Citations & Sources) ครบถ้วน
---

# Perplexity Web MCP (No API Key Required)

เซิร์ฟเวอร์ MCP สำหรับดึงข้อมูลและค้นหาเชิงลึกจาก Perplexity.ai โดยใช้ Browser Automation (Playwright Chromium) ทำงานในพื้นหลัง โดยไม่ต้องใช้ API Key

## ข้อมูลเซิร์ฟเวอร์ในระบบ
- **Server Name:** `perplexity-web`
- **Config File:** `/Users/chewathassana/.gemini/config/mcp_config.json`
- **Execution Command:** `npx -y perplexity-web-mcp@latest`
- **Browser Engine:** Playwright Chromium (Headless / Profile Persistent)

## เครื่องมือที่มีให้ใช้งาน (Tools)
1. **`search`**:
   - ค้นหาคำตอบจาก Perplexity.ai พร้อมรายการลิงก์อ้างอิง (Citations)
   - พารามิเตอร์: `query` (string)
2. **`search_advanced`**:
   - ค้นหาแบบกำหนด Focus โหมดหรือโมเดลเชิงลึก
   - พารามิเตอร์: `query`, `focus` (web, academic, writing, etc.)
3. **`login`**:
   - เปิดหน้าต่าง Chromium เพื่อให้อาจารย์ล็อกอินบัญชี Perplexity (ฟรี หรือ Pro) เพียงครั้งเดียว
   - เซสชันและคุกกี้จะถูกบันทึกไว้ในโปรไฟล์เบราว์เซอร์อัตโนมัติ ทำให้การค้นหาครั้งต่อไปไม่ติด Cloudflare Check

## วิธีการเข้าสู่ระบบครั้งแรก (Optional - แนะนำทำครั้งเดียว)
หากต้องการใช้บัญชีส่วนตัวหรือป้องกัน Cloudflare Captcha:
สามารถเรียกเครื่องมือ `login` หรือรันผ่านเทอร์มินัล:
```bash
npx perplexity-web-mcp@latest
```
เมื่อหน้าต่างเบราว์เซอร์เปิดขึ้น ให้ล็อกอินบัญชี Perplexity แล้วปิดหน้าต่าง ระบบจะจำเซสชันไว้ถาวร
