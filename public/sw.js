// Service Worker para PWA com Sistema de Atualização
const CACHE_VERSION = '1.0.1';
const CACHE_TIMESTAMP = new Date().toISOString();
const CACHE_NAME = `checklist-v${CACHE_VERSION}-${Date.now()}`;

// Sistema de versionamento automático
const VERSION_INFO = {
    version: CACHE_VERSION,
    timestamp: CACHE_TIMESTAMP,
    buildHash: 'auto-' + Math.random().toString(36).substr(2, 9)
};

// Arquivos essenciais para cache
const STATIC_CACHE_URLS = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/manifest.json',
    '/favicon.ico',
    // Páginas principais
    '/paradas',
    '/areas',
    '/login'
];

// URLs dinâmicas que devem ser cacheadas
const DYNAMIC_CACHE_URLS = [
    '/paradas/',
    '/areas/',
    '/api/'
];

// ===== INSTALAÇÃO DO SERVICE WORKER =====
self.addEventListener('install', event => {
    console.log('[SW] Instalando Service Worker versão:', CACHE_VERSION);
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('[SW] Cache aberto:', CACHE_NAME);
                return cache.addAll(STATIC_CACHE_URLS);
            })
            .then(() => {
                console.log('[SW] Arquivos estáticos cacheados');
                // Força a ativação imediata
                return self.skipWaiting();
            })
            .catch(error => {
                console.error('[SW] Erro ao cachear arquivos:', error);
            })
    );
});

// ===== ATIVAÇÃO DO SERVICE WORKER =====
self.addEventListener('activate', event => {
    console.log('[SW] Ativando Service Worker versão:', CACHE_VERSION);
    
    event.waitUntil(
        Promise.all([
            // Limpar caches antigos
            caches.keys().then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => {
                        if (cacheName !== CACHE_NAME) {
                            console.log('[SW] Removendo cache antigo:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            }),
            // Assumir controle de todas as abas
            self.clients.claim()
        ]).then(() => {
            console.log('[SW] Service Worker ativado e controlando todas as abas');
            // Notificar clientes sobre nova versão
            notifyClientsAboutUpdate();
        })
    );
});

// ===== INTERCEPTAÇÃO DE REQUISIÇÕES =====
self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);
    
    // Ignorar requisições não-HTTP
    if (!request.url.startsWith('http')) {
        return;
    }
    
    // Estratégia: Cache First para recursos estáticos
    if (STATIC_CACHE_URLS.some(staticUrl => url.pathname === staticUrl)) {
        event.respondWith(cacheFirst(request));
        return;
    }
    
    // Estratégia: Network First para dados dinâmicos
    if (DYNAMIC_CACHE_URLS.some(dynamicUrl => url.pathname.startsWith(dynamicUrl))) {
        event.respondWith(networkFirst(request));
        return;
    }
    
    // Estratégia padrão: Network First
    event.respondWith(networkFirst(request));
});

// ===== ESTRATÉGIAS DE CACHE =====

// Cache First: Busca no cache primeiro, depois na rede
async function cacheFirst(request) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.error('[SW] Erro em cacheFirst:', error);
        return new Response('Offline - Recurso não disponível', { status: 503 });
    }
}

// Network First: Busca na rede primeiro, depois no cache
async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.log('[SW] Rede indisponível, buscando no cache:', request.url);
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        return new Response('Offline - Dados não disponíveis', { 
            status: 503,
            headers: { 'Content-Type': 'text/plain; charset=utf-8' }
        });
    }
}

// ===== SISTEMA DE ATUALIZAÇÃO =====

// Notificar clientes sobre atualização disponível
function notifyClientsAboutUpdate() {
    self.clients.matchAll().then(clients => {
        clients.forEach(client => {
            client.postMessage({
                type: 'UPDATE_AVAILABLE',
                version: VERSION_INFO.version,
                timestamp: VERSION_INFO.timestamp,
                buildHash: VERSION_INFO.buildHash,
                message: `Nova versão ${VERSION_INFO.version} disponível! Clique para atualizar.`
            });
        });
    });
}

