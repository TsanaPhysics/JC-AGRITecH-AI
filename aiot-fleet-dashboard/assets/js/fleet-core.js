/**
 * ============================================================================
 * AIoT Multi-Board Fleet Dashboard - State Management & Core Controller
 * ============================================================================
 */

const FleetState = {
    boards: [],
    activeBoardId: 1,
    viewMode: 'focus', // 'focus' | 'fleet_grid' | 'comparative'
    gridColumns: 4,
    workshopFilter: 'all', // 'all' | 'master' | 'zoneA' | 'zoneB' | 'zoneC'
    searchQuery: '',
    visibleWidgets: [
        'weather_microclimate',
        'vpd_transpiration',
        'soil_7in1_root',
        'surface_soil_stick',
        'solar_radiation',
        'ai_npk_calibration',
        'relays_control',
        'trend_charts'
    ],
    pollingTimer: null,
    pollIntervalMs: 2500,

    getActiveBoard() {
        return this.boards.find(b => Number(b.id) === Number(this.activeBoardId)) || this.boards[0] || null;
    },

    getMasterBoard() {
        return this.boards.find(b => Number(b.is_master) === 1 || Number(b.id) === 1) || this.boards[0] || null;
    }
};

// Initialize Fleet System
document.addEventListener('DOMContentLoaded', async () => {
    try {
        await initFleetDashboard();
    } catch (e) {
        console.error('Fleet Init Error:', e);
    }
});

async function initFleetDashboard() {
    // 1. Load Layout Preset from LocalStorage or API
    try {
        const layoutData = await FleetAPI.getLayout('default');
        if (layoutData && layoutData.status === 'success' && layoutData.layout) {
            const l = layoutData.layout;
            FleetState.activeBoardId = Number(l.active_board_id || 1);
            FleetState.viewMode = l.view_mode || 'focus';
            FleetState.gridColumns = Number(l.grid_columns || 4);
            if (Array.isArray(l.visible_widgets) && l.visible_widgets.length > 0) {
                FleetState.visibleWidgets = l.visible_widgets;
            }
        }
    } catch(e) {
        console.warn('Using local fallback layout', e);
    }

    // 2. Fetch Initial Boards & Telemetry
    await refreshFleetNow();

    // 3. Start Auto Polling
    startFleetPolling();
}

function startFleetPolling() {
    if (FleetState.pollingTimer) clearInterval(FleetState.pollingTimer);
    FleetState.pollingTimer = setInterval(async () => {
        try {
            await fetchFleetData(false);
        } catch(e) {
            console.error('Polling error', e);
        }
    }, FleetState.pollIntervalMs);
}

async function refreshFleetNow() {
    const icon = document.getElementById('btnRefreshIcon');
    if (icon) icon.classList.add('animate-spin');
    try {
        await fetchFleetData(true);
    } finally {
        setTimeout(() => {
            if (icon) icon.classList.remove('animate-spin');
        }, 400);
    }
}

async function fetchFleetData(isManual = false) {
    const data = await FleetAPI.getBoards();
    if (data.status === 'success' && Array.isArray(data.boards)) {
        FleetState.boards = data.boards;

        // Ensure active board still exists
        if (!FleetState.boards.some(b => Number(b.id) === Number(FleetState.activeBoardId))) {
            FleetState.activeBoardId = FleetState.boards[0]?.id || 1;
        }

        // Render UI
        FleetUI.renderMasterCard();
        FleetUI.renderBoardSelector();
        FleetUI.renderCurrentView();

        const statusText = document.getElementById('navFleetStatusText');
        if (statusText) {
            const onlineCount = FleetState.boards.filter(b => b.status === 'online').length;
            statusText.innerText = `เชื่อมต่อ ${onlineCount}/${FleetState.boards.length} บอร์ดออนไลน์ (ห้องอบรม 15 กลุ่ม + 1 สาธิต)`;
        }
    }
}

// Workshop Group Filtering
function filterWorkshopGroup(zone) {
    FleetState.workshopFilter = zone;
    const filterBtns = {
        'all': 'btnFilterAll',
        'master': 'btnFilterMaster',
        'zoneA': 'btnFilterZoneA',
        'zoneB': 'btnFilterZoneB',
        'zoneC': 'btnFilterZoneC'
    };
    Object.keys(filterBtns).forEach(k => {
        const btn = document.getElementById(filterBtns[k]);
        if (btn) {
            if (k === zone) {
                btn.className = 'px-2.5 py-1 rounded-lg font-bold transition bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-[11px] shadow-xs';
            } else {
                btn.className = 'px-2.5 py-1 rounded-lg font-bold transition text-slate-400 hover:text-white text-[11px]';
            }
        }
    });
    FleetUI.renderBoardSelector();
}

function searchWorkshopGroup(query) {
    FleetState.searchQuery = (query || '').trim().toLowerCase();
    FleetUI.renderBoardSelector();
}
