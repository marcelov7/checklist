import React, { useState, useMemo } from 'react';
import { Button, Card, CardHeader, CardTitle, CardContent, Input } from './ui';
import {
  useHeavyComputation,
  useDebounce,
  useThrottle,
  useVirtualList,
  usePerformanceMonitor,
  useCachedData,
  LazyLoad,
  OptimizedImage,
  createMemoComponent,
  measurePerformance,
  memoryCache
} from '../utils/hooks-simple.tsx';

/**
 * Componente memoizado para demonstrar otimização
 */
const ExpensiveComponent = createMemoComponent(({ data, multiplier }) => {
  console.log('🎨 ExpensiveComponent renderizado');
  
  const result = useMemo(() => {
    // Simulação de computação pesada
    let sum = 0;
    for (let i = 0; i < data.length; i++) {
      sum += data[i] * multiplier;
    }
    return sum;
  }, [data, multiplier]);

  return (
    <div className="p-4 bg-blue-50 rounded-lg">
      <h4 className="font-semibold text-blue-900">Componente Memoizado</h4>
      <p className="text-blue-700">Resultado da computação: {result.toLocaleString()}</p>
      <p className="text-xs text-blue-600 mt-1">
        Este componente só re-renderiza quando data ou multiplier mudam
      </p>
    </div>
  );
});

/**
 * Componente para demonstrar virtual scrolling
 */