// Escutar mensagens dos clientes
self.addEventListener('message', event => {
    const { type, data } = event.data;
    
    switch (type) {
        case 'SKIP_WAITING':
            // Força a ativação da nova versão
            self.skipWaiting();
            break;
            
        case 'GET_VERSION':
            // Retorna informações completas da versão
            event.ports[0].postMessage({
                type: 'VERSION_INFO',
                ...VERSION_INFO,
                cacheName: CACHE_NAME
            });
            break;
            
        case 'CLEAR_CACHE':
            // Limpa todo o cache
            caches.keys().then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => caches.delete(cacheName))
                );
            }).then(() => {
                event.ports[0].postMessage({
                    type: 'CACHE_CLEARED',
                    success: true
                });
            });
            break;
    }
});

// ===== SINCRONIZAÇÃO EM BACKGROUND =====
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync') {
        console.log('[SW] Executando sincronização em background');
        event.waitUntil(syncOfflineData());
    }
});

// Sincronizar dados offline quando a conexão retornar
async function syncOfflineData() {
    try {
        // Buscar dados offline do IndexedDB
        const offlineData = await getOfflineData();
        
        for (const item of offlineData) {
            try {
                const response = await fetch('/api/sync', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(item)
                });
                
                if (response.ok) {
                    const result = await response.json();
                    // Marcar como sincronizado usando offlineId e serverId retornado
                    await markAsSynced(item.offlineId, result.id || result.serverId);
                    console.log('[SW] Item sincronizado:', item.offlineId);
                }
            } catch (error) {
                console.error('[SW] Erro ao sincronizar item:', error);
            }
        }
    } catch (error) {
        console.error('[SW] Erro na sincronização:', error);
    }
}

// Buscar dados offline do IndexedDB
async function getOfflineData() {
    try {
        // Abrir conexão com IndexedDB
        const db = await openOfflineDB();
        const unsyncedData = await getAllUnsyncedFromDB(db);
        return unsyncedData;
    } catch (error) {
        console.error('[SW] Erro ao buscar dados offline:', error);
        return [];
    }
}

// Marcar item como sincronizado
async function markAsSynced(offlineId, serverId) {
    try {
        const db = await openOfflineDB();
        await markItemAsSyncedInDB(db, offlineId, serverId);
        console.log('[SW] Item marcado como sincronizado:', offlineId);
    } catch (error) {
        console.error('[SW] Erro ao marcar como sincronizado:', error);
    }
}

// Abrir conexão com IndexedDB
function openOfflineDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('ChecklistOfflineDB', 1);
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

// Buscar todos os dados não sincronizados
async function getAllUnsyncedFromDB(db) {
    const stores = ['paradas', 'equipamentos', 'checklistItems'];
    const allData = [];
    
    for (const storeName of stores) {
        try {
            const transaction = db.transaction([storeName], 'readonly');
            const store = transaction.objectStore(storeName);
            const index = store.index('synced');
            
            const data = await new Promise((resolve, reject) => {
                const request = index.getAll(false);
                request.onsuccess = () => resolve(request.result);
                request.onerror = () => reject(request.error);
            });
            
            allData.push(...data.map(item => ({ ...item, type: storeName })));
        } catch (error) {
            console.error(`[SW] Erro ao buscar ${storeName}:`, error);
        }
    }
    
    return allData;
}

// Marcar item como sincronizado no IndexedDB
async function markItemAsSyncedInDB(db, offlineId, serverId) {
    const stores = ['paradas', 'equipamentos', 'checklistItems'];
    
    for (const storeName of stores) {
        try {
            const transaction = db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);
            
            // Buscar todos os itens para encontrar o correto
            const allItems = await new Promise((resolve, reject) => {
                const request = store.getAll();
                request.onsuccess = () => resolve(request.result);
                request.onerror = () => reject(request.error);
            });
            
            const item = allItems.find(i => i.offlineId === offlineId);
            if (item) {
                item.synced = true;
                item.serverId = serverId;
                item.syncedAt = new Date().toISOString();
                
                await new Promise((resolve, reject) => {
                    const request = store.put(item);
                    request.onsuccess = () => resolve(request.result);
                    request.onerror = () => reject(request.error);
                });
                
                break; // Item encontrado e atualizado
            }
        } catch (error) {
            console.error(`[SW] Erro ao atualizar ${storeName}:`, error);
        }
    }
}

console.log('[SW] Service Worker carregado:', VERSION_INFO);