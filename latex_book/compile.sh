#!/bin/bash
# ==============================================================================
# JC-AgriTech + AI: XeLaTeX Masterclass Textbook Compiler
# มาตรฐานมหาวิทยาลัยราชภัฏรำไพพรรณี (RBRU)
# ==============================================================================

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$DIR"

echo "================================================================="
echo "  📚 Compiling JC-AgriTech + AI Textbook with XeLaTeX (RBRU Style) "
echo "================================================================="

# ตรวจสอบ path ของ xelatex
XELATEX_CMD="xelatex"
if ! command -v xelatex &> /dev/null; then
    if [ -f "/Library/TeX/texbin/xelatex" ]; then
        XELATEX_CMD="/Library/TeX/texbin/xelatex"
    fi
fi

MAKEINDEX_CMD="makeindex"
if ! command -v makeindex &> /dev/null; then
    if [ -f "/Library/TeX/texbin/makeindex" ]; then
        MAKEINDEX_CMD="/Library/TeX/texbin/makeindex"
    fi
fi

echo "[1/3] Running XeLaTeX Pass 1 (Auxiliary Generation)..."
$XELATEX_CMD -interaction=nonstopmode main.tex > /dev/null 2>&1

echo "[2/3] Generating Academic Index with MakeIndex..."
if [ -f "main.idx" ]; then
    $MAKEINDEX_CMD main.idx > /dev/null 2>&1
fi

echo "[3/3] Running XeLaTeX Pass 2 (Final Typesetting & Cross-references)..."
$XELATEX_CMD -interaction=nonstopmode main.tex

if [ -f "main.pdf" ]; then
    echo "================================================================="
    echo "  🎉 SUCCESS! Masterclass PDF generated: $DIR/main.pdf"
    echo "================================================================="
else
    echo "================================================================="
    echo "  [!] Compilation had issues. Checking error log..."
    grep -E "^!|Fatal" main.log | head -n 20
    echo "================================================================="
fi
