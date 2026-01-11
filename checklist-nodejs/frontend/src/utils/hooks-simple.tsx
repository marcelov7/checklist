import React, { useState, useEffect, useMemo, useRef, useCallback } from 'react';

/**
 * Hook para computação pesada memoizada
 */
export const useHeavyComputation = (
  computeFn: () => any,
  dependencies: React.DependencyList
) => {
  return useMemo(computeFn, dependencies);
};

/**
 * Hook para debounce de valores
 */
export const useDebounce = (value: any, delay: number) => {
  const [debouncedValue, setDebouncedValue] = useState(value);

  useEffect(() => {
    const handler = setTimeout(() => {
      setDebouncedValue(value);
    }, delay);

    return () => {
      clearTimeout(handler);
    };
  }, [value, delay]);

  return debouncedValue;
};

/**
 * Hook para throttle de valores
 */
export const useThrottle = (value: any, delay: number) => {
  const [throttledValue, setThrottledValue] = useState(value);
  const lastUpdated = useRef(0);
  const pendingValue = useRef(value);
  const lastValue = useRef(value);

  if (lastValue.current !== value) {
    lastValue.current = value;
    pendingValue.current = value;
    
    const now = Date.now();
    if (now - lastUpdated.current >= delay) {
      setThrottledValue(value);
      lastUpdated.current = now;
    } else {
      setTimeout(() => {
        setThrottledValue(pendingValue.current);
        lastUpdated.current = Date.now();
      }, delay - (now - lastUpdated.current));
    }
  }

  return throttledValue;
};

/**
 * Hook para intersection observer
 */
export const useIntersectionObserver = (options = {}) => {
  const [isIntersecting, setIsIntersecting] = useState(false);
  const [entry, setEntry] = useState(null);
  const observerRef = useRef(null);
  const currentElementRef = useRef(null);

  const ref = useCallback((element: Element | null) => {
    if (observerRef.current && currentElementRef.current) {
      observerRef.current.unobserve(currentElementRef.current);
    }

    if (observerRef.current && !element) {
      observerRef.current.disconnect();
      observerRef.current = null;
    }

    currentElementRef.current = element;

    if (element) {
      if (!observerRef.current) {
        observerRef.current = new IntersectionObserver(
          ([entry]) => {
            setIsIntersecting(entry.isIntersecting);
            setEntry(entry);
          },
          {
            threshold: 0,
            root: null,
            rootMargin: '0px',
            ...options,
          }
        );
      }

      observerRef.current.observe(element);
    }
  }, [options]);

  useEffect(() => {
    return () => {
      if (observerRef.current) {
        observerRef.current.disconnect();
      }
    };
  }, []);

  return { ref, isIntersecting, entry };
};

/**
 * Hook para virtual scrolling
 */
export const useVirtualList = (
  items: any[],
  itemHeight: number,
  containerHeight: number
) => {
  const [scrollTop, setScrollTop] = useState(0);

  const startIndex = Math.floor(scrollTop / itemHeight);
  const endIndex = Math.min(
    startIndex + Math.ceil(containerHeight / itemHeight) + 1,
    items.length
  );

  const visibleItems = items.slice(startIndex, endIndex);

  const handleScroll = useCallback((e: any) => {
    setScrollTop(e.target.scrollTop);
  }, []);

  return {
    visibleItems,
    handleScroll,
    startIndex,
    endIndex,
  };
};

/**
 * Hook para monitoramento de performance
 */
export const usePerformanceMonitor = () => {
  const [metrics, setMetrics] = useState({});
  const startTimes = useRef({});

  const startMeasure = useCallback((name: string) => {
    const now = performance?.now ? performance.now() : Date.now();
    startTimes.current[name] = now;
  }, []);

  const endMeasure = useCallback((name: string) => {
    const startTime = startTimes.current[name];
    if (startTime === undefined) return;

    const endTime = performance?.now ? performance.now() : Date.now();
    const duration = endTime - startTime;

    setMetrics(prev => ({
      ...prev,
      [name]: {
        duration,
        startTime,
        endTime
      }
    }));

    delete startTimes.current[name];
  }, []);

  const clearMetrics = useCallback(() => {
    setMetrics({});
    startTimes.current = {};
  }, []);

  return {
    metrics,
    startMeasure,
    endMeasure,
    clearMetrics
  };
};

/**
 * Cache simples em memória
 */
export const memoryCache = {
  cache: new Map(),
  get(key: string) {
    return this.cache.get(key);
  },
  set(key: string, value: any) {
    this.cache.set(key, { data: value, timestamp: Date.now() });
  },
  clear() {
    this.cache.clear();
  }
};

/**
 * Hook para cache de dados
 */
export const useCachedData = (
  key: string,
  fetchFn: () => Promise<any>,
  ttl: number = 300000
) => {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const fetchData = useCallback(async () => {
    const cached = memoryCache.get(key);
    if (cached && Date.now() - cached.timestamp < ttl) {
      setData(cached.data);
      return;
    }

    setLoading(true);
    setError(null);
    
    try {
      const result = await fetchFn();
      memoryCache.set(key, result);
      setData(result);
    } catch (err) {
      setError(err instanceof Error ? err : new Error('Erro desconhecido'));
    } finally {
      setLoading(false);
    }
  }, [key, fetchFn, ttl]);

  useEffect(() => {
    fetchData();
  }, [fetchData]);

  return { data, loading, error, refetch: fetchData };
};

/**
 * Componente LazyLoad simples
 */
export const LazyLoad = ({ children, placeholder = null }) => {
  const [isVisible, setIsVisible] = useState(false);
  const { ref } = useIntersectionObserver();

  useEffect(() => {
    if (ref.current) {
      setIsVisible(true);
    }
  }, [ref]);

  return (
    <div ref={ref}>
      {isVisible ? children : placeholder}
    </div>
  );
};

/**
 * Componente de imagem otimizada
 */
export const OptimizedImage = ({ src, alt, ...props }) => {
  const [loaded, setLoaded] = useState(false);
  const [error, setError] = useState(false);

  return (
    <div style={{ position: 'relative' }}>
      {!loaded && !error && (
        <div style={{ 
          width: '100%', 
          height: '200px', 
          backgroundColor: '#f0f0f0',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center'
        }}>
          Carregando...
        </div>
      )}
      <img
        src={src}
        alt={alt}
        onLoad={() => setLoaded(true)}
        onError={() => setError(true)}
        style={{ display: loaded ? 'block' : 'none' }}
        {...props}
      />
      {error && (
        <div style={{ 
          width: '100%', 
          height: '200px', 
          backgroundColor: '#ffebee',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          color: '#c62828'
        }}>
          Erro ao carregar imagem
        </div>
      )}
    </div>
  );
};

/**
 * Função para criar componente memoizado
 */
export const createMemoComponent = (Component: any) => {
  return React.memo(Component);
};

/**
 * Função para medir performance
 */
export const measurePerformance = async (
  operation: () => Promise<any> | any,
  label: string
) => {
  const startTime = performance.now();
  
  try {
    const result = await operation();
    const endTime = performance.now();
    console.log(`⚡ ${label}: ${(endTime - startTime).toFixed(2)}ms`);
    return result;
  } catch (error) {
    const endTime = performance.now();
    console.error(`❌ ${label} falhou após ${(endTime - startTime).toFixed(2)}ms:`, error);
    throw error;
  }
};