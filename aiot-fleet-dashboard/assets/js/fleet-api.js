/**
 * ============================================================================
 * AIoT Multi-Board Fleet Dashboard - API Client Module
 * ============================================================================
 */

const FleetAPI = {
    baseUrl: 'api/index.php',

    async getBoards() {
        const res = await fetch(`${this.baseUrl}?action=get_boards`);
        if (!res.ok) throw new Error('Failed to fetch boards');
        return await res.json();
    },

    async getBoardDetail(id) {
        const res = await fetch(`${this.baseUrl}?action=get_board_detail&id=${id}`);
        if (!res.ok) throw new Error(`Failed to fetch board ${id}`);
        return await res.json();
    },

    async addBoard(data) {
        const res = await fetch(`${this.baseUrl}?action=add_board`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        return await res.json();
    },

    async deleteBoard(id) {
        const res = await fetch(`${this.baseUrl}?action=delete_board`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        return await res.json();
    },

    async controlActuator(boardId, relayId, state) {
        const res = await fetch(`${this.baseUrl}?action=control_actuator`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ board_id: boardId, relay_id: relayId, state: state ? 1 : 0 })
        });
        return await res.json();
    },

    async broadcastActuators(relayId, state) {
        const res = await fetch(`${this.baseUrl}?action=broadcast_actuators`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ relay_id: relayId, state: state ? 1 : 0 })
        });
        return await res.json();
    },

    async saveLayout(preset, activeBoardId, viewMode, visibleWidgets, gridCols) {
        const res = await fetch(`${this.baseUrl}?action=save_layout`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                preset: preset,
                active_board_id: activeBoardId,
                view_mode: viewMode,
                visible_widgets: visibleWidgets,
                grid_columns: gridCols
            })
        });
        return await res.json();
    },

    async getLayout(preset = 'default') {
        const res = await fetch(`${this.baseUrl}?action=get_layout&preset=${preset}`);
        if (!res.ok) return null;
        return await res.json();
    },

    async updateBoardSensors(data) {
        const res = await fetch(`${this.baseUrl}?action=update_board_sensors`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        return await res.json();
    },

    async updateBoardFull(data) {
        const res = await fetch(`${this.baseUrl}?action=update_board_full`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        return await res.json();
    },

    async batchApplyPreset(preset, boardIds = []) {
        const res = await fetch(`${this.baseUrl}?action=batch_apply_preset`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ preset, board_ids: boardIds })
        });
        return await res.json();
    },

    async resetWorkshopBoards() {
        const res = await fetch(`${this.baseUrl}?action=reset_workshop_boards`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        return await res.json();
    }
};
