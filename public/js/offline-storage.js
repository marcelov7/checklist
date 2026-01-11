// Sistema de Armazenamento Offline com IndexedDB
class OfflineStorage {
    constructor() {
        this.dbName = 'ChecklistOfflineDB';
        this.dbVersion = 1;
        this.db = null;
        
        this.init();
    }
    
    // ===== INICIALIZAÇÃO =====
    async init() {
        try {
            this.db = await this.openDatabase();
            console.log('[OfflineStorage] Banco de dados inicializado');
        } catch (error) {
            console.error('[OfflineStorage] Erro ao inicializar:', error);
        }
    }
    
    openDatabase() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);
            
            request.onerror = () => reject(request.error);
            request.onsuccess = () => resolve(request.result);
            
            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                
                // Store para paradas offline
                if (!db.objectStoreNames.contains('paradas')) {
                    const paradasStore = db.createObjectStore('paradas', { 
                        keyPath: 'id', 
                        autoIncrement: true 
                    });
                    paradasStore.createIndex('status', 'status', { unique: false });
                    paradasStore.createIndex('timestamp', 'timestamp', { unique: false });
                    paradasStore.createIndex('synced', 'synced', { unique: false });
                }
                
                // Store para equipamentos offline
                if (!db.objectStoreNames.contains('equipamentos')) {
                    const equipamentosStore = db.createObjectStore('equipamentos', { 
                        keyPath: 'id', 
                        autoIncrement: true 
                    });
                    equipamentosStore.createIndex('paradaId', 'paradaId', { unique: false });
                    equipamentosStore.createIndex('status', 'status', { unique: false });
                    equipamentosStore.createIndex('synced', 'synced', { unique: false });
                }
                
                // Store para checklist items offline
                if (!db.objectStoreNames.contains('checklistItems')) {
                    const checklistStore = db.createObjectStore('checklistItems', { 
                        keyPath: 'id', 
                        autoIncrement: true 
                    });
                    checklistStore.createIndex('equipamentoId', 'equipamentoId', { unique: false });
                    checklistStore.createIndex('status', 'status', { unique: false });
                    checklistStore.createIndex('synced', 'synced', { unique: false });
                }
                
                // Store para logs de sincronização
                if (!db.objectStoreNames.contains('syncLogs')) {
                    const syncStore = db.createObjectStore('syncLogs', { 
                        keyPath: 'id', 
                        autoIncrement: true 
                    });
                    syncStore.createIndex('timestamp', 'timestamp', { unique: false });
                    syncStore.createIndex('type', 'type', { unique: false });
                }
                
                console.log('[OfflineStorage] Estrutura do banco criada');
            };
        });
    }
    
    // ===== OPERAÇÕES DE PARADAS =====
    
    async saveParada(paradaData) {
        try {
            const transaction = this.db.transaction(['paradas'], 'readwrite');
            const store = transaction.objectStore('paradas');
            
            const offlineParada = {
                ...paradaData,
                timestamp: new Date().toISOString(),
                synced: false,
                offlineId: 'offline_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)
            };
            
            const result = await this.promisifyRequest(store.add(offlineParada));
            console.log('[OfflineStorage] Parada salva offline:', result);
            
            return result;
        } catch (error) {
            console.error('[OfflineStorage] Erro ao salvar parada:', error);
            throw error;
        }
    }
    
    async getParadasOffline() {
        try {
            const transaction = this.db.transaction(['paradas'], 'readonly');
            const store = transaction.objectStore('paradas');
            const index = store.index('synced');
            
            const result = await this.promisifyRequest(index.getAll(false));
            return result;
        } catch (error) {
            console.error('[OfflineStorage] Erro ao buscar paradas offline:', error);
            return [];
        }
    }
    
    async markParadaAsSynced(offlineId, serverId) {
        try {
            const transaction = this.db.transaction(['paradas'], 'readwrite');
            const store = transaction.objectStore('paradas');
            
            // Buscar pela offlineId
            const allParadas = await this.promisifyRequest(store.getAll());
            const parada = allParadas.find(p => p.offlineId === offlineId);
            
            if (parada) {
                parada.synced = true;
                parada.serverId = serverId;
                parada.syncedAt = new Date().toISOString();
                
                await this.promisifyRequest(store.put(parada));
                console.log('[OfflineStorage] Parada marcada como sincronizada:', offlineId);
            }
        } catch (error) {
            console.error('[OfflineStorage] Erro ao marcar parada como sincronizada:', error);
        }
    }
    
    // ===== OPERAÇÕES DE EQUIPAMENTOS =====
    
    async saveEquipamento(equipamentoData) {
        try {
            const transaction = this.db.transaction(['equipamentos'], 'readwrite');
            const store = transaction.objectStore('equipamentos');
            
            const offlineEquipamento = {
                ...equipamentoData,
                timestamp: new Date().toISOString(),
                synced: false,
                offlineId: 'offline_eq_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)
            };
            
            const result = await this.promisifyRequest(store.add(offlineEquipamento));
            console.log('[OfflineStorage] Equipamento salvo offline:', result);
            
            return result;
        } catch (error) {
            console.error('[OfflineStorage] Erro ao salvar equipamento:', error);
            throw error;
        }
    }
    
    async getEquipamentosOffline() {
        try {
            const transaction = this.db.transaction(['equipamentos'], 'readonly');
            const store = transaction.objectStore('equipamentos');
            const index = store.index('synced');
            
            const result = await this.promisifyRequest(index.getAll(false));
            return result;
        } catch (error) {
            console.error('[OfflineStorage] Erro ao buscar equipamentos offline:', error);
            return [];
        }
    }
    
    // ===== OPERAÇÕES DE CHECKLIST ITEMS =====
    
    async saveChecklistItem(itemData) {
        try {
            const transaction = this.db.transaction(['checklistItems'], 'readwrite');
            const store = transaction.objectStore('checklistItems');
            
            const offlineItem = {
                ...itemData,
                timestamp: new Date().toISOString(),
                synced: false,
                offlineId: 'offline_item_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)
            };
            
            const result = await this.promisifyRequest(store.add(offlineItem));
            console.log('[OfflineStorage] Item de checklist salvo offline:', result);
            
            return result;
        } catch (error) {
            console.error('[OfflineStorage] Erro ao salvar item de checklist:', error);
            throw error;
        }
    }
    
    async getChecklistItemsOffline() {
        try {
            const transaction = this.db.transaction(['checklistItems'], 'readonly');
            const store = transaction.objectStore('checklistItems');
            const index = store.index('synced');
            
            const result = await this.promisifyRequest(index.getAll(false));
            return result;
        } catch (error) {
            console.error('[OfflineStorage] Erro ao buscar itens de checklist offline:', error);
            return [];
        }
    }
    
    // ===== SINCRONIZAÇÃO =====
    
    async getAllUnsyncedData() {
        try {
            const [paradas, equipamentos, checklistItems] = await Promise.all([
                this.getParadasOffline(),
                this.getEquipamentosOffline(),
                this.getChecklistItemsOffline()
            ]);
            
            return {
                paradas,
                equipamentos,
                checklistItems,
                total: paradas.length + equipamentos.length + checklistItems.length
            };
        } catch (error) {
            console.error('[OfflineStorage] Erro ao buscar dados não sincronizados:', error);
            return { paradas: [], equipamentos: [], checklistItems: [], total: 0 };
        }
    }
    
    async logSyncAttempt(type, data, success, error = null) {
        try {
            const transaction = this.db.transaction(['syncLogs'], 'readwrite');
            const store = transaction.objectStore('syncLogs');
            
            const logEntry = {
                type,
                data: JSON.stringify(data),
                success,
                error: error ? error.toString() : null,
                timestamp: new Date().toISOString()
            };
            
            await this.promisifyRequest(store.add(logEntry));
        } catch (error) {
            console.error('[OfflineStorage] Erro ao salvar log de sincronização:', error);
        }
    }
    
    async getSyncLogs(limit = 50) {
        try {
            const transaction = this.db.transaction(['syncLogs'], 'readonly');
            const store = transaction.objectStore('syncLogs');
            const index = store.index('timestamp');
            
            // Buscar os logs mais recentes
            const request = index.openCursor(null, 'prev');
            const logs = [];
            
            return new Promise((resolve, reject) => {
                request.onsuccess = (event) => {
                    const cursor = event.target.result;
                    if (cursor && logs.length < limit) {
                        logs.push(cursor.value);
                        cursor.continue();
                    } else {
                        resolve(logs);
                    }
                };
                request.onerror = () => reject(request.error);
            });
        } catch (error) {
            console.error('[OfflineStorage] Erro ao buscar logs de sincronização:', error);
            return [];
        }
    }
    
    // ===== LIMPEZA =====
    
    async clearSyncedData() {
        try {
            const stores = ['paradas', 'equipamentos', 'checklistItems'];
            
            for (const storeName of stores) {
                const transaction = this.db.transaction([storeName], 'readwrite');
                const store = transaction.objectStore('storeName');
                const index = store.index('synced');
                
                const syncedItems = await this.promisifyRequest(index.getAll(true));
                
                for (const item of syncedItems) {
                    await this.promisifyRequest(store.delete(item.id));
                }
            }
            
            console.log('[OfflineStorage] Dados sincronizados removidos');
        } catch (error) {
            console.error('[OfflineStorage] Erro ao limpar dados sincronizados:', error);
        }
    }
    
    async clearAllData() {
        try {
            const stores = ['paradas', 'equipamentos', 'checklistItems', 'syncLogs'];
            
            for (const storeName of stores) {
                const transaction = this.db.transaction([storeName], 'readwrite');
                const store = transaction.objectStore(storeName);
                await this.promisifyRequest(store.clear());
            }
            
            console.log('[OfflineStorage] Todos os dados offline removidos');
        } catch (error) {
            console.error('[OfflineStorage] Erro ao limpar todos os dados:', error);
        }
    }
    
    // ===== UTILITÁRIOS =====
    
    promisifyRequest(request) {
        return new Promise((resolve, reject) => {
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }
    
    async getStorageInfo() {
        try {
            const unsyncedData = await this.getAllUnsyncedData();
            const logs = await this.getSyncLogs(10);
            
            return {
                unsyncedCount: unsyncedData.total,
                unsyncedData,
                recentLogs: logs,
                dbName: this.dbName,
                dbVersion: this.dbVersion
            };
        } catch (error) {
            console.error('[OfflineStorage] Erro ao obter informações de armazenamento:', error);
            return null;
        }
    }
}

// ===== INICIALIZAÇÃO AUTOMÁTICA =====
document.addEventListener('DOMContentLoaded', () => {
    window.offlineStorage = new OfflineStorage();
});

// Exportar para uso global
window.OfflineStorage = OfflineStorage;