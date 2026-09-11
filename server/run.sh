#!/bin/bash
# ==============================================================================
# JC-AgriTech + AI: Server & Dashboard Launcher
# รันทั้ง FastAPI Backend (:8000) และ Streamlit Dashboard (:8501) ในคำสั่งเดียว
# ==============================================================================

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$DIR"

echo "================================================================="
echo "  🌱 JC -AgriTech + AI: Starting Local Telemetry Hub & AI Lab    "
echo "================================================================="

# ตรวจสอบ virtualenv หรือติดตั้งแพ็กเกจหากจำเป็น
if [ ! -d "venv" ]; then
    echo "[*] Creating Python Virtual Environment (venv)..."
    python3 -m venv venv
    ./venv/bin/pip install --upgrade pip
    ./venv/bin/pip install -r requirements.txt
fi

echo "[1/3] Starting FastAPI Backend on http://0.0.0.0:8000 ..."
./venv/bin/uvicorn main_api:app --host 0.0.0.0 --port 8000 &
API_PID=$!

echo "[2/3] Starting Real-time Serial Telemetry Bridge..."
/Users/chewathassana/.local/pipx/venvs/platformio/bin/python serial_bridge.py &
BRIDGE_PID=$!

echo "[3/3] Starting Streamlit AI Dashboard on http://localhost:8501 ..."
./venv/bin/streamlit run dashboard_app.py --server.port 8501 --server.headless true &
DASH_PID=$!

echo ""
echo ">>> All services are running! Press Ctrl+C to stop all services."
echo "• Swagger API Docs  : http://localhost:8000/docs"
echo "• Streamlit Dashboard: http://localhost:8501"
echo "================================================================="

trap "kill $API_PID $BRIDGE_PID $DASH_PID; exit" INT TERM
wait