const VirtualScrollDemo = () => {
  // Gerar lista grande de itens
  const items = useMemo(() => 
    Array.from({ length: 10000 }, (_, i) => ({
      id: i,
      name: `Item ${i + 1}`,
      description: `Descrição do item ${i + 1}`,
      value: Math.floor(Math.random() * 1000)
    }))
  , []);

  const itemHeight = 60;
  const containerHeight = 300;

  const {
    visibleItems,
    totalHeight,
    offsetY,
    handleScroll,
    startIndex,
    endIndex
  } = useVirtualList(items, itemHeight, containerHeight);

  return (
    <div className="space-y-4">
      <div className="text-sm text-gray-600">
        Mostrando itens {startIndex + 1}-{endIndex} de {items.length.toLocaleString()}
      </div>
      
      <div
        className="border rounded-lg overflow-auto"
        style={{ height: containerHeight }}
        onScroll={handleScroll}
      >
        <div style={{ height: totalHeight, position: 'relative' }}>
          <div style={{ transform: `translateY(${offsetY}px)` }}>
            {visibleItems.map((item, index) => (
              <div
                key={item.id}
                className="flex items-center justify-between p-3 border-b hover:bg-gray-50"
                style={{ height: itemHeight }}
              >
                <div>
                  <div className="font-medium">{item.name}</div>
                  <div className="text-sm text-gray-500">{item.description}</div>
                </div>
                <div className="text-lg font-bold text-blue-600">
                  {item.value}
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

/**
 * Componente principal de demonstração de performance
 */
const PerformanceDemo = () => {
  usePerformanceMonitor('PerformanceDemo');

  const [searchTerm, setSearchTerm] = useState('');
  const [multiplier, setMultiplier] = useState(1);
  const [showExpensive, setShowExpensive] = useState(true);
  const [cacheKey, setCacheKey] = useState('demo-data-1');

  // Debounce da busca
  const debouncedSearchTerm = useDebounce(searchTerm, 300);

  // Throttle para scroll
  const throttledScroll = useThrottle((e) => {
    console.log('📜 Scroll throttled:', e.target.scrollTop);
  }, 100);

  // Dados para computação pesada
  const heavyData = useMemo(() => 
    Array.from({ length: 100000 }, (_, i) => i + 1)
  , []);

  // Computação pesada memoizada
  const heavyResult = useHeavyComputation(() => {
    return heavyData.reduce((sum, num) => sum + num, 0);
  }, [heavyData]);

  // Cache de dados simulado
  const { data: cachedData, loading: cacheLoading, refetch } = useCachedData(
    cacheKey,
    async () => {
      // Simular API call
      await new Promise(resolve => setTimeout(resolve, 1000));
      return {
        timestamp: new Date().toISOString(),
        randomValue: Math.floor(Math.random() * 1000),
        message: `Dados carregados para ${cacheKey}`
      };
    },
    30000 // 30 segundos de cache
  );

  // Função para testar performance
  const runPerformanceTest = async () => {
    await measurePerformance(async () => {
      // Simular operação pesada
      let result = 0;
      for (let i = 0; i < 1000000; i++) {
        result += Math.sqrt(i);
      }
      return result;
    }, 'Operação matemática pesada');
  };

  return (
    <div className="min-h-screen bg-gray-50 p-6">
      <header className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900 mb-2">
          Demonstração de Performance
        </h1>
        <p className="text-gray-600">
          Teste as otimizações de performance implementadas
        </p>
      </header>

      <div className="space-y-8">
        
        {/* Seção: Debounce */}
        <Card>
          <CardHeader>
            <CardTitle>Debounce de Busca</CardTitle>
            <p className="text-sm text-gray-600">
              A busca é executada apenas 300ms após parar de digitar
            </p>
          </CardHeader>
          <CardContent className="space-y-4">
            <Input
              label="Termo de Busca"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              placeholder="Digite para testar o debounce..."
            />
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
              <div className="p-3 bg-gray-50 rounded">
                <strong>Valor atual:</strong> "{searchTerm}"
              </div>
              <div className="p-3 bg-blue-50 rounded">
                <strong>Valor com debounce:</strong> "{debouncedSearchTerm}"
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Seção: Memoização */}
        <Card>
          <CardHeader>
            <CardTitle>Memoização de Componentes</CardTitle>
            <p className="text-sm text-gray-600">
              Componente só re-renderiza quando props relevantes mudam
            </p>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="flex gap-4 items-end">
              <Input
                label="Multiplicador"
                type="number"
                value={multiplier}
                onChange={(e) => setMultiplier(Number(e.target.value))}
                className="w-32"
              />
              
              <Button
                variant="outline"
                onClick={() => setShowExpensive(!showExpensive)}
              >
                {showExpensive ? 'Ocultar' : 'Mostrar'} Componente
              </Button>
            </div>

            {showExpensive && (
              <ExpensiveComponent 
                data={heavyData.slice(0, 1000)} 
                multiplier={multiplier} 
              />
            )}

            <div className="text-sm text-gray-600 bg-yellow-50 p-3 rounded">
              <strong>Dica:</strong> Abra o console para ver quando o componente re-renderiza.
              Mude apenas o multiplicador para ver a memoização em ação.
            </div>
          </CardContent>
        </Card>

        {/* Seção: Computação Pesada */}
        <Card>
          <CardHeader>
            <CardTitle>Computação Pesada Memoizada</CardTitle>
            <p className="text-sm text-gray-600">
              Resultado é calculado apenas uma vez e reutilizado
            </p>
          </CardHeader>
          <CardContent>
            <div className="p-4 bg-green-50 rounded-lg">
              <h4 className="font-semibold text-green-900">Soma de 100.000 números</h4>
              <p className="text-green-700 text-lg">
                Resultado: {heavyResult.toLocaleString()}
              </p>
              <p className="text-xs text-green-600 mt-1">
                Calculado apenas uma vez, mesmo com re-renders
              </p>
            </div>
          </CardContent>
        </Card>

        {/* Seção: Virtual Scrolling */}
        <Card>
          <CardHeader>
            <CardTitle>Virtual Scrolling</CardTitle>
            <p className="text-sm text-gray-600">
              Lista de 10.000 itens renderizada eficientemente
            </p>
          </CardHeader>
          <CardContent>
            <VirtualScrollDemo />
          </CardContent>
        </Card>

        {/* Seção: Cache */}
        <Card>
          <CardHeader>
            <CardTitle>Cache de Dados</CardTitle>
            <p className="text-sm text-gray-600">
              Dados são armazenados em cache por 30 segundos
            </p>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="flex gap-4 items-end">
              <Input
                label="Chave do Cache"
                value={cacheKey}
                onChange={(e) => setCacheKey(e.target.value)}
                className="flex-1"
              />
              
              <Button
                variant="outline"
                onClick={refetch}
                loading={cacheLoading}
              >
                Recarregar
              </Button>
            </div>

            {cachedData && (
              <div className="p-4 bg-purple-50 rounded-lg">
                <h4 className="font-semibold text-purple-900">Dados em Cache</h4>
                <div className="text-purple-700 space-y-1">
                  <p><strong>Timestamp:</strong> {cachedData.timestamp}</p>
                  <p><strong>Valor:</strong> {cachedData.randomValue}</p>
                  <p><strong>Mensagem:</strong> {cachedData.message}</p>
                </div>
              </div>
            )}

            <div className="text-sm text-gray-600">
              <strong>Cache atual:</strong> {memoryCache.size()} itens armazenados
            </div>
          </CardContent>
        </Card>

        {/* Seção: Lazy Loading */}
        <Card>
          <CardHeader>
            <CardTitle>Lazy Loading</CardTitle>
            <p className="text-sm text-gray-600">
              Componentes carregados apenas quando visíveis
            </p>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-8">
              {Array.from({ length: 5 }, (_, i) => (
                <LazyLoad
                  key={i}
                  fallback={
                    <div className="h-32 bg-gray-200 animate-pulse rounded-lg flex items-center justify-center">
                      <span className="text-gray-500">Carregando seção {i + 1}...</span>
                    </div>
                  }
                >
                  <div className="h-32 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white">
                    <div className="text-center">
                      <h3 className="text-xl font-bold">Seção {i + 1}</h3>
                      <p>Carregada via Intersection Observer</p>
                    </div>
                  </div>
                </LazyLoad>
              ))}
            </div>
          </CardContent>
        </Card>

        {/* Seção: Imagens Otimizadas */}
        <Card>
          <CardHeader>
            <CardTitle>Imagens Otimizadas</CardTitle>
            <p className="text-sm text-gray-600">
              Lazy loading de imagens com placeholder
            </p>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              {[
                'https://picsum.photos/300/200?random=1',
                'https://picsum.photos/300/200?random=2',
                'https://picsum.photos/300/200?random=3'
              ].map((src, index) => (
                <OptimizedImage
                  key={index}
                  src={src}
                  alt={`Imagem de exemplo ${index + 1}`}
                  className="rounded-lg"
                  width={300}
                  height={200}
                />
              ))}
            </div>
          </CardContent>
        </Card>

        {/* Seção: Medição de Performance */}
        <Card>
          <CardHeader>
            <CardTitle>Medição de Performance</CardTitle>
            <p className="text-sm text-gray-600">
              Teste e monitore a performance de operações
            </p>
          </CardHeader>
          <CardContent className="space-y-4">
            <Button
              variant="primary"
              onClick={runPerformanceTest}
            >
              Executar Teste de Performance
            </Button>
            
            <div className="text-sm text-gray-600 bg-blue-50 p-3 rounded">
              <strong>Dica:</strong> Abra o console para ver os logs de performance.
              Todas as operações são medidas e logadas automaticamente.
            </div>
          </CardContent>
        </Card>

        {/* Seção: Throttle */}
        <Card>
          <CardHeader>
            <CardTitle>Throttle de Eventos</CardTitle>
            <p className="text-sm text-gray-600">
              Scroll throttled para melhor performance
            </p>
          </CardHeader>
          <CardContent>
            <div
              className="h-64 overflow-auto border rounded-lg p-4 bg-gradient-to-b from-red-100 to-blue-100"
              onScroll={throttledScroll}
            >
              <div className="h-96 space-y-4">
                <p>Role esta área para ver o throttle em ação no console.</p>
                <p>O evento de scroll é limitado a executar apenas a cada 100ms.</p>
                <div className="space-y-2">
                  {Array.from({ length: 20 }, (_, i) => (
                    <div key={i} className="p-2 bg-white rounded shadow">
                      Linha {i + 1} - Role para testar o throttle
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

      </div>
    </div>
  );
};

export default PerformanceDemo;