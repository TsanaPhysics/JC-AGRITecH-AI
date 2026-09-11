#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$DIR"

echo "================================================================="
echo "  🌱 JC -AgriTech + AI: Cloud Telemetry Hub & AI Dashboard       "
echo "  Server IP: 14.207.141.164                                      "
echo "================================================================="

# ปิดโปรเซสเดิมที่อาจรันค้างอยู่บนพอร์ต 8000 และ 8501
fuser -k 8000/tcp 2>/dev/null || true
fuser -k 8501/tcp 2>/dev/null || true

if [ ! -d "venv" ]; then
    echo "[*] Initializing Python Virtual Environment..."
    python3 -m venv venv
    ./venv/bin/pip install --upgrade pip
    ./venv/bin/pip install -r requirements.txt
fi

echo "[1/2] Starting FastAPI Backend on 0.0.0.0:8000..."
nohup ./venv/bin/uvicorn main_api:app --host 0.0.0.0 --port 8000 > api.log 2>&1 &
API_PID=$!
echo "FastAPI PID: $API_PID"

echo "[2/2] Starting Streamlit AI Dashboard on 0.0.0.0:8501..."
nohup ./venv/bin/streamlit run dashboard_app.py --server.port 8501 --server.address 0.0.0.0 --server.headless true > streamlit.log 2>&1 &
DASH_PID=$!
echo "Streamlit PID: $DASH_PID"

sleep 3
ps aux | grep -E "uvicorn|streamlit" | grep -v grep

echo ""
echo "================================================================="
echo ">>> Services deployed successfully!"
echo "• Swagger API Docs   : http://14.207.141.164:8000/docs"
echo "• Telemetry Ingest   : http://14.207.141.164:8000/api/telemetry"
echo "• Web AI Dashboard   : http://14.207.141.164:8501"
echo "================================================================="
